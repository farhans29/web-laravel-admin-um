<?php

namespace App\Http\Controllers\Bookings\CheckOut;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Booking;

class CheckOutController extends Controller
{
    public function index()
    {
        
        $perPage = 10;

        // Start building the query with eager loading
        $query = Booking::with(['transaction', 'property', 'room']);
            
        // Order and paginate results
        $bookings = $query->orderBy('check_in_at', 'desc')
        ->paginate($perPage);

        return view('pages.bookings.checkout.index', compact('bookings'));
    }

    public function checkOut($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->check_out_at = now();
        $booking->save();

        return redirect()->back()->with('success', 'Guest successfully checked out.');
    }
}
