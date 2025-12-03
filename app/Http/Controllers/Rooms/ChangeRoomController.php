<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\RoomTransferNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ChangeRoomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $historySearch = $request->input('history_search');
        $currentDate = now()->format('Y-m-d');
        $propertyId = $request->input('property_id');

        // Get only active checked-in bookings (status 1) that are eligible for room transfer
        $bookings = Booking::with(['user', 'room', 'property', 'transaction', 'payment'])
            ->where('status', 1) // Only active bookings
            ->whereHas('payment', function ($q) {
                $q->where('payment_status', 'paid'); // Only paid bookings
            })
            ->whereNotNull('check_in_at') // Guest must have checked in
            ->whereNull('check_out_at') // Guest has not checked out yet
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    // Search by order_id
                    $q->where('order_id', 'like', '%' . $search . '%')
                        // Search by username
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('username', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy('check_in_at', 'desc')
            ->paginate(3);

        // Get available rooms (unchanged)
        $availableRooms = Room::whereDoesntHave('bookings', function ($query) use ($currentDate) {
            $query->where('status', 1)
                ->whereDate('check_in_at', '<=', $currentDate)
                ->whereDate('check_out_at', '>=', $currentDate);
        })
            ->when($propertyId, function ($query, $propertyId) {
                return $query->where('property_id', $propertyId);
            })
            ->get();

        // Modified transfer history query with search
        // Show bookings with status 3 (transferred out) as they represent completed room transfers
        $transferHistory = Booking::with(['user', 'room', 'property'])
            ->where('status', 3) // Status 3 means transferred out
            ->whereNotNull('reason') // Must have a transfer reason
            ->when($historySearch, function ($query, $historySearch) {
                return $query->where(function ($q) use ($historySearch) {
                    $q->where('order_id', 'like', '%' . $historySearch . '%')
                        ->orWhereHas('user', function ($uq) use ($historySearch) {
                            $uq->where('username', 'like', '%' . $historySearch . '%');
                        });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->through(function ($history) {
                // The current record (status 3) is the previous room
                // Find the new room (status 1) with the same order_id
                $newRoomBooking = Booking::with('room')
                    ->where('order_id', $history->order_id)
                    ->where('status', 1)
                    ->where('room_id', '!=', $history->room_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                return [
                    'order_id' => $history->order_id,
                    'guest_name' => $history->user->username ?? 'N/A',
                    'previous_room' => $history, // This is the old room (status 3)
                    'current_room' => $newRoomBooking, // This is the new room (status 1)
                    'reason' => $history->reason,
                    'created_at' => $history->updated_at, // Use updated_at as transfer timestamp
                ];
            });

        return view('pages.rooms.changerooms.index', compact(
            'bookings',
            'search',
            'availableRooms',
            'transferHistory',
            'historySearch'
        ));
    }


    public function getAvailableRooms(Request $request)
    {
        $propertyId = $request->input('property_id');
        $roomId     = $request->input('room_id');
        $checkIn    = $request->input('check_in');
        $checkOut   = $request->input('check_out');

        if (!$checkIn || !$checkOut || !$propertyId) {
            return response()->json(['error' => 'Missing required fields.'], 400);
        }
        $checkInDate  = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);

        $rooms = Room::where('property_id', $propertyId)
            ->where('status', 1)
            ->when($roomId, function ($query) use ($roomId) {
                return $query->where('idrec', '!=', $roomId);
            })
            ->get();

        $availableRooms = $rooms->filter(function ($room) use ($checkInDate, $checkOutDate) {
            // Check if room has any booking conflicts for the given date range
            // Only check for active bookings (status 1) and transferred bookings (status 2)
            $hasConflict = Booking::where('room_id', $room->idrec)
                ->whereIn('status', [1, 2]) // Active and transferred bookings
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    // Case 1: Booking check-in falls within the transfer period
                    $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_at', '>=', $checkInDate)
                          ->where('check_in_at', '<', $checkOutDate);
                    })
                    // Case 2: Booking check-out falls within the transfer period
                    ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_out_at', '>', $checkInDate)
                          ->where('check_out_at', '<=', $checkOutDate);
                    })
                    // Case 3: Booking completely encompasses the transfer period
                    ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_at', '<=', $checkInDate)
                          ->where('check_out_at', '>=', $checkOutDate);
                    });
                })
                ->exists();

            return !$hasConflict;
        });


        return response()->json($availableRooms->values());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_property_id' => 'required|string|max:100',
            'order_id' => 'required|string|max:100',
            'new_room' => 'required|string|max:255',
            'check_in' => 'required|date',
            'check_out' => 'nullable|date',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fill all required fields.');
        }

        try {
            // Get the previous booking to find the user and previous room
            $previousBooking = Booking::where('order_id', $request->order_id)
                ->where('status', 1) // Must be active status
                ->whereNotNull('check_in_at') // Must have checked in
                ->whereNull('check_out_at') // Must not have checked out
                ->firstOrFail();

            // Validate that new room is different from current room
            if ($previousBooking->room_id == $request->new_room) {
                return redirect()->back()
                    ->with('error', 'Kamar baru tidak boleh sama dengan kamar saat ini.');
            }

            // Get room details
            $previousRoom = Room::findOrFail($previousBooking->room_id);
            $newRoom = Room::findOrFail($request->new_room);

            // Validate new room belongs to same property
            if ($newRoom->property_id != $request->current_property_id) {
                return redirect()->back()
                    ->with('error', 'Kamar baru harus dalam properti yang sama.');
            }

            // Check if new room has any booking conflicts
            $checkInDate = Carbon::parse($request->check_in);
            $checkOutDate = $request->check_out ? Carbon::parse($request->check_out) : $previousBooking->check_out_at;

            $hasConflict = Booking::where('room_id', $request->new_room)
                ->whereIn('status', [1, 2]) // Active or transferred bookings
                ->where('order_id', '!=', $request->order_id) // Exclude current booking
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_at', '>=', $checkInDate)
                          ->where('check_in_at', '<', $checkOutDate);
                    })
                    ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_out_at', '>', $checkInDate)
                          ->where('check_out_at', '<=', $checkOutDate);
                    })
                    ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                        $q->where('check_in_at', '<=', $checkInDate)
                          ->where('check_out_at', '>=', $checkOutDate);
                    });
                })
                ->exists();

            if ($hasConflict) {
                return redirect()->back()
                    ->with('error', 'Kamar yang dipilih sudah dipesan untuk tanggal tersebut.');
            }

            // Get user who made the booking
            $user = $previousBooking->user;

            // Create the new booking (transfer) - copy all relevant data from previous booking
            $booking = Booking::create([
                'property_id' => $request->current_property_id,
                'order_id' => $request->order_id,
                'room_id' => $request->new_room,
                'user_id' => $previousBooking->user_id,
                'transaction_id' => $previousBooking->transaction_id,
                'payment_id' => $previousBooking->payment_id,
                'check_in_at' => $previousBooking->check_in_at, // Keep original check-in time
                'check_out_at' => $previousBooking->check_out_at, // Keep original check-out time
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'status' => 1, // Set to active status for the new room
                'reason' => $request->reason,
                'description' => $request->notes,
            ]);

            // Mark previous booking as transferred out (status 3)
            $previousBooking->status = 3;
            $previousBooking->updated_by = Auth::id();
            $previousBooking->check_out_at = now(); // Mark the time they left the old room
            $previousBooking->save();

            // Prepare transfer details for notification
            $transferDetails = [
                'guest_name' => $user->username,
                'order_id' => $request->order_id,
                'previous_room' => $previousRoom->name . ' (' . $previousRoom->type . ')',
                'new_room' => $newRoom->name . ' (' . $newRoom->type . ')',
                'reason' => $request->reason,
                'transfer_date' => now()->format('Y-m-d H:i'),
                'check_in' => $previousBooking->check_in_at->format('Y-m-d H:i'),
                'check_out' => $previousBooking->check_out_at ? $previousBooking->check_out_at->format('Y-m-d H:i') : 'Not specified',
            ];

            // Send notification to user
            if ($user && $user->email) {
                Notification::send($user, new RoomTransferNotification($transferDetails));
            }

            return redirect()->route('changerooom.index')->with('success', 'Kamar berhasil dipindahkan dan notifikasi telah dikirim!');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Booking tidak ditemukan atau tidak dapat dipindahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memindahkan kamar: ' . $e->getMessage());
        }
    }
}
