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
    public function index()
    {
        $properties = Property::orderBy('created_at', 'desc', 'creator')->paginate(5);
        return view('pages.Properties.m-Properties.index', compact('properties'));
    }

    public function toggleStatus($idrec)
    {
        $property = Property::findOrFail($idrec);
        $property->status = $property->status === 'active' ? 'non active' : 'active';
        $property->updated_at = now(); // jika kamu pakai timestamps manual
        $property->save();

        return back()->with('success', 'Status updated successfully.');
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
            $imageBase64Arr[] = "data:$mime;base64,$base64";
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
        $property->features = json_encode($facilitiesData['amenities']);
        $property->attributes = json_encode(['rules' => $facilitiesData['rules']]);
        $property->status = '1';
        $property->created_by = Auth::id();
        $property->save();

        return response()->json(['success' => true]);
    }
}
