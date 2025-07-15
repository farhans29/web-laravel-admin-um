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

        // Filter berdasarkan tanggal check-in
        if ($request->filled('date')) {
            $query->whereDate('check_in_at', $request->date);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'waiting':
                    $query->whereNull('check_in_at')->whereNull('check_out_at');
                    break;
                case 'checked-in':
                    $query->whereNotNull('check_in_at')->whereNull('check_out_at');
                    break;
                case 'checked-out':
                    $query->whereNotNull('check_out_at');
                    break;
            }
        }

        // Pagination
        $bookings = $query->paginate($request->input('per_page', 8));

        return view('pages.bookings.allbookings.index', compact('bookings'));
    }
}
