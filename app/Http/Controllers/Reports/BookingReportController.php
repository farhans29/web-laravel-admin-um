<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Exports\BookingReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BookingReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get properties for filter
        $properties = Property::where('status', 1)
            ->orderBy('name')
            ->get();

        // Set default date range (current month)
        $defaultStartDate = now()->startOfMonth()->format('Y-m-d');
        $defaultEndDate = now()->endOfMonth()->format('Y-m-d');

        // Get filter values
        $startDate = $request->filled('start_date') ? $request->start_date : $defaultStartDate;
        $endDate = $request->filled('end_date') ? $request->end_date : $defaultEndDate;
        $status = $request->input('status');
        $propertyId = $request->input('property_id');

        return view('pages.reports.booking-report.index', compact(
            'properties',
            'startDate',
            'endDate',
            'status',
            'propertyId'
        ));
    }

    public function getData(Request $request)
    {
        $user = Auth::user();

        $query = Booking::with(['room', 'property', 'transaction', 'payment'])
            ->orderByDesc('created_at');

        // Date range filter based on booking date (transaction check_in date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'pending':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'pending');
                    });
                    break;
                case 'waiting':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'waiting');
                    });
                    break;
                case 'waiting-check-in':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'paid');
                    })->whereNull('check_in_at')->whereNull('check_out_at');
                    break;
                case 'checked-in':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'paid');
                    })->whereNotNull('check_in_at')->whereNull('check_out_at');
                    break;
                case 'checked-out':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'paid');
                    })->whereNotNull('check_in_at')->whereNotNull('check_out_at');
                    break;
                case 'canceled':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'canceled');
                    });
                    break;
                case 'expired':
                    $query->whereHas('transaction', function ($q) {
                        $q->where('transaction_status', 'expired');
                    });
                    break;
            }
        }

        // Property filter - HO can filter by property location
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhereHas('room', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('property', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('location', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->paginate($request->input('per_page', 15));

        // Transform data for display
        $data = $bookings->map(function ($booking) {
            $transaction = $booking->transaction;
            $payment = $booking->payment;

            return [
                'booking_date' => $transaction ? Carbon::parse($transaction->check_in)->format('d M Y') : '-',
                'booking_number' => $booking->order_id,
                'name' => $booking->user_name ?? '-',
                'location' => $booking->property ? ($booking->property->location ?? $booking->property->name) : '-',
                'room' => $booking->room ? $booking->room->name : '-',
                'check_in' => $transaction ? Carbon::parse($transaction->check_in)->format('d M Y') : '-',
                'check_out' => $transaction ? Carbon::parse($transaction->check_out)->format('d M Y') : '-',
                'payment_date' => $payment && $payment->created_at ? Carbon::parse($payment->created_at)->format('d M Y') : '-',
                'payment_time' => $payment && $payment->created_at ? Carbon::parse($payment->created_at)->format('H:i') : '-',
                'status' => $booking->status ?? 'Unknown',
                'notes' => $transaction ? $transaction->notes : '',
                'raw_status' => $transaction ? $transaction->transaction_status : 'unknown',
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
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'status' => $request->input('status'),
            'property_id' => $request->input('property_id'),
            'search' => $request->input('search'),
        ];

        $filename = 'booking-report-' . now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new BookingReportExport($filters), $filename);
    }
}
