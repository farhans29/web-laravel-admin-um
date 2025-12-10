<?php

namespace App\Http\Controllers\RoomAvailability;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;

class RoomAvailabilityController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 8);
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Query untuk room availability
        $rooms = Room::with(['property', 'thumbnail', 'bookings' => function ($query) use ($startDate, $endDate) {
            // Hanya ambil booking dengan status aktif (1)
            $query->where('status', 1)
                ->with(['user', 'transaction', 'payment']);

            // Filter berdasarkan tanggal menggunakan transaction dates untuk konsistensi
            if ($startDate && $endDate) {
                $query->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                    $q->where(function ($q2) use ($startDate, $endDate) {
                        // Check if booking overlaps with the selected date range
                        $q2->whereBetween('check_in', [$startDate, $endDate])
                            ->orWhereBetween('check_out', [$startDate, $endDate])
                            ->orWhere(function ($q3) use ($startDate, $endDate) {
                                // Booking spans the entire date range
                                $q3->where('check_in', '<=', $startDate)
                                    ->where('check_out', '>=', $endDate);
                            });
                    });
                });
            }
        }])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('no', 'like', "%{$search}%")
                        ->orWhereHas('property', function ($q2) use ($search) {
                            $q2->where('property_name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status !== 'all', function ($query) use ($status) {
                if ($status === 'available') {
                    $query->where('rental_status', 0);
                } elseif ($status === 'booked') {
                    $query->where('rental_status', 1);
                }
            })
            ->orderBy('property_name')
            ->orderBy('no')
            ->paginate($perPage)
            ->withQueryString();

        // Jika request AJAX, return JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.room_availability.partials.roomAvailability_table', compact('rooms'))->render(),
                'pagination' => $rooms->links()->toHtml()
            ]);
        }

        return view('pages.room_availability.index', compact('rooms'));
    }

    public function getRoomBookings($roomId, Request $request)
    {
        $room = Room::with(['property'])->findOrFail($roomId);

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $bookingsQuery = Booking::where('room_id', $roomId)
            ->where('status', 1)
            ->with(['user', 'transaction', 'payment']);


        if ($startDate && $endDate) {
            $bookingsQuery->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->where(function ($q2) use ($startDate, $endDate) {

                    $q2->whereBetween('check_in', [$startDate, $endDate])
                        ->orWhereBetween('check_out', [$startDate, $endDate])
                        ->orWhere(function ($q3) use ($startDate, $endDate) {

                            $q3->where('check_in', '<=', $startDate)
                                ->where('check_out', '>=', $endDate);
                        });
                });
            });
        }

        $bookings = $bookingsQuery->get();

        $formattedBookings = $bookings->map(function ($booking) {

            if (!$booking->transaction) {
                return null;
            }

            $checkIn = new \DateTime($booking->transaction->check_in);
            $checkOut = new \DateTime($booking->transaction->check_out);

            $durationText = '';
            if ($booking->transaction->booking_type === 'monthly') {

                $months = $booking->transaction->booking_months ?? 0;
                $durationText = $months . ($months == 1 ? ' bulan' : ' bulan');
            } elseif ($booking->transaction->booking_type === 'daily') {

                $days = $booking->transaction->booking_days ?? $checkIn->diff($checkOut)->days;
                $durationText = $days . ($days == 1 ? ' malam' : ' malam');
            } else {

                $days = $checkIn->diff($checkOut)->days;
                $durationText = $days . ($days == 1 ? ' malam' : ' malam');
            }

            $userName = 'N/A';
            if ($booking->user && $booking->user->first_name) {
                $userName = $booking->user->first_name;
            } elseif ($booking->transaction->user_name) {
                $userName = $booking->transaction->user_name;
            } elseif ($booking->user_name) {
                $userName = $booking->user_name;
            }

            $userEmail = 'N/A';
            if ($booking->user && $booking->user->email) {
                $userEmail = $booking->user->email;
            } elseif ($booking->transaction->user_email) {
                $userEmail = $booking->transaction->user_email;
            } elseif ($booking->user_email) {
                $userEmail = $booking->user_email;
            }
            return [
                'id' => $booking->idrec,
                'booking_code' => $booking->order_id,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'check_in' => $booking->transaction->check_in,
                'check_out' => $booking->transaction->check_out,
                'booking_type' => $booking->transaction->booking_type ?? 'daily',
                'duration' => $durationText,
                'total_amount' => $booking->payment->grandtotal_price ?? 0,
                'payment_status' => $booking->payment->payment_status ?? 'pending',
                'status' => $booking->status,
                'status_badge' => $this->getStatusBadgeFromText($booking->status),
                'created_at' => $booking->created_at->format('d M Y H:i')
            ];
        })->filter();

        return response()->json([
            'success' => true,
            'room_name' => $room->name . ' - ' . ($room->property->property_name ?? ''),
            'bookings' => $formattedBookings,
            'total_bookings' => $bookings->count()
        ]);
    }

    // Badge warna berdasarkan status text (computed accessor)
    private function getStatusBadgeFromText($statusText)
    {
        $badges = [
            'Waiting For Payment' => 'bg-yellow-100 text-yellow-800',
            'Waiting For Confirmation' => 'bg-orange-100 text-orange-800',
            'Waiting For Check-In' => 'bg-blue-100 text-blue-800',
            'Checked-In' => 'bg-green-100 text-green-800',
            'Checked-Out' => 'bg-gray-100 text-gray-800',
            'Canceled' => 'bg-red-100 text-red-800',
            'Expired' => 'bg-red-100 text-red-800',
            'Payment Failed' => 'bg-red-100 text-red-800',
        ];

        return $badges[$statusText] ?? 'bg-gray-100 text-gray-800';
    }

    // Status text (deprecated - kept for backward compatibility)
    private function getStatusText($status)
    {
        $statuses = [
            0 => 'Pending',
            1 => 'Confirmed',
            2 => 'Checked In',
            3 => 'Checked Out',
            4 => 'Cancelled'
        ];

        return $statuses[$status] ?? 'Unknown';
    }

    // Badge warna (deprecated - kept for backward compatibility)
    private function getStatusBadge($status)
    {
        $badges = [
            0 => 'bg-yellow-100 text-yellow-800',
            1 => 'bg-green-100 text-green-800',
            2 => 'bg-blue-100 text-blue-800',
            3 => 'bg-gray-100 text-gray-800',
            4 => 'bg-red-100 text-red-800'
        ];

        return $badges[$status] ?? 'bg-gray-100 text-gray-800';
    }


    public function getAvailabilityData(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $search = $request->get('search');
        $status = $request->get('status', 'all');

        // Query yang sama dengan index untuk konsistensi data
        $roomsQuery = Room::when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('no', 'like', "%{$search}%")
                    ->orWhereHas('property', function ($q2) use ($search) {
                        $q2->where('property_name', 'like', "%{$search}%");
                    });
            });
        })
            ->when($status !== 'all', function ($query) use ($status) {
                if ($status === 'available') {
                    $query->where('rental_status', 0);
                } elseif ($status === 'booked') {
                    $query->where('rental_status', 1);
                }
            });

        $totalRooms = $roomsQuery->count();
        $availableRooms = $roomsQuery->clone()->where('rental_status', 0)->count();
        $bookedRooms = $roomsQuery->clone()->where('rental_status', 1)->count();

        return response()->json([
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'booked_rooms' => $bookedRooms
        ]);
    }

    public function updateRentalStatus(Request $request, $id)
    {
        $request->validate([
            'rental_status' => 'required|in:0,1'
        ]);

        $room = Room::findOrFail($id);
        $room->update([
            'rental_status' => $request->rental_status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kamar berhasil diupdate',
            'rental_status' => $room->rental_status,
            'status_text' => $room->rental_status == 1 ? 'Booked' : 'Available'
        ]);
    }
}
