<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\MRoomImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManajementRoomsController extends Controller
{
    // public function index()
    // {
    //     $rooms = Room::where('status', '!=', '2')
    //         ->with('transactions', 'bookings', 'property', 'creator', 'roomImages')
    //         // ->where('property_id', Auth::user()->property_id)
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(5);
    //     // if (Auth::user()->property_id == 0) {
    //     //     $properties = Property::orderBy('name', 'asc')->get();
    //     // } else {
    //     //     $properties = Property::where('idrec', Auth::user()->property_id)->first();
    //     // }

    //     $properties = Property::orderBy('name', 'asc')->get();

    //     return view('pages.Properties.m-Rooms.index', compact('rooms', 'properties'));
    // }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Room::where('status', '!=', '2')
            ->with(['transactions', 'bookings', 'property', 'creator', 'roomImages'])
            ->orderBy('created_at', 'desc');

        // Filter by property
        if ($request->has('property_id') && $request->property_id != '') {
            $query->where('property_id', $request->property_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('no', 'like', '%' . $searchTerm . '%')
                    ->orWhere('name', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('property', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $rooms = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        $properties = Property::orderBy('name', 'asc')->get();

        // Jika request AJAX, kembalikan partial view
        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.Properties.m-Rooms.partials.room_table', [
                    'properties' => $properties,
                    'rooms' => $rooms,
                    'per_page' => $perPage,
                ])->render(),
                'pagination' => $rooms instanceof \Illuminate\Pagination\LengthAwarePaginator
                    ? $rooms->appends($request->input())->links()->toHtml()
                    : ''
            ]);
        }

        return view('pages.Properties.m-Rooms.index', [
            'rooms' => $rooms,
            'properties' => $properties,
            'per_page' => $perPage,
        ]);
    }


    public function store(Request $request)
    {        
        $validated = $request->validate([
            'property_id' => 'required|numeric|exists:m_properties,idrec',
            'room_no' => 'required|string|max:255',
            'room_name' => 'required|string|max:255',
            'room_size' => 'required|numeric|min:0',
            'room_bed' => 'required|string|in:Single,Double,King,Queen,Twin',
            'room_capacity' => 'required|numeric|min:1',
            'description_id' => 'required|string',
            'daily_price' => 'nullable|numeric|min:0',
            'monthly_price' => 'nullable|numeric|min:0',
            'facilities' => 'nullable|array',
            'room_images' => 'required|array|min:3|max:10',
            'room_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Get property data
        $property = Property::findOrFail($validated['property_id']);

        // Process facilities
        $allFacilities = [
            'wifi',
            'ac',
            'tv',
            'bathroom',
            'hot_water',
            'wardrobe',
            'desk',
            'refrigerator',
            'breakfast'
        ];

        $facilityData = [
            'features' => array_intersect($request->input('facilities', []), $allFacilities),
        ];

        // Generate ID and unique slug
        $idrec = Room::max('idrec') + 1;
        $tagShort = strtolower(substr($property->tags, 0, 3));
        $nameShort = strtolower(collect(explode(' ', $validated['room_name']))->map(fn($w) => substr($w, 0, 1))->implode(''));
        $slug = $tagShort . '_' . $nameShort . '_' . $idrec;

        // Encode images to base64
        $imageBase64Array = [];
        $imageCaptionArray = [];

        foreach ($request->file('room_images') as $file) {
            if (!$file->isValid()) {
                return back()->withErrors(['room_images' => 'Invalid file uploaded.']);
            }

            $fileContents = file_get_contents($file->getRealPath());
            $imageBase64Array[] = base64_encode($fileContents);
            $imageCaptionArray[] = $file->getClientOriginalName();
        }

        // Determine price period
        $periode = [
            'daily' => !empty($validated['daily_price']),
            'monthly' => !empty($validated['monthly_price'])
        ];        
        // Save to rooms table
        $room = new Room();
        $room->idrec = $idrec;
        $room->property_id = $validated['property_id'];
        $room->property_name = $property->name;
        $room->slug = $slug;
        $room->no = $validated['room_no'];
        $room->name = $validated['room_name'];
        $room->descriptions = $validated['description_id'];
        $room->size = $validated['room_size'];
        $room->bed_type = $validated['room_bed'];
        $room->capacity = $validated['room_capacity'];
        $room->periode = json_encode($periode);
        $room->type = $property->type;
        $room->level = 1;
        $room->facility = $facilityData['features'];
        $room->price = $validated['daily_price'] ?? $validated['monthly_price'] ?? 0;
        $room->discount_percent = 0;
        $room->price_original_daily = $validated['daily_price'] ?? 0;
        $room->price_original_monthly = $validated['monthly_price'] ?? 0;
        $room->created_by = Auth::id();
        $room->status = 1;
        $room->created_at = now();
        $room->save();

        // Save to room_images table
        foreach ($imageBase64Array as $index => $base64) {
            $image = new MRoomImage();
            $image->room_id = $idrec;
            $image->image = $base64;
            $image->caption = $imageCaptionArray[$index] ?? 'No caption';
            $image->created_by = Auth::user()->id;
            $image->created_at = now();
            $image->save();
        }

        // Generate daily prices if daily price is set
        if ($periode['daily']) {
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addYear();
            $dailyPrice = $validated['daily_price'];

            $priceInserts = [];
            while ($startDate->lessThan($endDate)) {
                $priceInserts[] = [
                    'room_id' => $idrec,
                    'date' => $startDate->format('Y-m-d'),
                    'price' => $dailyPrice,
                    'created_at' => now(),
                    'created_by' => Auth::id(),
                    'status' => '1',
                ];

                if (count($priceInserts) >= 1000) {
                    RoomPrices::insert($priceInserts);
                    $priceInserts = [];
                }

                $startDate->addDay();
            }

            if (!empty($priceInserts)) {
                RoomPrices::insert($priceInserts);
            }
        }

        return $request->wantsJson()
            ? response()->json(['status' => 'success', 'message' => 'Ruangan berhasil dibuat!', 'data' => $room], 201)
            : redirect()->route('rooms.index')->with('success', 'Ruangan berhasil dibuat!');
    }


    public function update(Request $request, $idrec)
    {
        $validated = $request->validate([
            'edit_property_id' => 'required|numeric|exists:m_properties,idrec',
            'edit_room_no' => 'required|string|max:255',
            'edit_room_name' => 'required|string|max:255',
            'edit_room_size' => 'required|numeric|min:0',
            'edit_room_bed' => 'required|string|in:Single,Double,King,Queen,Twin',
            'edit_room_capacity' => 'required|numeric|min:1',
            'edit_description_id' => 'required|string',
            'edit_daily_price' => 'nullable|numeric|min:0',
            'edit_monthly_price' => 'nullable|numeric|min:0',
            'edit_room_facilities' => 'nullable|array',
            'edit_room_images' => 'nullable|array|min:3|max:10',
            'edit_room_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Cari data kamar yang akan diupdate
        $room = Room::findOrFail($idrec);

        // Dapatkan data properti
        $property = Property::findOrFail($validated['edit_property_id']);

        // Proses fasilitas
        $allFacilities = [
            'wifi',
            'ac',
            'tv',
            'bathroom',
            'hot_water',
            'wardrobe',
            'desk',
            'refrigerator',
            'breakfast'
        ];
        $facilityData = [
            'features' => array_intersect($request->input('edit_room_facilities', []), $allFacilities),
        ];

        // Generate slug baru berdasarkan nama dan properti
        $tagShort = strtolower(substr($property->tags, 0, 3));
        $nameShort = strtolower(collect(explode(' ', $validated['edit_room_name']))->map(fn($w) => substr($w, 0, 1))->implode(''));
        $slug = $tagShort . '_' . $nameShort . '_' . $idrec;

        // Update data kamar
        $room->property_id = $validated['edit_property_id'];
        $room->property_name = $property->name;
        $room->slug = $slug;
        $room->no = $validated['edit_room_no'];
        $room->name = $validated['edit_room_name'];
        $room->descriptions = $validated['edit_description_id'];
        $room->size = $validated['edit_room_size'];
        $room->bed_type = $validated['edit_room_bed'];
        $room->capacity = $validated['edit_room_capacity'];
        $periode = [
            'daily' => !empty($validated['edit_daily_price']),
            'monthly' => !empty($validated['edit_monthly_price'])
        ];
        $room->periode = json_encode($periode);
        $room->type = $property->type;
        $room->level = 1;
        $room->facility = $facilityData['features'];
        $room->price = $validated['edit_daily_price'] ?? $validated['edit_monthly_price'] ?? 0;
        $room->price_original_daily = $validated['edit_daily_price'] ?? 0;
        $room->price_original_monthly = $validated['edit_monthly_price'] ?? 0;
        $room->updated_at = now();
        $room->save();

        // Jika ada gambar baru, hapus gambar lama dan simpan gambar baru
        if ($request->hasFile('edit_room_images')) {
            // Hapus semua gambar lama dari DB
            MRoomImage::where('room_id', $idrec)->delete();

            $imageBase64Array = [];
            $imageCaptionArray = [];

            foreach ($request->file('edit_room_images') as $file) {
                if (!$file->isValid()) {
                    return back()->withErrors(['edit_room_images' => 'Invalid file uploaded.']);
                }
                $fileContents = file_get_contents($file->getRealPath());
                $imageBase64Array[] = base64_encode($fileContents);
                $imageCaptionArray[] = $file->getClientOriginalName();
            }

            foreach ($imageBase64Array as $index => $base64) {
                $image = new MRoomImage();
                $image->room_id = $idrec;
                $image->image = $base64;
                $image->caption = $imageCaptionArray[$index] ?? 'No caption';
                $image->created_by = Auth::id();
                $image->created_at = now();
                $image->save();
            }
        }

        // Update harga harian jika ada perubahan daily_price
        if ($periode['daily']) {
            // Hapus harga harian lama
            RoomPrices::where('room_id', $idrec)->delete();

            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addYear();
            $dailyPrice = $validated['edit_daily_price'];

            $priceInserts = [];
            while ($startDate->lessThan($endDate)) {
                $priceInserts[] = [
                    'room_id' => $idrec,
                    'date' => $startDate->format('Y-m-d'),
                    'price' => $dailyPrice,
                    'created_at' => now(),
                    'created_by' => Auth::id(),
                    'status' => '1',
                ];

                if (count($priceInserts) >= 1000) {
                    RoomPrices::insert($priceInserts);
                    $priceInserts = [];
                }

                $startDate->addDay();
            }

            if (!empty($priceInserts)) {
                RoomPrices::insert($priceInserts);
            }
        }

        return $request->wantsJson()
            ? response()->json(['status' => 'success', 'message' => 'Ruangan berhasil diperbarui!', 'data' => $room], 200)
            : redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui!');
    }




    public function changePriceIndex(Room $room)
    {
        $rooms = Room::where('status', '!=', '2')
            ->with('transactions', 'bookings', 'property', 'creator')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $properties = Property::orderBy('name', 'asc')->get();
        return view('pages.Properties.m-Rooms.edit-prices', compact('room'));
    }

    public function getPriceForDate(Request $request, $roomId)
    {
        $date = $request->query('date');
        // dd($date);
        $price = DB::table('m_room_prices')
            ->where('room_id', $roomId)
            ->where('date', $date)
            ->value('price');

        return response()->json(['price' => $price ?? 'Price not found.']);
    }

    public function updatePriceRange(Request $request, $roomId)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'price' => 'required|numeric|min:0',
            ]);

            $start = Carbon::parse($validated['start_date']);
            $end = Carbon::parse($validated['end_date']);

            $dates = collect($start->daysUntil($end))->map(fn($date) => $date->toDateString());
            // dd($dates);

            DB::beginTransaction();

            // Step 1: Delete existing records for room_id and each date
            DB::table('m_room_prices')
                ->where('room_id', $roomId)
                ->whereIn('date', $dates)
                ->delete();

            // Step 2: Insert new records
            $rows = $dates->map(fn($date) => [
                'room_id' => $roomId,
                'date' => $date,
                'price' => $validated['price'],
                'status' => '1',
                'created_by' => Auth::id(),
                'created_at' => now(),
            ]);

            DB::table('m_room_prices')->insert($rows->toArray());

            DB::commit();

            return response()->json(['message' => 'Harga berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui harga.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRoomPrices(Request $request, $roomId)
    {
        $year = $request->get('year');
        $month = $request->get('month');

        $start = Carbon::createFromDate($year, $month, 1);
        $end = $start->copy()->endOfMonth();
        // dd($start, $end);

        $prices = RoomPrices::where('room_id', $roomId)
            ->whereBetween('date', [$start, $end])
            ->pluck('price', 'date') // returns [ '2025-06-13' => 1000000, ... ]
            ->mapWithKeys(function ($value, $key) {
                return [Carbon::parse($key)->toDateString() => $value];
            });
        // dd($start, $end, $prices);

        return response()->json($prices);
    }

    public function updateStatus(Room $room, Request $request)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $room->update([
            'status' => $request->status,
            'updated_at' => now(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['success' => true]);
    }







    public function edit(Room $room)
    {
        // Load relasi property dan roomImages
        $room->load('property', 'roomImages');

        // Ambil facility (sudah dalam bentuk JSON string, decode dulu)
        $facilities = [];
        if ($room->facility) {
            $decodedFacility = json_decode($room->facility, true);
            if (is_array($decodedFacility)) {
                $facilities = array_keys(array_filter($decodedFacility));
            }
        }

        // Ambil data image tanpa menggunakan Storage::url
        $images = $room->roomImages->map(function ($image) {
            return [
                'idrec' => $image->idrec,
                'image_url' => $image->image, // langsung dari field 'image'
                'image_name' => basename($image->image)
            ];
        });

        return response()->json([
            'room' => [
                'idrec' => $room->idrec,
                'property_id' => $room->property_id,
                'room_no' => $room->no, // disesuaikan ke field `no`
                'name' => $room->name,
                'descriptions' => $room->descriptions,
                'bed_type' => $room->bed_type,
                'capacity' => $room->capacity,
                'room_size' => $room->size,
                'price_original_daily' => $room->price_original_daily,
                'price_original_monthly' => $room->price_original_monthly,
                'facility' => $facilities
            ],
            'images' => $images
        ]);
    }

    public function destroy($idrec)
    {
        try {
            $room = Room::findOrFail($idrec);

            // Soft delete: update status jadi 2
            $room->status = '2';
            $room->save();

            return response()->json(['message' => 'Room deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete room.'], 500);
        }
    }
}
