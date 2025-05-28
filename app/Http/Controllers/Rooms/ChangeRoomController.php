<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;

class ChangeRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $currentDate = now()->format('Y-m-d');
        $propertyId = $request->input('property_id');

        $bookings = Booking::with(['user', 'room', 'property', 'transaction'])
            ->where('status', 1)
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('username', 'like', '%' . $search . '%');
                });
            })
            ->paginate(3);

        // Get available rooms (not booked for current date)
        $availableRooms = Room::whereDoesntHave('bookings', function ($query) use ($currentDate) {
            $query->where('status', 1)
                ->whereDate('check_in_at', '<=', $currentDate)
                ->whereDate('check_out_at', '>=', $currentDate);
        })
            ->when($propertyId, function ($query, $propertyId) {
                return $query->where('property_id', $propertyId);
            })
            ->get();

        return view('pages.rooms.changerooms.index', compact('bookings', 'search', 'availableRooms'));
    }

    public function getAvailableRooms(Request $request)
    {
        $propertyName = $request->input('property');
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');
        $excludeRoom = $request->input('exclude_room');

        // Convert dates to proper format
        $checkInDate = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);

        // Get all rooms in the property
        $rooms = Room::where('property_name', $propertyName)
            ->where('status', 'active')
            ->when($excludeRoom, function ($query) use ($excludeRoom) {
                return $query->where('name', '!=', $excludeRoom);
            })
            ->get();

        // Filter rooms that are available during the requested period
        $availableRooms = $rooms->filter(function ($room) use ($checkInDate, $checkOutDate) {
            // Check if the room has any bookings that overlap with the requested dates
            $conflictingBookings = Booking::where('room_id', $room->idrec)
                ->where(function ($query) use ($checkInDate, $checkOutDate) {
                    $query->whereBetween('check_in_at', [$checkInDate, $checkOutDate])
                        ->orWhereBetween('check_out_at', [$checkInDate, $checkOutDate])
                        ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                            $query->where('check_in_at', '<=', $checkInDate)
                                ->where('check_out_at', '>=', $checkOutDate);
                        });
                })
                ->exists();

            return !$conflictingBookings;
        });

        return response()->json($availableRooms->values());
    }
}
