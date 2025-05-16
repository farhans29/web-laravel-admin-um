<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Booking;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('transactions')->get(); // or use a model if you have one
        return view('pages.bookings.checkin.index', compact('bookings'));
    }

    public function checkIn($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->check_in_at = now();
        $booking->save();

        return redirect()->back()->with('success', 'Guest successfully checked in.');
    }

}
