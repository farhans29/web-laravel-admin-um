<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CheckInController extends Controller
{
    public function index()
    {
        // Set default date range (today to 1 month ahead)
        $defaultStartDate = now()->format('Y-m-d');
        $defaultEndDate = now()->addMonth()->format('Y-m-d');

        $bookings = $this->filterBookings($defaultStartDate, $defaultEndDate)
            ->paginate(request('per_page', 8));

        return view('pages.bookings.checkin.index', compact('bookings'));
    }

    protected function filterBookings($startDate = null, $endDate = null)
    {
        $query = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->where('transaction_status', 'paid'); // Only paid transactions
            })
            ->whereNull('check_out_at') // Only bookings that haven't checked out
            ->join('t_transactions', 't_booking.order_id', '=', 't_transactions.order_id')
            ->orderByRaw('ISNULL(check_in_at) DESC')
            ->orderBy('t_transactions.check_in', 'asc');

        // Apply default date range if no dates provided in request
        $requestStartDate = request('start_date', $startDate);
        $requestEndDate = request('end_date', $endDate);

        $query->whereHas('transaction', function ($q) use ($requestStartDate, $requestEndDate) {
            $q->whereBetween('check_in', [$requestStartDate, $requestEndDate]);
        });

        // Search by order_id or user name
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('t_booking.order_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('username', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%");
                    });
            });
        }

        return $query->select('t_booking.*');
    }

    public function filter(Request $request)
    {
        // Set default date range if not provided
        $startDate = $request->filled('start_date') ? $request->start_date : now()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->addMonth()->format('Y-m-d');

        $query = $this->filterBookings($startDate, $endDate);

        $bookings = $query->paginate($request->input('per_page', 8));

        return response()->json([
            'table' => view('pages.bookings.checkin.partials.checkin_table', [
                'bookings' => $bookings,
                'per_page' => $request->input('per_page', 8),
            ])->render(),
            'pagination' => $bookings->appends($request->input())->links()->toHtml()
        ]);
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
            'doc_type' => 'required|string|in:ktp,passport,sim,other',
            'doc_image' => 'required|string',
        ]);

        try {
            // Find the booking
            $booking = Booking::where('order_id', $order_id)->firstOrFail();

            // Check if already checked in
            if ($booking->check_in_at) {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking has already been checked in'
                ], 400);
            }

            // Process the document image (base64 encoded)
            $imageData = $validated['doc_image'];

            // Validate it's a proper image or PDF (basic check)
            if (!preg_match('/^data:(image\/(png|jpeg|jpg)|application\/pdf);base64,/', $imageData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid document format'
                ], 400);
            }

            // Save the document to storage
            $fileName = 'doc_' . $booking->order_id . '_' . time() . '.' . (
                str_contains($imageData, 'image/jpeg') ? 'jpg' : (str_contains($imageData, 'image/png') ? 'png' : 'pdf')
            );

            $path = 'documents/' . $fileName;
            Storage::disk('public')->put($path, base64_decode(preg_replace('/^data:\w+\/\w+;base64,/', '', $imageData)));

            // Update booking
            $updated = $booking->update([
                'check_in_at' => now(),
                'doc_type' => $validated['doc_type'],
                'doc_path' => $path,
                'updated_by' => Auth::id(),
            ]);

            // Optionally send notification
            if ($updated) {

                return response()->json([
                    'success' => true,
                    'message' => 'Check-in successful',
                    'data' => $booking
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Check-in update failed'
            ], 500);
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
