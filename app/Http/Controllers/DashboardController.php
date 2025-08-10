<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $dataFeed = new DataFeed();

        // Get today's check-ins (paid bookings with today's check-in date, not checked in yet)
        $checkIns = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->where('transaction_status', 'paid')
                    ->whereDate('check_in', now()->toDateString());
            })
            ->whereNull('check_in_at')
            ->whereNull('check_out_at')
            ->orderBy(
                Transaction::select('check_in')
                    ->whereColumn('t_transactions.order_id', 't_booking.order_id')
                    ->limit(1)
            )
            ->limit(4)
            ->get();


        // Get today's check-outs (paid bookings with today's check-out date, checked in but not checked out)
        $checkOuts = Booking::with(['user', 'room', 'property', 'transaction'])
            ->whereHas('transaction', function ($q) {
                $q->where('transaction_status', 'paid')
                    ->whereDate('check_out', now()->toDateString()); // dari tabel transactions
            })
            ->whereNotNull('check_in_at') // sudah check-in
            ->whereNull('check_out_at') // belum check-out
            ->orderBy(
                Transaction::select('check_out')
                    ->whereColumn('t_transactions.order_id', 't_booking.order_id')
                    ->limit(1)
            )
            ->limit(4)
            ->get();

        $stats = [
            'upcoming' => Booking::whereHas('transaction', fn($q) =>
            $q->where('transaction_status', 'paid')
                ->whereDate('check_in', '>=', now()))
                ->whereNull('check_in_at')
                ->whereNull('check_out_at')
                ->count(),

            'today' => Booking::whereHas('transaction', fn($q) => $q->where('transaction_status', 'paid'))
                ->whereNull('check_in_at')
                ->whereNull('check_out_at')
                ->whereHas('transaction', fn($q) => $q->whereDate('check_in', now()->toDateString()))
                ->count(),

            'checkin' => Booking::whereHas('transaction', fn($q) => $q->where('transaction_status', 'paid'))
                ->whereNotNull('check_in_at')
                ->whereNull('check_out_at')
                ->count(),

            'checkout' => Booking::whereHas('transaction', fn($q) => $q->where('transaction_status', 'paid'))
                ->whereNotNull('check_in_at')
                ->whereNull('check_out_at')
                ->whereHas('transaction', fn($q) => $q->whereDate('check_out', now()->toDateString()))
                ->count(),
        ];

        // Get room availability for the next 7 days
        $startDate = now();
        $endDate = now()->addDays(7);

        // Get all room types with their total count
        $roomTypes = Room::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        // Get unavailable rooms (booked and not checked out yet)
        $unavailableRooms = Booking::where(function ($query) use ($startDate, $endDate) {
            $query->where('check_in_at', '<=', $endDate)
                ->where('check_out_at', '>=', $startDate)
                ->orWhere(function ($query) use ($endDate) {
                    $query->where('check_in_at', '<=', $endDate)
                        ->whereNull('check_out_at');
                });
        })
            ->select('room_id', 'type')
            ->join('m_rooms', 't_booking.room_id', '=', 'm_rooms.idrec')
            ->groupBy('room_id', 'type')
            ->get()
            ->groupBy('type');

        // Calculate availability for each room type
        $roomAvailability = [];
        foreach ($roomTypes as $roomType) {
            $unavailableCount = isset($unavailableRooms[$roomType->type]) ?
                count($unavailableRooms[$roomType->type]) : 0;

            $available = $roomType->total - $unavailableCount;
            $percentage = $roomType->total > 0 ? round(($available / $roomType->total) * 100) : 0;

            $roomAvailability[] = [
                'type' => $roomType->type,
                'total' => $roomType->total,
                'available' => $available,
                'percentage' => $percentage,
                'is_popular' => $roomType->type === 'Deluxe Suite',
                'is_luxury' => $roomType->type === 'Presidential Suite'
            ];
        }

        return view('pages/dashboard/dashboard', compact(
            'dataFeed',
            'checkIns',
            'checkOuts',
            'roomAvailability',
            'stats'
        ));
    }



    public function analytics()
    {
        return view('pages/dashboard/analytics');
    }

    /**
     * Displays the fintech screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fintech()
    {
        return view('pages/dashboard/fintech');
    }

    public function progress_index()
    {
        return view('pages/progress_page/index');
    }
}
