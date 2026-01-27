<?php

namespace App\Http\Controllers\Bookings\Completed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use Illuminate\Support\Carbon;

class CompletedController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction', 'refund'])
            ->whereHas('transaction', function ($q) {
                $q->whereIn('transaction_status', ['paid', 'expired', 'canceled', 'cancelled'])
                    ->where(function ($subQuery) {
                        $subQuery->whereIn('transaction_status', ['expired', 'canceled', 'cancelled'])
                            ->orWhere(function ($paidQuery) {
                                $paidQuery->where('transaction_status', 'paid')
                                    ->whereNotNull('check_in_at')
                                    ->whereNotNull('check_out_at');
                            });
                    });
            });

        // Filter by property_id for site users
        $user = Auth::user();
        if ($user && $user->isSiteRole() && $user->property_id) {
            $query->where('property_id', $user->property_id);
        }

        // ✅ Default: tampilkan data dari hari ini hingga 3 bulan ke depan
        if (!$request->filled('start_date') && !$request->filled('end_date')) {
            $startDate = now()->startOfDay();
            $endDate = now()->addMonths(3)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

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

        $bookings = $query->orderByDesc('created_at')
            ->paginate($request->input('per_page', 8));

        return view('pages.bookings.completed.index', compact('bookings'));
    }

    public function filter(Request $request)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction', 'refund'])
            ->whereHas('transaction', function ($q) {
                $q->whereIn('transaction_status', ['paid', 'expired', 'canceled', 'cancelled'])
                    ->where(function ($subQuery) {
                        $subQuery->whereIn('transaction_status', ['expired', 'canceled', 'cancelled'])
                            ->orWhere(function ($paidQuery) {
                                $paidQuery->where('transaction_status', 'paid')
                                    ->whereNotNull('check_in_at')
                                    ->whereNotNull('check_out_at');
                            });
                    });
            });

        // Filter by property_id for site users
        $user = Auth::user();
        if ($user && $user->isSiteRole() && $user->property_id) {
            $query->where('property_id', $user->property_id);
        }

        // ✅ Filter pencarian
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

        // ✅ Filter tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            if ($request->start_date === $request->end_date) {
                $query->whereDate('created_at', $request->start_date);
            } else {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
        } else {
            // Jika filter kosong → default ke hari ini hingga 3 bulan ke depan
            $startDate = now()->startOfDay();
            $endDate = now()->addMonths(3)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $bookings = $query->orderByDesc('created_at')
            ->paginate($request->input('per_page', 8));

        return response()->json([
            'table' => view('pages.bookings.allbookings.partials.allbookings_table', [
                'bookings' => $bookings,
                'per_page' => $request->input('per_page', 8),
            ])->render(),
            'pagination' => $bookings->appends($request->input())->links()->toHtml()
        ]);
    }
}
