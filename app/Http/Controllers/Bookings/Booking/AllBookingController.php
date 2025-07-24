<?php

namespace App\Http\Controllers\Bookings\Booking;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AllBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->orderByDesc('check_in_at');

        // Set default date range (today to 1 month ahead)
        $defaultStartDate = now()->format('Y-m-d');
        $defaultEndDate = now()->addMonth()->format('Y-m-d');

        // Apply date filter (use request if available, otherwise use default)
        $startDate = $request->filled('start_date') ? $request->start_date : $defaultStartDate;
        $endDate = $request->filled('end_date') ? $request->end_date : $defaultEndDate;

        $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('check_in', [$startDate, $endDate]);
        });

        // Pencarian berdasarkan order_id atau nama user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan status
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

        $bookings = $query->paginate($request->input('per_page', 8));

        return view('pages.bookings.allbookings.index', compact('bookings', 'startDate', 'endDate'));
    }

    public function filter(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->orderByDesc('check_in_at');

        // Set default date range if not provided
        $startDate = $request->filled('start_date') ? $request->start_date : now()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->addMonth()->format('Y-m-d');

        $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('check_in', [$startDate, $endDate]);
        });

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

        // Status filter - improved to match your Booking model's status logic
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

        $bookings = $query->paginate($request->input('per_page', 8));

        return response()->json([
            'table' => view('pages.bookings.allbookings.partials.allbookings_table', [
                'bookings' => $bookings,
                'per_page' => $request->input('per_page', 8),
            ])->render(),
            'pagination' => $bookings->appends($request->input())->links()->toHtml()
        ]);
    }
}
