<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

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

        $showActions = true;

        return view('pages/dashboard/dashboard', compact(
            'dataFeed',
            'checkIns',
            'checkOuts',
            'roomAvailability',
            'stats',
            'showActions'
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

        // Coba buat storage link secara otomatis
        $storageLinkResult = $this->createStorageLink();

        // Ambil beberapa gambar untuk testing
        $images = PropertyImage::with('property')
            ->limit(10)
            ->get();

        return view('pages/progress_page/index', compact('images', 'storageLinkResult'));
    }

   public function testImages()
    {
        // Coba buat storage link secara otomatis
        $storageLinkResult = $this->createStorageLink();
        
        // Ambil beberapa gambar untuk testing
        $images = PropertyImage::with('property')
            ->limit(10)
            ->get();
            
        return view('pages/progress_page/index', compact('images', 'storageLinkResult'));
    }

    public function testSingleImage($id)
    {
        $image = PropertyImage::with('property')->findOrFail($id);
        return response()->json([
            'success' => true,
            'image' => $image,
            'image_url' => $image->image_url,
            'thumbnail_url' => $image->thumbnail_url,
            'storage_exists' => Storage::exists($image->image)
        ]);
    }

    /**
     * Membuat storage link secara otomatis
     */
    private function createStorageLink()
    {
        try {
            // Cek apakah storage link sudah ada
            $publicPath = public_path('storage');
            $storagePath = storage_path('app/public');
            
            $result = [
                'success' => false,
                'message' => '',
                'link_exists' => is_link($publicPath),
                'target_exists' => file_exists($storagePath)
            ];

            // Jika symbolic link belum ada, buat
            if (!is_link($publicPath)) {
                Artisan::call('storage:link');
                $result['success'] = true;
                $result['message'] = 'Storage link created successfully!';
                $result['link_exists'] = true;
            } else {
                $result['message'] = 'Storage link already exists.';
                $result['success'] = true;
            }

            return $result;

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating storage link: ' . $e->getMessage(),
                'link_exists' => false,
                'target_exists' => file_exists(storage_path('app/public'))
            ];
        }
    }

    /**
     * Force create storage link
     */
    public function forceStorageLink()
    {
        try {
            $publicPath = public_path('storage');
            
            // Hapus link yang sudah ada jika ada
            if (is_link($publicPath)) {
                unlink($publicPath);
            }
            
            // Buat link baru
            Artisan::call('storage:link');
            
            return response()->json([
                'success' => true,
                'message' => 'Storage link created successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check storage status
     */
    public function checkStorage()
    {
        $publicPath = public_path('storage');
        $storagePath = storage_path('app/public');
        
        return response()->json([
            'storage_link_exists' => is_link($publicPath),
            'storage_path_exists' => file_exists($storagePath),
            'public_storage_writable' => is_writable(public_path()),
            'storage_app_public_writable' => is_writable($storagePath),
            'link_target' => is_link($publicPath) ? readlink($publicPath) : null,
            'expected_target' => $storagePath
        ]);
    }
}
