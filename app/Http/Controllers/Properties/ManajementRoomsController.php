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
    public function index()
    {
        $rooms = Room::where('status', '!=', '2')
            ->with('transactions', 'bookings', 'property', 'creator', 'roomImages')
            // ->where('property_id', Auth::user()->property_id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        // if (Auth::user()->property_id == 0) {
        //     $properties = Property::orderBy('name', 'asc')->get();
        // } else {
        //     $properties = Property::where('idrec', Auth::user()->property_id)->first();
        // }

        $properties = Property::orderBy('name', 'asc')->get();

        return view('pages.Properties.m-Rooms.index', compact('rooms', 'properties'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
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
                'facilities.*' => 'string|in:wifi,ac,tv,bathroom,hot_water,wardrobe,desk,refrigerator,breakfast',
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

            $facilityData = array_fill_keys($allFacilities, false);
            foreach ($validated['facilities'] ?? [] as $facility) {
                $facilityData[$facility] = true;
            }

            // Process images
            $imageData = [];
            foreach ($request->file('room_images') as $image) {
                $imageContent = file_get_contents($image->getRealPath());
                $imageData[] = [
                    'base64' => 'data:' . $image->getClientMimeType() . ';base64,' . base64_encode($imageContent),
                    'caption' => $image->getClientOriginalName(),
                ];
            }

            // Generate slug
            $idrec = Room::max('idrec') + 1;
            $tagShort = strtolower(substr($request->property_type, 0, 3));
            $nameShort = strtolower(collect(explode(' ', $request->property_name))->map(fn($w) => substr($w, 0, 1))->implode(''));
            $slug = $tagShort . '_' . $nameShort . '_' . $idrec;

            // Determine price mode - tetap sebagai array untuk kondisi if
            $periode = [
                'daily' => !empty($validated['daily_price']),
                'monthly' => !empty($validated['monthly_price'])
            ];

            // Create room data
            $room = Room::create([
                'idrec' => $idrec,
                'property_id' => $validated['property_id'],
                'property_name' => $property->name,
                'slug' => $slug,
                'no' => $validated['room_no'],
                'name' => $validated['room_name'],
                'descriptions' => $validated['description_id'],
                'size' => $validated['room_size'],
                'bed_type' => $validated['room_bed'],
                'capacity' => $validated['room_capacity'],
                'periode' => json_encode($periode),
                'type' => $property->type,
                'level' => 1,
                'facility' => json_encode(array_keys(array_filter($facilityData))),
                'price' => $validated['daily_price'] ?? $validated['monthly_price'] ?? 0,
                'discount_percent' => 0,
                'price_original_daily' => $validated['daily_price'] ?? 0,
                'price_original_monthly' => $validated['monthly_price'] ?? 0,
                'created_by' => Auth::id(),
                'status' => 1,
                'created_at' => now(),
            ]);

            // Save room images
            foreach ($imageData as $img) {
                MRoomImage::create([
                    'room_id' => $idrec,
                    'image' => $img['base64'],
                    'caption' => $img['caption'],
                    'created_by' => Auth::id(),
                    'created_at' => now(),
                ]);
            }

            // Gunakan array $periode untuk pengecekan kondisi
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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kamar berhasil ditambahkan',
                'data' => $room
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $idrec)
    {
        // dd("Jalan", $request->all(), $idrec);

        $validated = $request->validate([
            'edit_room_name' => 'required|string|max:255',
            'edit_room_size' => 'required|numeric',
            'edit_room_bed' => 'required|string',
            'edit_room_capacity' => 'required|numeric',
            'description_id' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'daily_price' => 'nullable|numeric',
            'monthly_price' => 'nullable|numeric',
            'facilities' => 'nullable|string',
            'mode' => 'nullable|string',
        ]);

        // Handle photo
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoContents = file_get_contents($photo->getRealPath());
            $base64Photo = base64_encode($photoContents);
            $validated['photo'] = $base64Photo;
        }

        // Facilities
        $facilities = [
            'wifi' => $request->has('wifi'),
            'tv' => $request->has('tv'),
            'ac' => $request->has('ac'),
            'bathroom' => $request->has('bathroom'),
        ];

        // Keep only the keys where value is true
        $selectedFacilities = array_keys(array_filter($facilities));

        // Encode as JSON
        $validated['facilities'] = json_encode($selectedFacilities);


        $data = [
            'name' => $validated['edit_room_name'],
            'descriptions' => $validated['description_id'],
            'size' => $validated['edit_room_size'],
            'bed_type' => $validated['edit_room_bed'],
            'capacity' => $validated['edit_room_capacity'],
            'image' => $validated['photo'] ?? null, // Use photo from $validated if available
            'price_original_daily' => $validated['daily_price'] ?? 0,
            'price_original_monthly' => $validated['monthly_price'] ?? 0,
            'facility' => $validated['facilities'],
            'periode' => $validated['mode'],
            'updated_at' => now(),
            'updated_by' => Auth::id(),
            'status' => '1',
        ];

        try {
            DB::beginTransaction();

            // Updates the room
            $room = Room::find($idrec);
            $oldPrice = $room->price_original_daily;
            $dailyPrice = $validated['daily_price'] ?? 0;

            $room->update($data);

            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addYear();

            // Step 1: Fetch all current prices in one go
            $existingPrices = RoomPrices::where('room_id', $idrec)
                ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->pluck('price', 'date'); // ['YYYY-MM-DD' => price]

            $priceInserts = [];

            while ($startDate->lessThan($endDate)) {
                $dateStr = $startDate->format('Y-m-d');
                $existingPrice = $existingPrices[$dateStr] ?? null;
                // dd($existingPrice, $oldPrice, $facilities);

                if (is_null($existingPrice)) {
                    // No price exists, just insert new price
                    $priceInserts[] = [
                        'room_id'    => $idrec,
                        'date'       => $dateStr,
                        'price'      => $dailyPrice,
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                        'status'     => '1',
                    ];
                } elseif ($existingPrice == $oldPrice) {
                    // Same as old price → delete then insert new
                    DB::table('m_room_prices')
                        ->where('room_id', $idrec)
                        ->where('date', $dateStr)
                        ->delete();

                    $priceInserts[] = [
                        'room_id'    => $idrec,
                        'date'       => $dateStr,
                        'price'      => $dailyPrice,
                        'created_at' => now(),
                        'created_by' => Auth::id(),
                        'status'     => '1',
                    ];
                }

                $startDate->addDay();
            }

            if (!empty($priceInserts)) {
                DB::table('m_room_prices')->insert($priceInserts);
            }

            DB::commit();

            return redirect()->route('rooms.index')->with('success', 'Kamar berhasil disimpan.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan kamar.')->withInput();
        }
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
    
}
