<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManajementRoomsController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('created_at', 'desc')->paginate(5);
        $properties = Property::orderBy('name', 'asc')->get();
        return view('pages.Properties.m-Rooms.index', compact('rooms', 'properties'));
    }

    public function store(Request $request)
    {
        // dd($request);
        // Validate and store room
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'level' => 'required|numeric',
            'property_id' => 'required|numeric',
            'property_name' => 'required|string',
            'room_type' => 'required|string',
            'description_id' => 'nullable|string',
            'description_en' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'daily_price' => 'nullable|numeric',
            'daily_discount_price' => 'nullable|numeric',
            'monthly_price' => 'nullable|numeric',
            'monthly_discount_price' => 'nullable|numeric',
            'facilities' => 'nullable|string',
            'availability' => 'nullable|string',
        ]);

        //Convert to base-64 and then limit image size to 10 MB
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoContents = file_get_contents($photo->getRealPath());
            $base64Photo = base64_encode($photoContents);

            $validated['photo'] = $base64Photo; // Save this in the DB or send to API
        }
        
        $facilities = [
            'wifi' => $request->has('wifi'),
            'tv' => $request->has('tv'),
            'ac' => $request->has('ac'),
            'bathroom' => $request->has('bathroom'),
        ];

        $validated['facilities'] = json_encode($facilities);

        $dailyAvailable = !($request->daily_price == 0 && $request->daily_discount_price == 0);
        $monthlyAvailable = !($request->monthly_price == 0 && $request->monthly_discount_price == 0);

        $validated['availability'] = json_encode([
            'daily' => $dailyAvailable,
            'monthly' => $monthlyAvailable,
        ]);
        
        $data = [
            'property_id' => $validated['property_id'],
            'property_name' => $validated['property_name'],
            'name' => $validated['room_name'],
            'level' => $validated['level'],
            'type' => $validated['room_type'],
            'descriptions' => $validated['description_id'],
            'attachment' => $validated['photo'] ?? null, // Use photo from $validated if available
            'price_original_daily' => $validated['daily_price'] ?? 0,
            'price_discounted_daily' => $validated['daily_discount_price'] ?? 0,
            'price_original_monthly' => $validated['monthly_price'] ?? 0,
            'price_discounted_monthly' => $validated['monthly_discount_price'] ?? 0,
            'facility' => $validated['facilities'],
            'periode' => $validated['availability'],
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 'admin',
            'updated_by' => 'admin',
            'status' => '1',
        ];

        // dd($data);
        $success = Room::create($data);
        // dd($success);
        // Room::create($validated); // Adjust based on your model

        if ($success) {
            return redirect()->route('rooms.index')->with('success', 'Kamar berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan kamar.');
        }
    }

}
