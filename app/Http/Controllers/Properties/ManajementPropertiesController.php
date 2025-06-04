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
            'facilities' => 'nullable|array',
        ]);

        $imageBase64Arr = [];
        foreach ($request->file('property_images') as $file) {
            // Validate file type
            $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file->getMimeType(), $validTypes)) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid file type. Please upload a JPEG or PNG image.'
                    ], 400);
                }
                return back()->with('error', 'Invalid file type. Please upload a JPEG or PNG image.');
            }

            // Validate file size (5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'File is too large. Maximum size is 5MB.'
                    ], 400);
                }
                return back()->with('error', 'File is too large. Maximum size is 5MB.');
            }

            // Read file contents and convert to base64
            $fileContents = file_get_contents($file->getRealPath());
            $base64 = base64_encode($fileContents);
            $imageBase64Arr[] = $base64;
        }

        // Rest of your code remains the same...
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
            'amenities' => array_intersect($request->input('facilities', []), $amenities),
            'rules' => array_intersect($request->input('facilities', []), $rules)
        ];

        // Generate idrec baru
        $idrec = Property::max('idrec') + 1;

        // Generate slug
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

        return redirect()->route('properties.index')->with('success', 'Property created successfully!');
    }

    // public function uploadAttachment(Request $request, $id)
    // {
    //     try {
    //         $request->validate([
    //             'attachment_file' => 'required|file|mimes:jpg,jpeg,png|max:10240', // 10MB max
    //         ]);

    //         // Find the booking
    //         $booking = DB::table('t_transactions')
    //             ->where('idrec', $id)
    //             ->where('user_id', Auth::id())
    //             ->first();

    //         if (!$booking) {
    //             if ($request->wantsJson()) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Booking not found.'
    //                 ], 404);
    //             }
    //             return back()->with('error', 'Booking not found.');
    //         }

    //         // Handle file upload
    //         if ($request->hasFile('attachment_file') && $request->file('attachment_file')->isValid()) {
    //             $file = $request->file('attachment_file');

    //             // Validate file type
    //             $validTypes = ['image/jpeg', 'image/png'];
    //             if (!in_array($file->getMimeType(), $validTypes)) {
    //                 if ($request->wantsJson()) {
    //                     return response()->json([
    //                         'status' => 'error',
    //                         'message' => 'Invalid file type. Please upload a JPEG or PNG image.'
    //                     ], 400);
    //                 }
    //                 return back()->with('error', 'Invalid file type. Please upload a JPEG or PNG image.');
    //             }

    //             // Validate file size (10MB)
    //             if ($file->getSize() > 10 * 1024 * 1024) {
    //                 if ($request->wantsJson()) {
    //                     return response()->json([
    //                         'status' => 'error',
    //                         'message' => 'File is too large. Maximum size is 10MB.'
    //                     ], 400);
    //                 }
    //                 return back()->with('error', 'File is too large. Maximum size is 10MB.');
    //             }


    //             // Read file contents and convert to base64
    //             $fileContents = file_get_contents($file->getRealPath());
    //             $base64 = base64_encode($fileContents);

    //             // Update the booking with the new attachment
    //             $updated = DB::table('t_transactions')
    //                 ->where('idrec', $id)
    //                 ->update([
    //                     'attachment' => $base64,
    //                     'updated_at' => now(),
    //                 ]);

    //             if ($updated) {
    //                 if ($request->wantsJson()) {
    //                     return response()->json([
    //                         'status' => 'success',
    //                         'message' => 'Payment proof uploaded successfully.',
    //                         'data' => [
    //                             'booking_id' => $id,
    //                             'has_attachment' => true
    //                         ]
    //                     ]);
    //                 }
    //                 return back()->with('success', 'Payment proof uploaded successfully.');
    //             }


    //             if ($request->wantsJson()) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Failed to update booking with attachment.'
    //                 ], 500);
    //             }
    //             return back()->with('error', 'Failed to update booking with attachment.');
    //         } else {
    //             if ($request->wantsJson()) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'message' => 'Invalid file upload.'
    //                 ], 400);
    //             }
    //             return back()->with('error', 'Invalid file upload.');
    //         }
    //     } catch (\Exception $e) {
    //         \Log::error('Error uploading attachment: ' . $e->getMessage(), [
    //             'booking_id' => $id,
    //             'user_id' => Auth::id(),
    //             'exception' => $e
    //         ]);

    //         if ($request->wantsJson()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'An error occurred while uploading the file.',
    //                 'error' => config('app.debug') ? $e->getMessage() : null
    //             ], 500);
    //         }
    //         return back()->with('error', 'An error occurred while uploading the file. Please try again.');
    //     }
    // }

    public function update(Request $request, $id)
    {
        dd($request->all());
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
