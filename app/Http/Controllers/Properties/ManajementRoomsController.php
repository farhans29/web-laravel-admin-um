<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomPrices;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ManajementRoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::where('status', '!=', '2')
                ->with('transactions', 'bookings', 'property', 'creator')
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        $properties = Property::orderBy('name', 'asc')->get();
        return view('pages.Properties.m-Rooms.index', compact('rooms', 'properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_no' => 'required|string|max:255',
            'room_name' => 'required|string|max:255',
            'level' => 'nullable|numeric',
            'property_id' => 'required|numeric',
            'property_name' => 'required|string',
            'room_type' => 'nullable|string',
            'description_id' => 'nullable|string',
            'description_en' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'daily_price' => 'nullable|numeric',
            'monthly_price' => 'nullable|numeric',
            'facilities' => 'nullable|string',
            'mode' => 'nullable|string',
        ]);
        // dd($validated);

        // Handle photo
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoContents = file_get_contents($photo->getRealPath());
            $base64Photo = base64_encode($photoContents);
            $validated['photo'] = $base64Photo;
        }

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
            'property_id' => $validated['property_id'],
            'property_name' => $validated['property_name'],
            'no' => $validated['room_no'],
            'name' => $validated['room_name'],
            'level' => "",
            'type' => "",
            'descriptions' => $validated['description_id'],
            'attachment' => $validated['photo'] ?? null, // Use photo from $validated if available
            'discount_percent' => 0,
            'price_original_daily' => $validated['daily_price'] ?? 0,
            'price_discounted_daily' => 0,
            'price_original_monthly' => $validated['monthly_price'] ?? 0,
            'price_discounted_monthly' => 0,
            'facility' => $validated['facilities'],
            'periode' => $validated['mode'],
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'status' => '1',
        ];

        try {
            DB::beginTransaction();

            // Save the room
            $room = Room::create($data);
            // dd($room);

            // Loop from today to one year later
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addYear();
            $dailyPrice = $validated['daily_price'] ?? 0;

            $priceInserts = [];
            while ($startDate->lessThan($endDate)) {
                $priceInserts[] = [
                    'room_id' => $room->idrec,
                    'date' => $startDate->format('Y-m-d'),
                    'price' => $dailyPrice,
                    'created_at' => now(),
                    'created_by' => Auth::id(),
                    'status' => '1',
                ];
                $startDate->addDay();
            }

            RoomPrices::insert($priceInserts);

            DB::commit();

            return redirect()->route('rooms.index')->with('success', 'Kamar berhasil disimpan.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan kamar.')->withInput();
        }
    }

    public function update(Request $request, $idrec)
    {
        // dd("Jalan", $request->all(), $idrec);

        $validated = $request->validate([
            'edit_room_name' => 'required|string|max:255',
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
            'attachment' => $validated['photo'] ?? null, // Use photo from $validated if available
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

    /**
     * Shows the room prices index page for a given room.
     *
     * @param \App\Models\Room $room
     * @return \Illuminate\Contracts\View\View
     */
    public function changePriceIndex(Room $room)
    {
        $rooms = Room::where('status', '!=', '2')
                ->with('transactions', 'bookings', 'property', 'creator')
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        $properties = Property::orderBy('name', 'asc')->get();
        return view('pages.Properties.m-Rooms.edit-prices', compact('room'));
    }

    // GET price for selected date
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

    // POST to update price over a range
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

            $dates = collect($start->daysUntil($end))->map(fn ($date) => $date->toDateString());

            DB::beginTransaction();

            // Step 1: Delete existing records for room_id and each date
            DB::table('m_room_prices')
                ->where('room_id', $roomId)
                ->whereIn('date', $dates)
                ->delete();

            // Step 2: Insert new records
            $rows = $dates->map(fn ($date) => [
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
}
