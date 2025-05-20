<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use Illuminate\Http\Request;

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
        // Validate and store room
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'level' => 'required|numeric',
            'property_id' => 'required|numeric',
            'description_id' => 'nullable|string',
            'description_en' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'daily_price' => 'nullable|numeric',
            'daily_discount_price' => 'nullable|numeric',
            'monthly_price' => 'nullable|numeric',
            'monthly_discount_price' => 'nullable|numeric',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('room_photos', 'public');
        }
        
        $data = [
            'room_name' => $validated['room_name'],
            'level' => $validated['level'],
            'property_id' => $validated['property_id'],
            'description_id' => $validated['description_id'],
            'photo' => $validated['photo'] ?? null, // Use photo from $validated if available
            'price_original_daily' => $validated['daily_price'] ?? 100,
            'price_original_monthly' => $validated['daily_discount_price'] ?? 80,
            'price_discounted_daily' => $validated['monthly_price'] ?? 3000,
            'price_discounted_monthly' => $validated['monthly_discount_price'] ?? 2500,
        ];

        Room::create($data);

        Room::create($validated); // Adjust based on your model

        return redirect()->back()->with('success', 'Room added successfully.');
    }

}
