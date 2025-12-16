<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Exports\RentedRoomsReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RentedRoomsReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get properties for filter
        $properties = Property::where('status', 1)
            ->orderBy('name')
            ->get();

        // Set default date (today)
        $defaultDate = now()->format('Y-m-d');

        // Get filter values
        $selectedDate = $request->filled('selected_date') ? $request->selected_date : $defaultDate;
        $propertyId = $request->input('property_id');
        $bookingType = $request->input('booking_type', 'all');

        return view('pages.reports.rented-rooms-report.index', compact(
            'properties',
            'selectedDate',
            'propertyId',
            'bookingType'
        ));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = Booking::with([
                'transaction',
                'payment',
                'property',
                'room'
            ])
            ->whereHas('transaction', function ($q) {
                // Exclude rejected/canceled bookings
                $q->whereNotIn('transaction_status', ['canceled', 'cancelled', 'rejected']);
            })
            ->orderByDesc('created_at');

        // Date filter (rooms occupied on selected date)
        if ($request->filled('selected_date')) {
            $selectedDate = $request->selected_date;

            $query->whereHas('transaction', function ($q) use ($selectedDate) {
                $q->where('check_in', '<=', $selectedDate . ' 23:59:59')
                  ->where('check_out', '>=', $selectedDate . ' 00:00:00')
                  ->whereNotIn('transaction_status', ['canceled', 'cancelled', 'rejected']);
            });
        }

        // Booking type filter
        if ($request->filled('booking_type') && $request->booking_type !== 'all') {
            $query->whereHas('transaction', function ($q) use ($request) {
                $q->where('booking_type', $request->booking_type);
            });
        }

        // Property filter
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        // Search filter (tenant name, room number, order_id)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('order_id', 'like', "%{$search}%")
                    ->orWhereHas('room', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->paginate($request->input('per_page', 15));

        // Transform data for display (14 columns)
        $data = $bookings->map(function ($booking, $index) use ($bookings) {
            $transaction = $booking->transaction;

            // Calculate duration from transaction fields
            $duration = '-';
            $days = 0;
            $months = 0;

            if ($transaction) {
                if ($transaction->booking_type === 'daily') {
                    $days = $transaction->booking_days ?? 1;
                    $duration = $days . ' day' . ($days != 1 ? 's' : '');
                } else {
                    $months = $transaction->booking_months ?? 1;
                    $duration = $months . ' month' . ($months != 1 ? 's' : '');
                }
            }

            $offset = ($bookings->currentPage() - 1) * $bookings->perPage();

            return [
                'no' => $offset + $index + 1,
                'property' => $booking->property ? $booking->property->name : '-',
                'tenant_name' => $booking->user_name ?? '-',
                'room_number' => $booking->room ? $booking->room->name : '-',
                'booking_type' => $transaction ? ucfirst($transaction->booking_type) : '-',
                'check_in' => $transaction && $transaction->check_in ?
                    Carbon::parse($transaction->check_in)->format('d M Y') : '-',
                'check_out' => $transaction && $transaction->check_out ?
                    Carbon::parse($transaction->check_out)->format('d M Y') : '-',
                'duration' => $duration,
                'room_price' => 'Rp ' . number_format($transaction->room_price ?? 0, 0, ',', '.'),
                'admin_fee' => 'Rp ' . number_format($transaction->admin_fees ?? 0, 0, ',', '.'),
                'grand_total' => 'Rp ' . number_format($transaction->grandtotal_price ?? 0, 0, ',', '.'),
                'payment_status' => $transaction ? $transaction->transaction_status : '-',
                'payment_date' => $transaction && $transaction->paid_at ?
                    Carbon::parse($transaction->paid_at)->format('d M Y') : '-',
                'order_id' => $booking->order_id,
                'days' => $days,
                'months' => $months,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ]
        ]);
    }

    public function export(Request $request)
    {
        $filters = [
            'selected_date' => $request->input('selected_date'),
            'booking_type' => $request->input('booking_type'),
            'property_id' => $request->input('property_id'),
            'search' => $request->input('search'),
        ];

        $filename = 'rented-rooms-report-' . now()->format('Y-m-d-His') . '.xlsx';

        $exporter = new RentedRoomsReportExport($filters);
        return $exporter->export($filename);
    }
}
