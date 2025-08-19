<?php

namespace App\Http\Controllers\Bookings\NewReservation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewReservController extends Controller
{
    public function index()
    {
        // Set default date range (today to 1 month ahead)
        $defaultStartDate = now()->format('Y-m-d');
        $defaultEndDate = now()->addMonth()->format('Y-m-d');

        $perPage = request('per_page', 8);

        $checkIns = Booking::with(['transaction', 'property', 'room', 'user'])
            ->whereHas('transaction', function ($q) {
                $q->where('transaction_status', 'paid');
            })
            ->whereNotNull('check_in_at')
            ->orderByRaw('CASE WHEN check_out_at IS NULL THEN 0 ELSE 1 END')
            ->orderBy('check_out_at', 'desc');

        // Apply date filtering if filterBookings is a scope
        $checkIns = $this->filterBookings($defaultStartDate, $defaultEndDate)->paginate($perPage);

        return view('pages.bookings.newreservations.index', compact('checkIns'));
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

        // Apply date filter if provided
        if (request()->filled('start_date') && request()->filled('end_date')) {
            $startDate = request('start_date');
            $endDate = request('end_date');

            // Jika start_date dan end_date sama, cari hanya untuk tanggal itu
            if ($startDate === $endDate) {
                $query->whereHas('transaction', function ($q) use ($startDate) {
                    $q->whereDate('check_in', '<=', $startDate)->whereDate('check_out', '>=', $startDate);
                });
            } else {
                // Jika range tanggal, gunakan between seperti sebelumnya
                $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('check_in', [$startDate, $endDate]);
                });
            }
        } else {
            // Gunakan default date range jika tidak ada input
            $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('check_in', [$startDate, $endDate]);
            });
        }

        // Search by order_id or user name
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('t_booking.order_id', 'like', "%{$search}%")->orWhereHas('user', function ($q) use ($search) {
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

        $checkIns = $query->paginate($request->input('per_page', 8));

        return response()->json([
            'table' => view('pages.bookings.newreservations.partials.newreserve_table', [
                'bookings' => $checkIns,
                'per_page' => $request->input('per_page', 8),
            ])->render(),
            'pagination' => $checkIns->appends($request->input())->links()->toHtml(),
        ]);
    }

    public function checkIn(Request $request, $order_id)
    {
        $validated = $request->validate([
            'doc_type' => 'required|string|in:ktp,passport,sim,other',
            'doc_image' => 'required|string', // Ubah menjadi required karena sekarang wajib ada
            'has_profile_photo' => 'sometimes|boolean',
        ]);

        try {
            // Find the booking
            $booking = Booking::where('order_id', $order_id)->firstOrFail();

            // Check if already checked in
            if ($booking->check_in_at) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This booking has already been checked in',
                    ],
                    400,
                );
            }

            $path = null;
            $imageData = $validated['doc_image'];

            if (!preg_match('/^data:(image\/(png|jpeg|jpg)|application\/pdf);base64,/', $imageData)) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Invalid document format',
                    ],
                    400,
                );
            }

            $fileName = 'doc_' . $booking->order_id . '_' . time() . '.' . (str_contains($imageData, 'image/jpeg') ? 'jpg' : (str_contains($imageData, 'image/png') ? 'png' : 'pdf'));

            $path = 'documents/' . $fileName;
            Storage::disk('public')->put($path, base64_decode(preg_replace('/^data:\w+\/\w+;base64,/', '', $imageData)));

            $updated = $booking->update([
                'check_in_at' => now(),
                'doc_type' => $validated['doc_type'],
                'doc_path' => $path,
                'updated_by' => Auth::id(),
                'verified_with_profile' => !empty($validated['has_profile_photo']),
            ]);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Check-in successful',
                    'data' => $booking,
                ]);
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Check-in update failed',
                ],
                500,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error during check-in: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function getBookingDetails($orderId)
    {
        $booking = Booking::with(['transaction', 'property', 'room', 'transaction.user', 'user'])
            ->where('order_id', $orderId)
            ->firstOrFail();

        $response = $booking->toArray();

        // Tambahkan dua versi URL foto profil jika tersedia
        if ($booking->transaction->user && $booking->transaction->user->profile_photo_path) {
            $photoPath = $booking->transaction->user->profile_photo_path;
            $response['user_profile_photo_demo'] = 'https://demo-ulinmahoni.integrated-os.cloud/storage/' . $photoPath;
            $response['user_profile_photo_web'] = 'https://web.ulinmahoni.com/storage/' . $photoPath;
        } else {
            $response['user_profile_photo_demo'] = null;
            $response['user_profile_photo_web'] = null;
        }

        return response()->json($response);
    }
}
