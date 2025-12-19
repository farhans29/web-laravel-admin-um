<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $user = Auth::user();

            // Filter properties based on user access
            if ($propertyId) {
                $property = Property::findOrFail($propertyId);

                // Non-super admin: verify they have access to this property
                if (!$user->isSuperAdmin() && $user->property_id != $propertyId) {
                    $property = Property::where('idrec', $user->property_id)->firstOrFail();
                }

                $properties = collect([$property]);
            } else {
                // Super admin: get all properties, Non-super admin: only their property
                if ($user->isSuperAdmin()) {
                    $properties = Property::where('status', 1)->get();
                } else {
                    $properties = Property::where('status', 1)
                        ->where('idrec', $user->property_id)
                        ->get();
                }
            }

            $report = [];

            foreach ($properties as $property) {
                // Total kamar di property ini
                $totalRooms = Room::where('property_id', $property->idrec)
                    ->where('status', 1)
                    ->count();

                // Kamar yang sedang dipesan berdasarkan bookings (must be checked in)
                $currentDate = now()->toDateString();

                $bookedRooms = Booking::where('property_id', $property->idrec)
                    ->whereHas('transaction', function ($q) use ($currentDate) {
                        $q->where('transaction_status', 'paid')
                            ->whereDate('check_in', '<=', $currentDate)
                            ->whereDate('check_out', '>=', $currentDate);
                    })
                    ->whereNotNull('check_in_at') // Must be physically checked in
                    ->whereNull('check_out_at')   // Not yet checked out
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
                        'name' => $property->property_name ?? $property->name ?? 'N/A',
                        'initial' => $property->initial ?? '',
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
        // Use +1 to include both check-in and check-out days (inclusive counting)
        $durations = Transaction::where('property_id', $propertyId)
            ->where('transaction_status', 'paid')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->whereHas('booking', function ($q) {
                $q->whereNotNull('check_out_at');
            })
            ->select(
                DB::raw('AVG(DATEDIFF(check_out, check_in) + 1) as avg_duration'),
                DB::raw('MIN(DATEDIFF(check_out, check_in) + 1) as min_duration'),
                DB::raw('MAX(DATEDIFF(check_out, check_in) + 1) as max_duration'),
                DB::raw('COUNT(*) as total_bookings')
            )
            ->first();

        // Breakdown by duration ranges (with inclusive day counting)
        $durationRanges = Transaction::where('property_id', $propertyId)
            ->where('transaction_status', 'paid')
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->whereHas('booking', function ($q) {
                $q->whereNotNull('check_out_at');
            })
            ->select(
                DB::raw('CASE
                    WHEN DATEDIFF(check_out, check_in) + 1 = 1 THEN "1 Hari"
                    WHEN DATEDIFF(check_out, check_in) + 1 BETWEEN 2 AND 3 THEN "2-3 Hari"
                    WHEN DATEDIFF(check_out, check_in) + 1 BETWEEN 4 AND 7 THEN "4-7 Hari"
                    WHEN DATEDIFF(check_out, check_in) + 1 BETWEEN 8 AND 30 THEN "1-4 Minggu"
                    ELSE "Lebih dari 1 Bulan"
                END as duration_range'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('duration_range')
            ->orderBy(DB::raw('MIN(DATEDIFF(check_out, check_in) + 1)'))
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
        $currentDate = now()->toDateString();

        // Get booked room IDs (only those that are physically checked in)
        $bookedRoomIds = Booking::where('property_id', $propertyId)
            ->whereHas('transaction', function ($q) use ($currentDate) {
                $q->where('transaction_status', 'paid')
                    ->whereDate('check_in', '<=', $currentDate)
                    ->whereDate('check_out', '>=', $currentDate);
            })
            ->whereNotNull('check_in_at') // Must be physically checked in
            ->whereNull('check_out_at')   // Not yet checked out
            ->pluck('room_id')
            ->toArray();

        // Get all rooms grouped by type with availability count
        $rooms = Room::where('property_id', $propertyId)
            ->where('status', 1)
            ->get()
            ->groupBy('type')
            ->map(function ($roomsGroup) use ($bookedRoomIds) {
                $totalRooms = $roomsGroup->count();
                $bookedCount = $roomsGroup->whereIn('idrec', $bookedRoomIds)->count();
                $availableRooms = $totalRooms - $bookedCount;

                return (object) [
                    'type' => $roomsGroup->first()->type,
                    'total_rooms' => $totalRooms,
                    'available_rooms' => $availableRooms,
                ];
            })
            ->values();

        return $rooms;
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

    private function getOccupiedRoomsDetails($propertyId = null)
    {
        return Booking::with(['room', 'property', 'transaction', 'user'])
            ->when($propertyId, function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            })
            ->whereHas('transaction', function ($q) {
                $q->where('transaction_status', 'paid')
                    ->whereDate('check_in', '<=', now()->toDateString())
                    ->whereDate('check_out', '>=', now()->toDateString());
            })
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->get()
            ->map(function ($booking) {
                $checkIn = Carbon::parse($booking->transaction->check_in)->startOfDay();
                $checkOut = Carbon::parse($booking->transaction->check_out)->startOfDay();
                $today = Carbon::now()->startOfDay();

                // Calculate days correctly (inclusive)
                $daysStayed = $checkIn->diffInDays($today) + 1; // +1 to include check-in day
                $totalDays = $checkIn->diffInDays($checkOut) + 1; // +1 to include both check-in and check-out days
                $daysRemaining = max(0, $today->diffInDays($checkOut)); // Ensure non-negative

                // Adjust for overdue
                if ($checkOut->isPast() && !$checkOut->isToday()) {
                    $daysRemaining = 0;
                    $daysStayed = $totalDays + $checkOut->diffInDays($today);
                }

                return [
                    'booking_id' => $booking->idrec,
                    'guest_name' => $booking->user_name ?? $booking->transaction->user_name ?? 'N/A',
                    'room_name' => $booking->room->name ?? 'N/A',
                    'room_type' => $booking->room->type ?? 'N/A',
                    'property_name' => $booking->property->property_name ?? $booking->property->name ?? 'N/A',
                    'check_in_date' => $checkIn->format('d M Y'),
                    'check_out_date' => $checkOut->format('d M Y'),
                    'days_stayed' => $daysStayed,
                    'total_days' => $totalDays,
                    'days_remaining' => $daysRemaining,
                    'progress_percentage' => $totalDays > 0 ? min(100, round(($daysStayed / $totalDays) * 100)) : 0,
                    'total_price' => $booking->transaction->grandtotal_price ?? 0,
                    'daily_rate' => $totalDays > 0 ? round(($booking->transaction->grandtotal_price ?? 0) / $totalDays, 2) : 0,
                    'is_checkout_today' => $checkOut->isToday(),
                    'is_overdue' => $checkOut->isPast() && !$checkOut->isToday(),
                ];
            });
    }

    private function getOccupancyHistory($days = 30, $propertyId = null)
    {
        $history = [];
        $totalRooms = Room::where('status', 1)
            ->when($propertyId, function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            })
            ->count();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();

            // Count rooms occupied on this specific date
            // Only count rooms where guest has actually checked in
            $occupied = Booking::when($propertyId, function ($q) use ($propertyId) {
                    $q->where('property_id', $propertyId);
                })
                ->whereHas('transaction', function ($q) use ($date) {
                    $q->where('transaction_status', 'paid')
                        ->whereDate('check_in', '<=', $date)
                        ->whereDate('check_out', '>=', $date);
                })
                ->whereNotNull('check_in_at') // Must be physically checked in
                ->where(function ($q) use ($date) {
                    // And either not checked out yet, or checked out after this date
                    $q->whereNull('check_out_at')
                        ->orWhereDate('check_out_at', '>', $date);
                })
                ->count();

            $occupancyRate = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100, 2) : 0;

            $history[] = [
                'date' => Carbon::parse($date)->format('M d'),
                'occupied' => $occupied,
                'occupancy_rate' => $occupancyRate,
            ];
        }

        return $history;
    }

    private function getRentalDurationTrends($propertyId = null)
    {
        // Compare current month with previous month
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $previousDate = $now->copy()->subMonth();
        $previousMonth = $previousDate->month;
        $previousYear = $previousDate->year;

        // Current month data - only completed or ongoing bookings
        $currentMonthData = Transaction::where('transaction_status', 'paid')
            ->when($propertyId, function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            })
            ->whereYear('check_in', $currentYear)
            ->whereMonth('check_in', $currentMonth)
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->select(
                DB::raw('AVG(DATEDIFF(check_out, check_in) + 1) as avg_duration'), // +1 for inclusive count
                DB::raw('COUNT(*) as total_bookings')
            )
            ->first();

        // Previous month data
        $previousMonthData = Transaction::where('transaction_status', 'paid')
            ->when($propertyId, function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            })
            ->whereYear('check_in', $previousYear)
            ->whereMonth('check_in', $previousMonth)
            ->whereNotNull('check_in')
            ->whereNotNull('check_out')
            ->select(
                DB::raw('AVG(DATEDIFF(check_out, check_in) + 1) as avg_duration'), // +1 for inclusive count
                DB::raw('COUNT(*) as total_bookings')
            )
            ->first();

        $currentAvg = round($currentMonthData->avg_duration ?? 0, 1);
        $previousAvg = round($previousMonthData->avg_duration ?? 0, 1);

        // Calculate trend
        if ($previousAvg > 0) {
            $trend = round((($currentAvg - $previousAvg) / $previousAvg) * 100, 1);
        } elseif ($currentAvg > 0) {
            $trend = 100; // 100% increase from 0
        } else {
            $trend = 0; // Both are 0
        }

        return [
            'current_month_avg' => $currentAvg,
            'previous_month_avg' => $previousAvg,
            'trend_percentage' => abs($trend),
            'trend_direction' => $trend > 0 ? 'up' : ($trend < 0 ? 'down' : 'stable'),
            'current_bookings' => $currentMonthData->total_bookings ?? 0,
            'previous_bookings' => $previousMonthData->total_bookings ?? 0,
        ];
    }

    private function getRevenuePerOccupiedRoom($propertyId = null)
    {
        $today = now()->toDateString();

        // Get bookings that are currently occupied (checked in but not checked out)
        $occupiedBookings = Booking::with('transaction')
            ->when($propertyId, function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            })
            ->whereHas('transaction', function ($q) use ($today) {
                $q->where('transaction_status', 'paid')
                    ->whereDate('check_in', '<=', $today)
                    ->whereDate('check_out', '>=', $today);
            })
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->get();

        $occupiedRooms = $occupiedBookings->count();
        $totalRevenue = $occupiedBookings->sum(function ($booking) {
            return $booking->transaction->grandtotal_price ?? 0;
        });

        $averageRevenuePerRoom = $occupiedRooms > 0
            ? round($totalRevenue / $occupiedRooms, 2)
            : 0;

        return [
            'occupied_rooms' => $occupiedRooms,
            'total_revenue' => $totalRevenue,
            'average_per_room' => $averageRevenuePerRoom,
        ];
    }

    public function index()
    {
        $user = Auth::user();
        $userPropertyId = $user->isSuperAdmin() ? null : $user->property_id;

        $dataFeed = new DataFeed();

        // Get today's check-ins (paid bookings with today's check-in date, not checked in yet)
        $checkIns = Booking::with(['user', 'room', 'property', 'transaction'])
            ->when($userPropertyId, function ($q) use ($userPropertyId) {
                $q->where('property_id', $userPropertyId);
            })
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
            ->when($userPropertyId, function ($q) use ($userPropertyId) {
                $q->where('property_id', $userPropertyId);
            })
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
            'upcoming' => Booking::when($userPropertyId, function ($q) use ($userPropertyId) {
                    $q->where('property_id', $userPropertyId);
                })
                ->whereHas('transaction', fn($q) =>
                    $q->where('transaction_status', 'paid')
                        ->whereDate('check_in', '>=', now()))
                ->whereNull('check_in_at')
                ->whereNull('check_out_at')
                ->count(),

            'today' => Booking::when($userPropertyId, function ($q) use ($userPropertyId) {
                    $q->where('property_id', $userPropertyId);
                })
                ->whereHas('transaction', fn($q) => $q->where('transaction_status', 'paid'))
                ->whereNull('check_in_at')
                ->whereNull('check_out_at')
                ->whereHas('transaction', fn($q) => $q->whereDate('check_in', now()->toDateString()))
                ->count(),

            'checkin' => Booking::when($userPropertyId, function ($q) use ($userPropertyId) {
                    $q->where('property_id', $userPropertyId);
                })
                ->whereHas('transaction', fn($q) => $q->where('transaction_status', 'paid'))
                ->whereNotNull('check_in_at')
                ->whereNull('check_out_at')
                ->count(),

            'checkout' => Booking::when($userPropertyId, function ($q) use ($userPropertyId) {
                    $q->where('property_id', $userPropertyId);
                })
                ->whereHas('transaction', fn($q) => $q->where('transaction_status', 'paid'))
                ->whereNotNull('check_in_at')
                ->whereNull('check_out_at')
                ->whereHas('transaction', fn($q) => $q->whereDate('check_out', now()->toDateString()))
                ->count(),
        ];

        // Sales Report Data (Last 30 days)
        $salesReport = $this->getSalesReport($userPropertyId);

        // Get room availability for the next 7 days
        $startDate = now();
        $endDate = now()->addDays(7);

        // Get all room types with their total count
        $roomTypes = Room::select('type', DB::raw('count(*) as total'))
            ->when($userPropertyId, function ($q) use ($userPropertyId) {
                $q->where('property_id', $userPropertyId);
            })
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
            ->when($userPropertyId, function ($q) use ($userPropertyId) {
                $q->where('t_booking.property_id', $userPropertyId);
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

        // Get new dashboard data
        $occupiedRooms = $this->getOccupiedRoomsDetails($userPropertyId);
        $occupancyHistory = $this->getOccupancyHistory(30, $userPropertyId);
        $rentalDurationTrends = $this->getRentalDurationTrends($userPropertyId);
        $revenuePerRoom = $this->getRevenuePerOccupiedRoom($userPropertyId);

        $showActions = true;

        return view('pages/dashboard/dashboard', compact(
            'dataFeed',
            'checkIns',
            'checkOuts',
            'roomAvailability',
            'stats',
            'salesReport',
            'showActions',
            'roomReports',
            'occupiedRooms',
            'occupancyHistory',
            'rentalDurationTrends',
            'revenuePerRoom'
        ));
    }

    private function getSalesReport($propertyId = null)
    {
        $startDate = now()->subDays(30);
        $endDate = now();

        $salesData = Transaction::where('transaction_status', 'paid')
            ->when($propertyId, function ($q) use ($propertyId) {
                $q->where('property_id', $propertyId);
            })
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
        $user = Auth::user();

        // Non-super admin: enforce their property_id
        if (!$user->isSuperAdmin()) {
            $propertyId = $user->property_id;
        }

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
