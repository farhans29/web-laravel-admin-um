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
            'property_images' => 'required|array|min:1',
            'property_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'facilities' => 'nullable|array',
        ]);

        // Encode images to base64
        $imageBase64Arr = [];
        foreach ($request->file('property_images') as $image) {
            $imageData = file_get_contents($image->getRealPath());
            $base64 = base64_encode($imageData);
            $mime = $image->getMimeType();
            $imageBase64Arr[] = $base64;
        }

        // Definisikan kategori fasilitas
        $amenities = [
            'High-speed WiFi',
            '24/7 Security',
            'Shared Kitchen',
            'Laundry Service',
            'Parking Area',
            'Common Area'
        ];

        $rules = [
            'No Smoking',
            'No Pets',
            'ID Card Required',
            'Deposit Required'
        ];

        // Pisahkan fasilitas berdasarkan kategori
        $facilitiesData = [
            'amenities' => [],
            'rules' => []
        ];

        foreach ($request->input('facilities', []) as $facility) {
            if (in_array($facility, $amenities)) {
                $facilitiesData['amenities'][] = $facility;
            } elseif (in_array($facility, $rules)) {
                $facilitiesData['rules'][] = $facility;
            }
        }

        // Generate idrec baru
        $idrec = Property::max('idrec') + 1;

        // Generate slug: 3 huruf pertama tag + inisial nama + idrec
        $tagShort = strtolower(substr($request->property_type, 0, 3));
        $nameShort = strtolower(collect(explode(' ', $request->property_name))->map(function ($word) {
            return substr($word, 0, 1);
        })->implode(''));
        $slug = $tagShort . '_' . $nameShort . '_' . $idrec;

        // Simpan data properti
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
        $property->image = json_encode($imageBase64Arr);
        $property->features = $facilitiesData['amenities'];
        $property->attributes = $facilitiesData['rules'];
        $property->status = '1';
        $property->created_by = Auth::id();
        $property->save();

        return response()->json(['success' => true]);
    }

    public function edit($propertyId)
    {
        $property = Property::findOrFail($propertyId);

        $property->image = json_decode($property->image, true);
        $property->features = is_array($property->features) ? $property->features : json_decode($property->features, true);
        $property->attributes = is_array($property->attributes) ? $property->attributes : json_decode($property->attributes, true);

        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, $idrec)
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
            'facilities' => 'nullable|array',
        ]);

        $property = Property::findOrFail($idrec);

        // Handle image update
        $existingImages = $request->input('existing_images', []);
        $newImages = [];

        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $image) {
                $imageData = file_get_contents($image->getRealPath());
                $base64 = base64_encode($imageData);
                $mime = $image->getMimeType();
                $newImages[] = "data:$mime;base64,$base64";
            }
        }

        $allImages = array_merge($existingImages, $newImages);

        // Pisahkan fasilitas
        $amenities = [
            'High-speed WiFi',
            '24/7 Security',
            'Shared Kitchen',
            'Laundry Service',
            'Parking Area',
            'Common Area'
        ];

        $rules = [
            'No Smoking',
            'No Pets',
            'ID Card Required',
            'Deposit Required'
        ];

        $facilitiesData = [
            'amenities' => [],
            'rules' => []
        ];

        foreach ($request->input('facilities', []) as $facility) {
            if (in_array($facility, $amenities)) {
                $facilitiesData['amenities'][] = $facility;
            } elseif (in_array($facility, $rules)) {
                $facilitiesData['rules'][] = $facility;
            }
        }

        // Update data property
        $property->name = $request->property_name;
        $property->tags = $request->property_type;
        $property->province = $request->province;
        $property->city = $request->city;
        $property->subdistrict = $request->district;
        $property->village = $request->village;
        $property->postal_code = $request->postal_code;
        $property->address = $request->full_address;
        $property->description = $request->description;
        $property->distance = $request->distance;
        $property->location = $request->latitude . ',' . $request->longitude;
        $property->image = json_encode($allImages);
        $property->features = $facilitiesData['amenities'];
        $property->attributes = $facilitiesData['rules'];
        $property->updated_by = Auth::id(); 
        $property->save();

        return redirect()->route('properties.index')
            ->with('success', 'Properti berhasil diperbarui');
    }
}
