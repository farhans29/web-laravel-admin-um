<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.bookings.checkin.index');
    }

    public function checkIn(Request $request, $id)
    {

        //Validate Input Deposit
        $validated = $request->validate([
            'deposit_amount' => 'required|string|max:255',
        ]);

        $booking = Booking::findOrFail($id);
        $booking->check_in_at = now();
        $booking->deposit_amount = $validated['deposit_amount'];
        $booking->save();

        return redirect()->back()->with('success', 'Guest successfully checked in.');
    }

}
