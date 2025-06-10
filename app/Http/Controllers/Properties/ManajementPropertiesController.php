<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class ManajementPropertiesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 5);

        $query = Property::with('creator')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('province', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->when(isset($status) && $status !== '', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc');

        $properties = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        return view('pages.Properties.m-Properties.index', [
            'properties' => $properties,
            'search' => $search,
            'status' => $status,
            'per_page' => $perPage,
        ]);
    }

    public function updateStatus(Property $property, Request $request)
    {
        $request->validate([
            'status' => 'required|boolean'
        ]);

        $property->update([
            'status' => $request->status,
            'updated_at' => now(),
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_name' => 'required',
            'property_type' => 'required',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'postal_code' => 'nullable|string',
            'full_address' => 'required|string',
            'description' => 'nullable|string',
            'distance' => 'nullable|string',
            'latitude' => 'required',
            'longitude' => 'required',
            'property_images' => 'required|array|size:3',
            'property_images.*' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'facilities' => 'nullable|array',
        ]);

        if (count($request->file('property_images')) !== 3) {
            return back()->withErrors(['property_images' => 'Exactly 3 images are required.']);
        }

        $imageBase64Array = [];

        foreach ($request->file('property_images') as $file) {
            if (!$file->isValid()) {
                return back()->withErrors(['property_images' => 'Invalid file uploaded.']);
            }

            $fileContents = file_get_contents($file->getRealPath());
            $imageBase64Array[] = base64_encode($fileContents);
        }

        if (count($imageBase64Array) !== 3) {
            abort(400, 'Exactly 3 images are required.');
        }

        $features = array_merge(
            ['High-speed WiFi', 'Parking', 'Swimming Pool', 'Gym', 'Restaurant', '24/7 Security', 'Concierge', 'Laundry Service', 'Room Service']
        );

        $facilitiesData = [
            'features' => array_intersect($request->input('facilities', []), $features),
        ];

        $idrec = Property::max('idrec') + 1;

        $tagShort = strtolower(substr($request->property_type, 0, 3));
        $nameShort = strtolower(collect(explode(' ', $request->property_name))->map(function ($word) {
            return substr($word, 0, 1);
        })->implode(''));
        $slug = $tagShort . '_' . $nameShort . '_' . $idrec;

        $property = new Property();
        $property->idrec = $idrec;
        $property->slug = $slug;
        $property->tags = $request->property_type;
        $property->name = $request->property_name;
        $property->province = $request->province;
        $property->city = $request->city;
        $property->subdistrict = $request->district;
        $property->village = $request->village;
        $property->postal_code = $request->postal_code;
        $property->address = $request->full_address;
        $property->description = $request->description;
        $property->distance = $request->distance;
        $property->location = $request->latitude . ',' . $request->longitude;

        $property->image = $imageBase64Array[0];
        $property->image2 = $imageBase64Array[1];
        $property->image3 = $imageBase64Array[2];

        $property->features = json_encode($facilitiesData['features']);
        $property->status = '1';
        $property->created_by = Auth::id();

        $property->save();

        return $request->wantsJson()
            ? response()->json(['status' => 'success', 'message' => 'Property created successfully!', 'data' => $property], 201)
            : redirect()->route('properties.index')->with('success', 'Property created successfully!');
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'property_name' => 'required|string|max:255',
            'property_type' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'postal_code' => 'nullable|string',
            'full_address' => 'required|string',
            'description' => 'nullable|string',
            'distance' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'nullable|string',
            'property_images' => 'nullable|array',
            'property_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'features' => 'nullable|array',
            'attributes' => 'nullable|array',
        ]);

        $property = Property::findOrFail($id);

        // Handle image update
        $existingImages = $request->input('existing_images', []);
        $newImages = [];

        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $image) {
                $imageData = file_get_contents($image->getRealPath());
                $base64 = base64_encode($imageData);
                $newImages[] = $base64;
            }
        }

        // Combine existing and new images
        $allImages = array_merge($existingImages, $newImages);

        // Update property data
        $property->update([
            'name' => $validated['property_name'],
            'tags' => $validated['property_type'],
            'province' => $validated['province'],
            'city' => $validated['city'],
            'subdistrict' => $validated['district'],
            'village' => $validated['village'],
            'postal_code' => $validated['postal_code'],
            'address' => $validated['full_address'],
            'description' => $validated['description'],
            'distance' => $validated['distance'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'location' => $validated['latitude'] . ',' . $validated['longitude'],
            'image' => json_encode($allImages),
            'features' => $validated['features'] ?? [],
            'attributes' => $validated['attributes'] ?? [],
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('properties.index')
            ->with('success', 'Properti berhasil diperbarui');
    }
}
