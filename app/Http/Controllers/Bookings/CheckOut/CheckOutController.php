<?php

namespace App\Http\Controllers\Bookings\CheckOut;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CheckOutController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Booking::with(['transaction', 'property', 'room', 'user'])
            ->whereHas('transaction', function ($q) {
                $q->where('transaction_status', 'paid');
            })
            ->whereNotNull('check_in_at')
            ->whereNotNull('check_out_at');

        // Filter by property_id for site users
        $user = Auth::user();
        if ($user && $user->isSiteRole() && $user->property_id) {
            $query->where('property_id', $user->property_id);
        }

        // Search by order_id or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Date range filter - using check_in_at from booking table
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query->whereBetween('check_in_at', [
                $startDate,
                Carbon::parse($endDate)->endOfDay()
            ]);
        } else {
            // Default filter: from today to 1 month ahead
            $query->whereBetween('check_in_at', [
                now()->startOfDay(),
                now()->addMonth()->endOfDay()
            ]);
        }

        $bookings = $query
            ->orderByRaw('CASE WHEN check_out_at IS NULL THEN 0 ELSE 1 END') // NULL values first
            ->orderBy('check_out_at', 'desc') // Then sort by check_out_at
            ->paginate($perPage);

        return view('pages.bookings.checkout.index', compact('bookings'));
    }

    public function filter(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->where('transaction_status', 'paid');
            })
            ->whereNotNull('check_in_at')
            ->whereNotNull('check_out_at')
            ->orderByRaw('CASE WHEN check_out_at IS NULL THEN 0 ELSE 1 END') // NULL values first
            ->orderBy('check_out_at', 'desc'); // Then sort by check_out_at

        // Filter by property_id for site users
        $user = Auth::user();
        if ($user && $user->isSiteRole() && $user->property_id) {
            $query->where('property_id', $user->property_id);
        }

        // Search by order_id or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        // Date range filter - using check_in_at from booking table
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in_at', [
                    $startDate,
                    Carbon::parse($endDate)->endOfDay()
                ]);
            });
        } else {
            // Default filter: from today to 1 month ahead
            $query->whereBetween('check_in_at', [
                now()->startOfDay(),
                now()->addMonth()->endOfDay()
            ]);
        }

        $bookings = $query->paginate($request->input('per_page', 8));

        return response()->json([
            'table' => view('pages.bookings.checkout.partials.checkout_table', [
                'bookings' => $bookings,
                'per_page' => $request->input('per_page', 8),
            ])->render(),
            'pagination' => $bookings->appends($request->input())->links()->toHtml()
        ]);
    }

    public function checkOut($order_id)
    {
        // Cari booking berdasarkan order_id
        $booking = Booking::where('order_id', $order_id)->firstOrFail();

        $booking->check_out_at = now();
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Guest successfully checked out.'
        ]);
    }

    public function getBookingDetails($orderId)
    {
        $booking = Booking::with(['transaction', 'property', 'room', 'user'])
            ->where('order_id', $orderId)
            ->firstOrFail();

        $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

        return response()->json([
            'order_id' => $booking->order_id,
            'user_name' => $transaction->user_name,
            'property_name' => $transaction->property_name,
            'room_name' => $transaction->room_name,
            'check_in' => $transaction->check_in,
            'check_out' => $transaction->check_out,
            'grandtotal_price' => $transaction->grandtotal_price,
            // Add any other fields you need from either model
            'actual_check_in' => $booking->check_in_at, // From booking model
            'actual_check_out' => $booking->check_out_at // Will be null until checked out
        ]);
    }
}
