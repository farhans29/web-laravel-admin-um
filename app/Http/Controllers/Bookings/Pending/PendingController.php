<?php

namespace App\Http\Controllers\Bookings\Pending;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class PendingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->whereIn('transaction_status', ['pending', 'waiting']);
            })
            ->orderByDesc('check_in_at');

        // Pencarian berdasarkan order_id atau nama user
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

        // Filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate   = $request->end_date;

            $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                if ($startDate === $endDate) {
                    // Jika tanggal sama → cocokkan persis tanggal check_in
                    $q->whereDate('check_in', $startDate);
                } else {
                    // Jika berbeda → rentang tanggal
                    $q->whereBetween('check_in', [
                        $startDate . ' 00:00:00',
                        $endDate   . ' 23:59:59',
                    ]);
                }
            });
        }

        $bookings = $query->paginate($request->input('per_page', 8));

        return view('pages.bookings.pending.index', compact('bookings'));
    }

    public function filter(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->whereIn('transaction_status', ['pending', 'waiting']);
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

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate   = $request->end_date;

            $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                if ($startDate === $endDate) {
                    $q->whereDate('check_in', $startDate);
                } else {
                    $q->whereBetween('check_in', [
                        $startDate . ' 00:00:00',
                        $endDate   . ' 23:59:59',
                    ]);
                }
            });
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
