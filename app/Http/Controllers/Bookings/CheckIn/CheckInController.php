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
        $status = $request->input('status'); // Ini akan null jika tidak dipilih
        $checkInDate = $request->input('check_in_date');
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        // Start building the query
        $bookings = Booking::with(['transaction', 'property', 'room'])
            ->whereHas('transaction', function ($q) {
                $q->where('status', 1);
            })
            ->get();
        dd($bookings);

        // Apply filters hanya jika ada parameter
        if ($propertyType) {
            $query->whereHas('property', function ($q) use ($propertyType) {
                $q->where('type', $propertyType);
            });
        }

        if ($status) { // Hanya filter status jika dipilih
            switch ($status) {
                case 'active':
                    $query->where('check_in_at', '<=', now())
                        ->where('check_out_at', '>=', now());
                    break;
                case 'upcoming':
                    $query->where('check_in_at', '>', now());
                    break;
                case 'completed':
                    $query->where('check_out_at', '<', now());
                    break;
            }
        }

        if ($checkInDate) {
            $query->whereDate('check_in_at', $checkInDate);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                    ->orWhereHas('transaction', function ($q) use ($search) {
                        $q->where('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%");
                    });
            });
        }

        // Order and paginate results
        $bookings = $query->orderBy('check_in_at', 'desc')
            ->paginate($perPage);

        return view('pages.bookings.checkin.index', compact('bookings'));
    }
}
