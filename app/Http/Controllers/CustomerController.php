<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $registrationStatus = $request->input('registration_status');
        $propertyId = $request->input('property_id');
        $perPage = $request->input('per_page', 8);

        // Get properties for filter dropdown
        $properties = Property::select('idrec', 'name')->orderBy('name')->get();

        // Build the customer query by combining registered users and guest transactions
        $customers = $this->getCustomersQuery($search, $registrationStatus, $propertyId)->paginate($perPage)->appends([
            'search' => $search,
            'registration_status' => $registrationStatus,
            'property_id' => $propertyId,
            'per_page' => $perPage
        ]);

        return view('pages.customers.index', [
            'customers' => $customers,
            'perPage' => $perPage,
            'properties' => $properties
        ]);
    }

    public function filter(Request $request)
    {
        $search = $request->input('search');
        $registrationStatus = $request->input('registration_status');
        $propertyId = $request->input('property_id');
        $perPage = $request->input('per_page', 8);

        $customers = $this->getCustomersQuery($search, $registrationStatus, $propertyId)->paginate($perPage);

        return view('pages.customers.partials.customer_table', [
            'customers' => $customers,
            'perPage' => $perPage
        ])->render();
    }

    private function getCustomersQuery($search = null, $registrationStatus = null, $propertyId = null)
    {
        // Get registered users with their booking statistics
        $registeredUsers = User::select([
            'users.id',
            DB::raw('CAST(users.username AS CHAR) COLLATE utf8mb4_unicode_ci as username'),
            DB::raw('CAST(users.email AS CHAR) COLLATE utf8mb4_unicode_ci as email'),
            DB::raw('CAST(users.phone_number AS CHAR) COLLATE utf8mb4_unicode_ci as phone'), // PERBAIKAN DI SINI
            DB::raw('CAST("registered" AS CHAR) COLLATE utf8mb4_unicode_ci as registration_status'),
            DB::raw('COALESCE(COUNT(DISTINCT t_transactions.order_id), 0) as total_bookings'),
            DB::raw('COALESCE(SUM(t_transactions.grandtotal_price), 0) as total_spent'),
            DB::raw('MAX(t_transactions.transaction_date) as last_booking_date'),
            DB::raw('(SELECT property_name FROM t_transactions t2 WHERE t2.user_id = users.id ORDER BY t2.transaction_date DESC LIMIT 1) as last_property_name'),
            DB::raw('(SELECT room_name FROM t_transactions t3 WHERE t3.user_id = users.id ORDER BY t3.transaction_date DESC LIMIT 1) as last_room_name')
        ])
            ->leftJoin('t_transactions', 'users.id', '=', 't_transactions.user_id')
            ->where(function ($query) {
                // Filter untuk mengecualikan admin
                $query->where('users.is_admin', '!=', 1)
                    ->orWhere('users.is_admin', 0)
                    ->orWhereNull('users.is_admin');
            })
            ->groupBy('users.id', 'users.username', 'users.email', 'users.phone_number'); // TAMBAHKAN phone_number ke GROUP BY

        // Get guest customers (transactions without user_id)
        $guestCustomers = Transaction::select([
            DB::raw('NULL as id'),
            DB::raw('CAST(user_name AS CHAR) COLLATE utf8mb4_unicode_ci as username'),
            DB::raw('CAST(user_email AS CHAR) COLLATE utf8mb4_unicode_ci as email'),
            DB::raw('CAST(user_phone_number AS CHAR) COLLATE utf8mb4_unicode_ci as phone'),
            DB::raw('CAST("guest" AS CHAR) COLLATE utf8mb4_unicode_ci as registration_status'),
            DB::raw('COUNT(DISTINCT order_id) as total_bookings'),
            DB::raw('SUM(grandtotal_price) as total_spent'),
            DB::raw('MAX(transaction_date) as last_booking_date'),
            DB::raw('(SELECT property_name FROM t_transactions t2 WHERE t2.user_email = t_transactions.user_email AND t2.user_id IS NULL ORDER BY t2.transaction_date DESC LIMIT 1) as last_property_name'),
            DB::raw('(SELECT room_name FROM t_transactions t3 WHERE t3.user_email = t_transactions.user_email AND t3.user_id IS NULL ORDER BY t3.transaction_date DESC LIMIT 1) as last_room_name')
        ])
            ->whereNull('user_id')
            ->groupBy('user_name', 'user_email', 'user_phone_number');

        // Apply property filter to registered users - only show users who have booked at this property
        if ($propertyId) {
            $registeredUsers->whereExists(function ($query) use ($propertyId) {
                $query->select(DB::raw(1))
                    ->from('t_transactions as t_prop')
                    ->whereColumn('t_prop.user_id', 'users.id')
                    ->where('t_prop.property_id', $propertyId);
            });
        }

        // Apply property filter to guest customers
        if ($propertyId) {
            $guestCustomers->where('property_id', $propertyId);
        }

        // Apply search filter to registered users
        if ($search) {
            $registeredUsers->where(function ($query) use ($search) {
                $query->where('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.email', 'like', '%' . $search . '%');
            });
        }

        // Apply search filter to guest customers
        if ($search) {
            $guestCustomers->where(function ($query) use ($search) {
                $query->where('user_name', 'like', '%' . $search . '%')
                    ->orWhere('user_email', 'like', '%' . $search . '%')
                    ->orWhere('user_phone_number', 'like', '%' . $search . '%');
            });
        }

        // Combine both queries using union
        $query = $registeredUsers->union($guestCustomers);

        // Apply registration status filter
        if ($registrationStatus && $registrationStatus !== 'all') {
            $query = DB::table(DB::raw("({$query->toSql()}) as customers"))
                ->mergeBindings($query->getQuery())
                ->where('registration_status', $registrationStatus);
        } else {
            $query = DB::table(DB::raw("({$query->toSql()}) as customers"))
                ->mergeBindings($query->getQuery());
        }

        // Order by last booking date descending (newest first)
        return $query->orderBy('last_booking_date', 'desc');
    }

    public function getBookings(Request $request, $identifier)
    {
        $type = $request->input('type', 'registered');

        if ($type === 'registered') {
            // Get bookings for registered users
            // TAMBAHKAN FILTER UNTUK MEMASTIKAN BUKAN ADMIN
            $bookings = Transaction::with(['booking', 'property', 'room', 'payment'])
                ->whereHas('user', function ($query) {
                    $query->where('is_admin', '!=', 1)
                        ->orWhere('is_admin', 0)
                        ->orWhereNull('is_admin');
                })
                ->where('user_id', $identifier)
                ->orderBy('transaction_date', 'desc')
                ->get();

            // TAMBAHKAN FILTER UNTUK MEMASTIKAN BUKAN ADMIN
            $customer = User::where('id', $identifier)
                ->where(function ($query) {
                    $query->where('is_admin', '!=', 1)
                        ->orWhere('is_admin', 0)
                        ->orWhereNull('is_admin');
                })
                ->first();
            $customerName = $customer ? $customer->username : 'Unknown';
        } else {
            // Get bookings for guest customers by email
            $bookings = Transaction::with(['booking', 'property', 'room', 'payment'])
                ->where('user_email', $identifier)
                ->whereNull('user_id')
                ->orderBy('transaction_date', 'desc')
                ->get();

            $customerName = $bookings->first()->user_name ?? 'Unknown';
        }

        return response()->json([
            'customer_name' => $customerName,
            'bookings' => $bookings->map(function ($transaction) {
                return [
                    'order_id' => $transaction->order_id,
                    'property_name' => $transaction->property_name,
                    'room_name' => $transaction->room_name,
                    'transaction_date' => $transaction->transaction_date ? $transaction->transaction_date->format('M d, Y') : '-',
                    'check_in' => $transaction->check_in ? $transaction->check_in->format('M d, Y') : '-',
                    'check_out' => $transaction->check_out ? $transaction->check_out->format('M d, Y') : '-',
                    'booking_days' => $transaction->booking_days,
                    'booking_months' => $transaction->booking_months,
                    'grandtotal_price' => number_format($transaction->grandtotal_price, 0, ',', '.'),
                    'transaction_status' => $transaction->transaction_status,
                    'booking_status' => $transaction->booking ? $transaction->booking->status : '-',
                    'payment_status' => $transaction->payment ? $transaction->payment->status : 'unpaid'
                ];
            })
        ]);
    }

    public function preRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $mainAppUrl = env('MAIN_APP_URL');
            $apiUrl = rtrim($mainAppUrl, '/') . '/auth/register-without-verification';

            $response = Http::timeout(30)->post($apiUrl, [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => $request->password,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'message' => $responseData['message'] ?? 'Registration successful.',
                    'data' => $responseData['data'] ?? null
                ], 200);
            }

            // Handle error response from external API
            return response()->json([
                'status' => 'error',
                'message' => $responseData['message'] ?? 'Registration failed. Please try again.',
                'errors' => $responseData['errors'] ?? null
            ], $response->status() ?: 400);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Pre-registration API connection error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to connect to registration service. Please try again later.'
            ], 503);

        } catch (\Exception $e) {
            Log::error('Pre-registration error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }
}
