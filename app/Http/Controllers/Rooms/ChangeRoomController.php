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

        // Existing bookings query (unchanged)
        $priorityOrderIds = Booking::select('order_id')
            ->where('status', 2)
            ->groupBy('order_id');

        $bookings = Booking::with(['user', 'room', 'property', 'transaction', 'payment'])
            ->where(function ($query) use ($priorityOrderIds) {
                $query->whereIn('order_id', $priorityOrderIds)
                    ->where('status', 2)
                    ->orWhere(function ($query) use ($priorityOrderIds) {
                        $query->whereNotIn('order_id', $priorityOrderIds)
                            ->where('status', 1);
                    });
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    // cari berdasarkan order_id
                    $q->where('order_id', 'like', '%' . $search . '%')
                        // cari berdasarkan username user
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('username', 'like', '%' . $search . '%');
                        });
                });
            })
            ->whereHas('payment', function ($q) {
                $q->where('payment_status', 'paid'); // hanya yang paid
            })
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
        $transferHistory = Booking::with(['user', 'room', 'property'])
            ->where('status', 2)
            ->when($historySearch, function ($query, $historySearch) {
                return $query->where(function ($q) use ($historySearch) {
                    $q->where('order_id', 'like', '%' . $historySearch . '%')
                        ->orWhereHas('user', function ($uq) use ($historySearch) {
                            $uq->where('username', 'like', '%' . $historySearch . '%');
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function ($history) {
                return [
                    'order_id' => $history->order_id,
                    'guest_name' => $history->user->username ?? 'N/A',
                    'previous_room' => Booking::with('room')
                        ->where('order_id', $history->order_id)
                        ->where('status', 1)
                        ->first(),
                    'current_room' => $history,
                    'reason' => $history->reason,
                    'created_at' => $history->created_at,
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
            $hasConflict = Booking::where('room_id', $room->idrec)
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->whereBetween('check_in_at', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out_at', [$checkInDate, $checkOutDate])
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
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get the previous booking to find the user and previous room
            $previousBooking = Booking::where('order_id', $request->order_id)
                ->where('status', 1) // Assuming 1 is active status
                ->firstOrFail();

            // Get user who made the booking
            $user = $previousBooking->transaction->user;
            // Get room details
            $previousRoom = Room::find($previousBooking->room_id);
            $newRoom = Room::find($request->new_room);

            // Create the new booking (transfer)
            $booking = Booking::create([
                'property_id' => $request->current_property_id,
                'order_id' => $request->order_id,
                'room_id' => $request->new_room,
                'user_id' => $previousBooking->user_id, // Keep same user
                'check_in_at' => Carbon::parse($request->check_in),
                'check_out_at' => $request->check_out ? Carbon::parse($request->check_out) : null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'status' => 2, // Assuming 2 is transferred status
                'reason' => $request->reason ?? null,
                'description' => $request->notes,
            ]);

            // Mark previous booking as inactive or transferred
            $previousBooking->status = 3; // Or whatever status indicates it's no longer active
            $previousBooking->save();

            // Prepare transfer details for notification
            $transferDetails = [
                'guest_name' => $user->username,
                'order_id' => $request->order_id,
                'previous_room' => $previousRoom->name . ' (' . $previousRoom->type . ')',
                'new_room' => $newRoom->name . ' (' . $newRoom->type . ')',
                'reason' => $request->reason ?? 'Not specified',
                'transfer_date' => now()->format('Y-m-d H:i'),
                'check_in' => Carbon::parse($request->check_in)->format('Y-m-d H:i'),
                'check_out' => $request->check_out ? Carbon::parse($request->check_out)->format('Y-m-d H:i') : 'Not specified',
            ];

            // Send notification to user
            if ($user && $user->email) {
                Notification::send($user, new RoomTransferNotification($transferDetails));
            }

            return redirect()->route('changerooom.index')->with('success', 'Kamar berhasil dipindahkan dan notifikasi telah dikirim!');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }
}
