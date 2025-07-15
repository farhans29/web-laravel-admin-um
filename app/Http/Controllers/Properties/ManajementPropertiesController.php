<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Auth;

class ManajementPropertiesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Property::with(['creator', 'images'])
            ->orderBy('created_at', 'desc');

        $properties = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        return view('pages.Properties.m-Properties.index', [
            'properties' => $properties,
            'per_page' => $perPage,
        ]);
    }

    public function filter(Request $request)
    {
        $perPage = $request->input('per_page', 8);
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Property::with(['creator', 'images'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('province', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan status
        if ($status !== null) {
            $query->where('status', $status);
        }

        $properties = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('pages.Properties.m-Properties.partials.property_table', [
                    'properties' => $properties,
                    'per_page' => $perPage,
                ])->render(),
                'pagination' => $perPage !== 'all'
                    ? $properties->appends(request()->input())->links()->toHtml()
                    : ''
            ]);
        }

        return view('pages.Properties.m-Properties.index', [
            'properties' => $properties,
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
            ? response()->json(['status' => 'success', 'message' => 'Properti berhasil dibuat!', 'data' => $property], 201)
            : redirect()->route('properties.index')->with('success', 'Properti berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string',
                'description' => 'nullable|string',
                'address' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'province' => 'required|string',
                'city' => 'required|string',
                'subdistrict' => 'required|string',
                'village' => 'required|string',
                'postal_code' => 'nullable|string',
                'features' => 'nullable|array',
                'property_images' => 'nullable|array',
                'property_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'integer',
            ]);

            $property = Property::findOrFail($id);

            // Validasi dan filter fitur
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
            $filteredFeatures = array_intersect($request->input('features', []), $validFeatures);

            // Hapus gambar berdasarkan delete_images
            $imagesToDelete = $request->input('delete_images', []);
            if (!empty($imagesToDelete)) {
                PropertyImage::whereIn('idrec', $imagesToDelete)
                    ->where('property_id', $property->idrec)
                    ->delete();
            }

            // Simpan gambar baru jika ada
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
            // Update data ke tabel `m_properties`
            $property->update([
                'name' => $request->input('name'),
                'tags' => $request->input('tags'),
                'description' => $request->input('description'),
                'address' => $request->input('address'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'location' => $request->input('latitude') . ',' . $request->input('longitude'),
                'province' => $request->input('province'),
                'city' => $request->input('city'),
                'subdistrict' => $request->input('subdistrict'),
                'village' => $request->input('village'),
                'postal_code' => $request->input('postal_code'),
                'features' => $filteredFeatures,
                'updated_by' => Auth::id(),
                'updated_at' => now(),
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Properti berhasil diperbarui',
                'redirect' => route('properties.index')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function tablePartial(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Property::with(['creator', 'images'])
            ->orderBy('created_at', 'desc');

        $properties = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        return view('pages.Properties.m-Properties.partials.property_table', [
            'properties' => $properties,
            'per_page' => $perPage,
        ]);
    }
}
