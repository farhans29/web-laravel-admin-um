<?php

namespace App\Http\Controllers\Bookings\CheckIn;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class CheckInController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters from the request
        $propertyType = $request->input('property_type');
        $status = $request->input('status');
        $checkInDate = $request->input('check_in_date');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        // Start building the query with eager loading
        $query = Booking::with(['transaction', 'property', 'room']);

        // Apply property type filter
        if ($propertyType) {
            $query->whereHas('property', function ($q) use ($propertyType) {
                $q->where('type', $propertyType);
            });
        }

        // Apply status filter
        if ($status) {
            switch ($status) {
                case 'active':
                    $query->whereNotNull('check_in_at')
                        ->whereNull('check_out_at');
                    break;
                case 'upcoming':
                    $query->whereNull('check_in_at')
                        ->whereNull('check_out_at');
                    break;
                case 'completed':
                    $query->whereNotNull('check_in_at')
                        ->whereNotNull('check_out_at');
                    break;
            }
        }

        // Apply check-in date filter
        if ($checkInDate) {
            $query->whereDate('check_in_at', $checkInDate);
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('transaction', function ($q) use ($search) {
                        $q->where('user_name', 'like', "%{$search}%")
                            ->orWhere('user_phone_number', 'like', "%{$search}%");
                    });
            });
        }

        // Order and paginate results
        $bookings = $query->orderBy('check_in_at', 'desc')
            ->paginate($perPage);

        return view('pages.bookings.checkin.index', compact('bookings'));
    }
}
