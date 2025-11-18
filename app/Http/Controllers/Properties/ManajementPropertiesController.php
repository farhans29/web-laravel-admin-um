<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Support\Facades\Auth;
use App\Models\PropertyFacility;
use Illuminate\Support\Facades\Storage;

class ManajementPropertiesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        $query = Property::with(['creator', 'images', 'thumbnail'])
            ->orderBy('created_at', 'desc');

        $generalFacilities = PropertyFacility::where('status', 1)->byCategory('general')->get();
        $securityFacilities = PropertyFacility::where('status', 1)->byCategory('security')->get();
        $amenitiesFacilities = PropertyFacility::where('status', 1)->byCategory('amenities')->get();

        $facilities = PropertyFacility::where('status', 1)
            ->get()
            ->groupBy('category');

        $properties = $perPage === 'all'
            ? $query->get()
            : $query->paginate((int) $perPage)->withQueryString();

        return view('pages.Properties.m-Properties.index', [
            'generalFacilities' => $generalFacilities,
            'securityFacilities' => $securityFacilities,
            'amenitiesFacilities' => $amenitiesFacilities,
            'facilities' => $facilities,
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
            'property_images' => 'required|array|min:3|max:10', // Minimal 3 foto
            'property_images.*' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'facilities' => 'nullable|array',
            'thumbnail_index' => 'required|integer',
            'general_facilities' => 'nullable|array',
            'security_facilities' => 'nullable|array',
            'amenities_facilities' => 'nullable|array',
        ]);

        // Ambil hanya fasilitas yang valid
        $facilitiesData = [
            'general' => array_reverse($request->input('general_facilities', [])),
            'security' => array_reverse($request->input('security_facilities', [])),
            'amenities' => array_reverse($request->input('amenities_facilities', [])),
        ];


        // Buat ID dan slug unik
        $idrec = Property::max('idrec') + 1;
        $tagShort = strtolower(substr($request->property_type, 0, 3));
        $nameShort = strtolower(collect(explode(' ', $request->property_name))->map(fn($w) => substr($w, 0, 1))->implode(''));
        $slug = $tagShort . '_' . $nameShort . '_' . $idrec;

        // Generate initial dari property_name
        $initials = $this->generateInitials($request->property_name);

        $imagePaths = [];
        $thumbnailIndex = $request->thumbnail_index;

        foreach ($request->file('property_images') as $index => $file) {
            if (!$file->isValid()) {
                return back()->withErrors(['property_images' => 'Invalid file uploaded.']);
            }

            // Generate nama file unik
            $fileName = 'property_' . $idrec . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();

            // Simpan file ke storage/app/public/property_image
            $path = $file->storeAs('property_image', $fileName, 'public');

            // Simpan path untuk digunakan di database
            $imagePaths[] = $path;
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
        $property->general = $facilitiesData['general'];
        $property->security = $facilitiesData['security'];
        $property->amenities = $facilitiesData['amenities'];
        $property->status = '1';
        $property->created_by = Auth::id();
        $property->save();

        // Simpan ke tabel m_property_images
        foreach ($imagePaths as $index => $imagePath) {
            $image = new PropertyImage();
            $image->property_id = $idrec;
            $image->image = $imagePath; // path ke file gambar di storage
            $image->caption = $request->file('property_images')[$index]->getClientOriginalName() ?? 'No caption';
            $image->thumbnail = ($index == $thumbnailIndex) ? 1 : 0;
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
                'tags' => 'required|string',
                'description' => 'nullable|string',
                'address' => 'required|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'province' => 'required|string',
                'city' => 'required|string',
                'subdistrict' => 'required|string',
                'village' => 'required|string',
                'postal_code' => 'nullable|string',
                'general' => 'nullable|array',
                'general.*' => 'integer',
                'security' => 'nullable|array',
                'security.*' => 'integer',
                'amenities' => 'nullable|array',
                'amenities.*' => 'integer',
                'property_images' => 'nullable|array',
                'property_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'integer',
                'thumbnail_index' => 'required|integer|min:0',
                'existing_images' => 'nullable|array',
                'existing_images.*' => 'integer',
            ]);

            $property = Property::findOrFail($id);

            // Hapus gambar berdasarkan delete_images
            $imagesToDelete = $request->input('delete_images', []);
            if (!empty($imagesToDelete)) {
                // Hapus file dari storage
                $imagesToDeleteRecords = PropertyImage::whereIn('idrec', $imagesToDelete)
                    ->where('property_id', $property->idrec)
                    ->get();

                foreach ($imagesToDeleteRecords as $image) {
                    // Hapus file fisik dari storage
                    if (Storage::disk('public')->exists($image->image)) {
                        Storage::disk('public')->delete($image->image);
                    }
                }

                // Hapus dari database
                PropertyImage::whereIn('idrec', $imagesToDelete)
                    ->where('property_id', $property->idrec)
                    ->delete();
            }

            // Reset all thumbnails first
            PropertyImage::where('property_id', $property->idrec)
                ->update(['thumbnail' => false]);

            // Get all existing images (after possible deletion)
            $existingImages = PropertyImage::where('property_id', $property->idrec)
                ->orderBy('idrec')
                ->get();

            // Handle thumbnail selection for existing images
            $thumbnailIndex = $request->input('thumbnail_index');
            $existingImagesCount = $existingImages->count();

            // Set thumbnail untuk existing images
            if ($thumbnailIndex < $existingImagesCount) {
                $existingImages[$thumbnailIndex]->update(['thumbnail' => true]);
            }

            // Simpan gambar baru jika ada
            $newImages = [];
            if ($request->hasFile('property_images')) {
                foreach ($request->file('property_images') as $index => $file) {
                    if (!$file->isValid()) continue;

                    // Generate unique filename
                    $filename = 'property_' . $property->idrec . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();

                    // Store file to storage
                    $filePath = $file->storeAs('property_images', $filename, 'public');

                    $caption = $file->getClientOriginalName();

                    // Check if this new image should be thumbnail
                    $isThumbnail = ($thumbnailIndex >= $existingImagesCount) &&
                        ($index == ($thumbnailIndex - $existingImagesCount));

                    $newImage = PropertyImage::create([
                        'property_id' => $property->idrec,
                        'image' => $filePath, // Simpan path file, bukan base64
                        'thumbnail' => $isThumbnail,
                        'caption' => $caption,
                        'created_by' => Auth::id(),
                        'created_at' => now()
                    ]);

                    $newImages[] = $newImage;
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
                'general' => $request->input('general', []),
                'security' => $request->input('security', []),
                'amenities' => $request->input('amenities', []),
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

    // ```````````````````m_facility`````````````````````````````````
    public function indexFacility(Request $request)
    {
        $query = PropertyFacility::with(['createdBy', 'updatedBy'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('facility', 'like', '%' . $request->search . '%');
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->category, function ($q) use ($request) {
                $q->where('category', $request->category);
            });

        $perPage = $request->per_page ?? 8;
        $facilities = $perPage === 'all'
            ? $query->get()
            : $query->paginate($perPage);

        $categories = PropertyFacility::distinct()->pluck('category');

        return view('pages.Properties.Facility_properties.index', compact('facilities', 'categories'));
    }

    public function storeFacility(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'facility' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:general,security,amenities',
            'status' => 'required|boolean', // Changed to boolean
        ]);

        try {
            // Create a new facility record
            $facility = PropertyFacility::create([
                'facility' => $validatedData['facility'],
                'description' => $validatedData['description'] ?? null,
                'category' => $validatedData['category'],
                'status' => $validatedData['status'] ? 1 : 0, // Convert to 1/0
                'created_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Facility created successfully',
                'data' => $facility,
                'redirect_url' => route('facilityProperty.index')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create facility',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateFacility(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'facility' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:general,security,amenities',
            'status' => 'required|boolean'
        ]);

        try {
            // Ambil data facility berdasarkan ID
            $facility = PropertyFacility::find($id);

            if (!$facility) {
                return response()->json([
                    'success' => false,
                    'message' => 'Facility not found'
                ], 404);
            }

            // Update data facility
            $facility->update([
                'facility' => $validatedData['facility'],
                'description' => $validatedData['description'] ?? null,
                'category' => $validatedData['category'],
                'status' => $validatedData['status'] ? 1 : 0,
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Facility updated successfully',
                'data' => $facility,
                'redirect_url' => route('facilityProperty.index')
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update facility',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
