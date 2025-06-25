<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{


    public function index()
    {
        $bookings = Booking::with(['user', 'room', 'property', 'transaction'])
            ->where(function ($query) {
                $query->where('status', 2)
                    ->orWhere(function ($q) {
                        $q->where('status', 1)
                            ->whereNotIn('order_id', function ($sub) {
                                $sub->select('order_id')
                                    ->from('t_booking')
                                    ->where('status', 2);
                            });
                    });
            })
            ->orderBy('idrec', 'desc')
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
            'ktp_img' => 'required|string',
        ]);

        try {
            $booking = Booking::where('order_id', $order_id)->firstOrFail();
            $imageData = $validated['ktp_img'];

            // Debug sementara
            if (!$booking) {
                return response()->json(['success' => false, 'message' => 'Booking not found']);
            }

            $updated = $booking->update([
                'check_in_at' => now(),
                'ktp_img' => $imageData,
                'updated_by' => Auth::id(),
            ]);

            if (!$updated) {
                return response()->json(['success' => false, 'message' => 'Update failed']);
            }

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
