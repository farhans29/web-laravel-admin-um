<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\TBooking;

class CheckInController extends Controller
{
    public function index()
    {
        $bookings = TBooking::with('transactions')->get(); // or use a model if you have one
        return view('pages.bookings.checkin.index', compact('bookings'));
    }

    public function checkIn($id)
    {
        $booking = TBooking::findOrFail($id);
        $booking->check_in_at = now();
        $booking->save();

        return redirect()->back()->with('success', 'Guest successfully checked in.');
    }

}
