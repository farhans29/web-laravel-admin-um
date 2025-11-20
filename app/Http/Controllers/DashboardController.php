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

        $showActions = true;

        return view('pages/dashboard/dashboard', compact(
            'dataFeed',
            'checkIns',
            'checkOuts',
            'roomAvailability',
            'stats',
            'salesReport',
            'showActions'
        ));
    }


    private function getSalesReport()
    {
        $startDate = now()->subDays(30);
        $endDate = now();

        // Total transactions and gross amount
        $totalData = Transaction::where('transaction_status', 'paid')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select(
                DB::raw('COUNT(*) as total_transactions'),
                DB::raw('SUM(grandtotal_price) as gross_amount'),
                DB::raw('SUM(admin_fees) as total_admin_fees'),
                DB::raw('AVG(grandtotal_price) as average_transaction')
            )
            ->first();

        // Daily sales data for chart
        $dailySales = Transaction::where('transaction_status', 'paid')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(grandtotal_price) as daily_revenue'),
                DB::raw('SUM(admin_fees) as daily_admin_fees')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Weekly sales data
        $weeklySales = Transaction::where('transaction_status', 'paid')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select(
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('WEEK(transaction_date) as week'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(grandtotal_price) as weekly_revenue')
            )
            ->groupBy('year', 'week')
            ->orderBy('year')
            ->orderBy('week')
            ->get();

        // Revenue by room type
        $revenueByRoom = Transaction::where('transaction_status', 'paid')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->join('m_rooms', 't_transactions.room_id', '=', 'm_rooms.idrec')
            ->select(
                'm_rooms.type as room_type',
                DB::raw('COUNT(*) as booking_count'),
                DB::raw('SUM(t_transactions.grandtotal_price) as total_revenue')
            )
            ->groupBy('m_rooms.type')
            ->orderByDesc('total_revenue')
            ->get();

        // Booking type distribution
        $bookingTypeStats = Transaction::where('transaction_status', 'paid')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select(
                'booking_type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(grandtotal_price) as revenue')
            )
            ->groupBy('booking_type')
            ->get();

        // Monthly trend data
        $monthlyTrend = Transaction::where('transaction_status', 'paid')
            ->whereBetween('transaction_date', [now()->subMonths(6), now()])
            ->select(
                DB::raw('YEAR(transaction_date) as year'),
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(grandtotal_price) as monthly_revenue')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return [
            'period' => [
                'start' => $startDate->format('d M Y'),
                'end' => $endDate->format('d M Y')
            ],
            'summary' => [
                'total_transactions' => $totalData->total_transactions ?? 0,
                'gross_amount' => $totalData->gross_amount ?? 0,
                'total_admin_fees' => $totalData->total_admin_fees ?? 0,
                'average_transaction' => $totalData->average_transaction ?? 0,
                'net_amount' => ($totalData->gross_amount ?? 0) - ($totalData->total_admin_fees ?? 0)
            ],
            'daily_sales' => $dailySales,
            'weekly_sales' => $weeklySales,
            'monthly_trend' => $monthlyTrend,
            'revenue_by_room' => $revenueByRoom,
            'booking_type_stats' => $bookingTypeStats,
            'chart_data' => [
                'daily_labels' => $dailySales->pluck('date')->map(function ($date) {
                    return \Carbon\Carbon::parse($date)->format('d M');
                })->toArray(),
                'daily_revenue' => $dailySales->pluck('daily_revenue')->toArray(),
                'daily_transactions' => $dailySales->pluck('transaction_count')->toArray(),
                'room_types' => $revenueByRoom->pluck('room_type')->toArray(),
                'room_revenues' => $revenueByRoom->pluck('total_revenue')->toArray(),
                'booking_types' => $bookingTypeStats->pluck('booking_type')->toArray(),
                'booking_counts' => $bookingTypeStats->pluck('count')->toArray(),
                'monthly_labels' => $monthlyTrend->map(function ($item) {
                    return \Carbon\Carbon::create()->month($item->month)->format('M Y');
                })->toArray(),
                'monthly_revenue' => $monthlyTrend->pluck('monthly_revenue')->toArray(),
            ]
        ];
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
