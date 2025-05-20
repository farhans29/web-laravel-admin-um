<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

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
        $currentDate = now()->format('Y-m-d');
        $propertyName = $request->input('property');

        $query = Room::whereDoesntHave('bookings', function ($query) use ($currentDate) {
            $query->where('status', 1)
                ->whereDate('check_in_at', '<=', $currentDate)
                ->whereDate('check_out_at', '>=', $currentDate);
        });

        if ($propertyName) {
            $query->whereHas('property', function ($q) use ($propertyName) {
                $q->where('name', $propertyName);
            });
        }

        return $query->get();
    }
}
