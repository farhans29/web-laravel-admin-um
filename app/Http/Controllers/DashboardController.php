<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Property;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getPropertyRoomReportData($propertyId = null)
    {
        try {
            // Jika tidak ada propertyId, ambil property pertama atau semua property
            if ($propertyId) {
                $property = Property::findOrFail($propertyId);
                $properties = collect([$property]);
            } else {
                $properties = Property::where('status', 1)->get();
            }

            $report = [];

            foreach ($properties as $property) {
                // Total kamar di property ini
                $totalRooms = Room::where('property_id', $property->idrec)
                    ->where('status', 1)
                    ->count();

                // Kamar yang sedang dipesan berdasarkan transactions
                $currentDate = now()->toDateString();

                $bookedRooms = Transaction::where('property_id', $property->idrec)
                    ->where('transaction_status', 'paid')
                    ->whereDate('check_in', '<=', $currentDate)
                    ->whereDate('check_out', '>=', $currentDate)
                    ->whereHas('booking', function ($q) {
                        $q->whereNull('check_out_at');
                    })
                    ->count();

                // Kamar tersedia
                $availableRooms = $totalRooms - $bookedRooms;

                // Data durasi sewa
                $bookingDurations = $this->getBookingDurations($property->idrec);

                // Data penjualan bulan ini
                $monthlySales = $this->getMonthlySales($property->idrec);

                // Breakdown tipe kamar
                $roomTypesBreakdown = $this->getRoomTypesBreakdown($property->idrec);

                $report[$property->idrec] = [
                    'property' => [
                        'id' => $property->idrec,
                        'name' => $property->name,
                        'initial' => $property->initial,
                    ],
                    'room_stats' => [
                        'total_rooms' => $totalRooms,
                        'available_rooms' => $availableRooms,
                        'booked_rooms' => $bookedRooms,
                        'occupancy_rate' => $totalRooms > 0 ? round(($bookedRooms / $totalRooms) * 100, 2) : 0,
                    ],
                    'booking_durations' => $bookingDurations,
                    'monthly_sales' => $monthlySales,
                    'room_types_breakdown' => $roomTypesBreakdown,
                ];
            }

            return $propertyId ? $report[$propertyId] : $report;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getBookingDurations($propertyId)
    {
        // Durasi statistik dari transactions yang sudah completed (check-out)
        $durations = Transaction::where('property_id', $propertyId)
            ->where('transaction_status', 'paid')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->whereHas('booking', function ($q) {
                $q->whereNotNull('check_out_at');
            })
            ->select(
                DB::raw('AVG(DATEDIFF(check_out, check_in)) as avg_duration'),
                DB::raw('MIN(DATEDIFF(check_out, check_in)) as min_duration'),
                DB::raw('MAX(DATEDIFF(check_out, check_in)) as max_duration'),
                DB::raw('COUNT(*) as total_bookings')
            )
            ->first();

        // Breakdown by duration ranges
        $durationRanges = Transaction::where('property_id', $propertyId)
            ->where('transaction_status', 'paid')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->whereHas('booking', function ($q) {
                $q->whereNotNull('check_out_at');
            })
            ->select(
                DB::raw('CASE 
                    WHEN DATEDIFF(check_out, check_in) = 1 THEN "1 Hari"
                    WHEN DATEDIFF(check_out, check_in) BETWEEN 2 AND 3 THEN "2-3 Hari"
                    WHEN DATEDIFF(check_out, check_in) BETWEEN 4 AND 7 THEN "4-7 Hari"
                    WHEN DATEDIFF(check_out, check_in) BETWEEN 8 AND 30 THEN "1-4 Minggu"
                    ELSE "Lebih dari 1 Bulan"
                END as duration_range'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('duration_range')
            ->orderBy(DB::raw('MIN(DATEDIFF(check_out, check_in))'))
            ->get();

        return [
            'average_duration' => round($durations->avg_duration ?? 0, 1),
            'min_duration' => $durations->min_duration ?? 0,
            'max_duration' => $durations->max_duration ?? 0,
            'total_bookings' => $durations->total_bookings ?? 0,
            'duration_ranges' => $durationRanges
        ];
    }

    private function getMonthlySales($propertyId)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $sales = Transaction::where('property_id', $propertyId)
            ->where('transaction_status', 'paid')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->select(
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(grandtotal_price) as total_revenue')
            )
            ->first();

        return [
            'total_bookings' => $sales->total_bookings ?? 0,
            'total_revenue' => $sales->total_revenue ?? 0
        ];
    }

    private function getRoomTypesBreakdown($propertyId)
    {
        // Subquery untuk kamar yang sedang dipesan berdasarkan transactions
        $bookedRoomsSubquery = Transaction::where('property_id', $propertyId)
            ->where('transaction_status', 'paid')
            ->whereDate('check_in', '<=', now()->toDateString())
            ->whereDate('check_out', '>=', now()->toDateString())
            ->whereHas('booking', function ($q) {
                $q->whereNull('check_out_at');
            })
            ->select('room_id')
            ->pluck('room_id');

        return Room::where('property_id', $propertyId)
            ->where('status', 1)
            ->select(
                'type',
                DB::raw('COUNT(*) as total_rooms'),
                DB::raw('SUM(CASE WHEN idrec NOT IN (' . ($bookedRoomsSubquery->count() > 0 ? $bookedRoomsSubquery->implode(',') : '0') . ') THEN 1 ELSE 0 END) as available_rooms')
            )
            ->groupBy('type')
            ->get();
    }

    private function getCurrentOccupancyData($propertyId = null)
    {
        $query = Transaction::where('transaction_status', 'paid')
            ->whereDate('check_in', '<=', now()->toDateString())
            ->whereDate('check_out', '>=', now()->toDateString())
            ->whereHas('booking', function ($q) {
                $q->whereNull('check_out_at');
            });

        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        return [
            'current_guests' => $query->count(),
            'total_revenue_today' => Transaction::where('transaction_status', 'paid')
                ->whereDate('paid_at', now()->toDateString())
                ->when($propertyId, function ($q) use ($propertyId) {
                    $q->where('property_id', $propertyId);
                })
                ->sum('grandtotal_price')
        ];
    }

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
                    ->whereDate('check_out', now()->toDateString());
            })
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
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

        // Sales Report Data (Last 30 days)
        $salesReport = $this->getSalesReport();

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

        // Get room reports - PERBAIKAN DI SINI
        $roomReports = $this->getPropertyRoomReportData(); // Langsung panggil method data, bukan API

        $showActions = true;

        return view('pages/dashboard/dashboard', compact(
            'dataFeed',
            'checkIns',
            'checkOuts',
            'roomAvailability',
            'stats',
            'salesReport',
            'showActions',
            'roomReports'
        ));
    }

    private function getSalesReport()
    {
        $startDate = now()->subDays(30);
        $endDate = now();

        $salesData = Transaction::where('transaction_status', 'paid')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(grandtotal_price) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $salesData->pluck('date')->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('M d');
            }),
            'bookings' => $salesData->pluck('total_bookings'),
            'revenue' => $salesData->pluck('total_revenue')
        ];
    }

    public function getPropertyRoomReport($propertyId = null)
    {
        $data = $this->getPropertyRoomReportData($propertyId);

        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Room report retrieved successfully'
        ]);
    }

    public function analytics()
    {
        return view('pages/dashboard/analytics');
    }

    public function fintech()
    {
        return view('pages/dashboard/fintech');
    }

    public function progress_index()
    {
        return view('pages/progress_page/index');
    }
}
