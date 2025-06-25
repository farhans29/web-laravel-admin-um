<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;

class CheckInController extends Controller
{


    public function index()
    {
        $bookings = Booking::with(['user', 'room', 'property', 'transaction'])
            ->where(function ($query) {
                // Ambil semua data dengan status = 1
                $query->where('status', 1);

                // ATAU, jika ada order_id yang memiliki status 2
                $query->orWhereIn('order_id', function ($sub) {
                    $sub->select('order_id')
                        ->from('t_booking')
                        ->where('status', 2);
                });
            })
            ->where(function ($query) {
                // Prioritaskan status = 2 jika ada
                $query->where('status', 1)
                    ->whereNotIn('order_id', function ($sub) {
                        $sub->select('order_id')
                            ->from('t_booking')
                            ->where('status', 2);
                    })
                    ->orWhere('status', 2);
            })
            ->orderBy('check_in_at', 'asc')
            ->paginate(7);

        return view('pages.bookings.checkin.index', compact('bookings'));
    }


    public function getBookingDetails($orderId)
    {
        $booking = Booking::with(['transaction', 'property', 'room'])
            ->where('order_id', $orderId)
            ->firstOrFail();

        return response()->json($booking);
    }

    public function checkIn(Request $request, $order_id)
    {
        $validated = $request->validate([
            'ktp_img' => 'required|string', // Data URL base64
            'updated_by' => 'required|exists:users,id'
        ]);

        try {
            $booking = Booking::where('order_id', $order_id)->firstOrFail();
            $imageData = $validated['ktp_img'];
            $booking->update([
                'check_in_at' => now(),
                'ktp_img' => $imageData, // Simpan base64 string langsung
                'updated_by' => $validated['updated_by']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-in successful',
                'data' => $booking
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during check-in: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:t_booking,order_id',
            'check_out_time' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $booking = Booking::where('order_id', $validated['order_id'])->first();
        $booking->check_out_at = $validated['check_out_time'];
        $booking->save();

        return redirect()->back()->with('success', 'Guest checked out successfully');
    }
}
