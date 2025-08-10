<?php

namespace App\Http\Controllers\Bookings\Completed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class CompletedController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->whereIn('transaction_status', ['paid', 'expired', 'canceled'])
                    ->where(function ($subQuery) {
                        $subQuery->where('transaction_status', '!=', 'paid')
                            ->orWhere(function ($paidQuery) {
                                $paidQuery->where('transaction_status', 'paid')
                                    ->whereNotNull('check_in_at')
                                    ->whereNotNull('check_out_at');
                            });
                    });
            })
            ->orderByDesc('check_in_at');

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

        $bookings = $query->paginate($request->input('per_page', 8));

        return view('pages.bookings.completed.index', compact('bookings'));
    }

    public function filter(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->whereIn('transaction_status', ['paid', 'expired', 'canceled'])
                    ->where(function ($subQuery) {
                        $subQuery->where('transaction_status', '!=', 'paid')
                            ->orWhere(function ($paidQuery) {
                                $paidQuery->where('transaction_status', 'paid')
                                    ->whereNotNull('check_in_at')
                                    ->whereNotNull('check_out_at');
                            });
                    });
            })
            ->orderByDesc('check_in_at');

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

        // Date range filter (only in filter method)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Jika start_date dan end_date sama, cari tanggal yang tepat
            if ($request->start_date === $request->end_date) {
                $query->whereHas('transaction', function ($q) use ($request) {
                    $q->whereDate('check_in', $request->start_date);
                });
            } else {
                $query->whereHas('transaction', function ($q) use ($request) {
                    $q->whereBetween('check_in', [$request->start_date, $request->end_date]);
                });
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
