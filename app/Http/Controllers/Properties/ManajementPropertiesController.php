<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyImage;
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

        $query = Property::with(['creator', 'images'])
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

    protected function generateInitials($name)
    {
        $words = preg_split('/\s+/', $name);
        $initials = '';

        foreach ($words as $word) {
            // Ambil huruf pertama jika kapital
            if (ctype_upper(substr($word, 0, 1))) {
                $initials .= substr($word, 0, 1);
            }
        }

        // Jika tidak ada huruf kapital, ambil semua huruf pertama
        if (empty($initials)) {
            foreach ($words as $word) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }

        return $initials;
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
            'latitude' => 'required',
            'longitude' => 'required',
            'property_images' => 'required|array|min:1|max:10',
            'property_images.*' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'facilities' => 'nullable|array',
        ]);

        // Fitur yang tersedia
        $features = [
            'High-speed WiFi',
            'Parking',
            'Swimming Pool',
            'Gym',
            'Restaurant',
            '24/7 Security',
            'Concierge',
            'Laundry Service',
            'Room Service'
        ];

        // Ambil hanya fasilitas yang valid
        $facilitiesData = [
            'features' => array_intersect($request->input('facilities', []), $features),
        ];

        // Buat ID dan slug unik
        $idrec = Property::max('idrec') + 1;
        $tagShort = strtolower(substr($request->property_type, 0, 3));
        $nameShort = strtolower(collect(explode(' ', $request->property_name))->map(fn($w) => substr($w, 0, 1))->implode(''));
        $slug = $tagShort . '_' . $nameShort . '_' . $idrec;

        // Generate initial dari property_name
        $initials = $this->generateInitials($request->property_name);

        // Encode file gambar ke base64
        $imageBase64Array = [];
        $imageCaptionArray = [];

        foreach ($request->file('property_images') as $file) {
            if (!$file->isValid()) {
                return back()->withErrors(['property_images' => 'Invalid file uploaded.']);
            }

            $fileContents = file_get_contents($file->getRealPath());
            $imageBase64Array[] = base64_encode($fileContents);
            $imageCaptionArray[] = $file->getClientOriginalName();
        }

        // Simpan ke tabel `properties`
        $property = new Property();
        $property->idrec = $idrec;
        $property->slug = $slug;
        $property->tags = $request->property_type;
        $property->name = $request->property_name;
        $property->initial = $initials;
        $property->province = $request->province;
        $property->city = $request->city;
        $property->subdistrict = $request->district;
        $property->village = $request->village;
        $property->postal_code = $request->postal_code;
        $property->address = $request->full_address;
        $property->description = $request->description;
        $property->location = $request->latitude . ',' . $request->longitude;
        $property->latitude = $request->latitude;
        $property->longitude = $request->longitude;
        $property->features = $facilitiesData['features'];
        $property->status = '1';
        $property->created_by = Auth::id();
        $property->save();
       
        // Simpan ke tabel m_property_images
        foreach ($imageBase64Array as $index => $base64) {
            $image = new PropertyImage();
            $image->property_id = $idrec;
            $image->image = $base64; // base64 string
            $image->caption = $imageCaptionArray[$index] ?? 'No caption';
            $image->created_by = Auth::user()->id;
            $image->created_at = now();
            $image->save();
        }

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
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'nullable|string',
            'property_images' => 'nullable|array',
            'property_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'features' => 'nullable|array',
        ]);

        $property = Property::findOrFail($id);

        // Whitelist fitur yang valid
        $validFeatures = [
            "High-speed WiFi",
            "Parking",
            "Swimming Pool",
            "Gym",
            "Restaurant",
            "24/7 Security",
            "Concierge",
            "Laundry Service",
            "Room Service"
        ];

        // Filter fitur yang dikirim agar hanya menyimpan yang valid
        $filteredFeatures = array_intersect($request->input('features', []), $validFeatures);

        // Ambil gambar yang masih dipertahankan
        $existingImages = $request->input('existing_images', []);

        // Hapus gambar dari DB yang tidak termasuk existingImages
        PropertyImage::where('property_id', $property->idrec)
            ->whereNotIn('image', $existingImages)
            ->delete();

        // Tambahkan gambar baru jika ada
        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $file) {
                if (!$file->isValid()) continue;

                $fileContents = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileContents);
                $caption = $file->getClientOriginalName();

                PropertyImage::create([
                    'property_id' => $property->idrec,
                    'image' => $base64,
                    'caption' => $caption,
                    'created_by' => Auth::id(),
                    'created_at' => now()
                ]);
            }
        }

        // Update data properti
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
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'location' => $validated['latitude'] . ',' . $validated['longitude'],
            'features' => $filteredFeatures,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('properties.index')
            ->with('success', 'Properti berhasil diperbarui');
    }
}
