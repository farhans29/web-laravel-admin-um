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
        dd($request->all());
        $validated = $request->validate([
            'property_name' => 'required',
            'property_type' => 'required',
            'province' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string',
            'village' => 'required|string',
            'postal_code' => 'nullable|string',
            'full_address' => 'required|string',
            
            'property_images' => 'required|array|min:1',
            'property_images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'facilities' => 'nullable|array',
        ]);

        // Handle upload gambar
        $imagePaths = [];
        foreach ($request->file('property_images') as $image) {
            $path = $image->store('public/properties');
            $imagePaths[] = str_replace('public/', '', $path);
        }

        // Generate data untuk model
        $property = new Property();
        $property->slug = Str::slug($request->property_name);
        $property->tags = $request->property_type;
        $property->name = $request->property_name;
        $property->province = $request->province;
        $property->city = $request->city;
        $property->subdistrict = $request->district;
        $property->village = $request->village;
        $property->postal_code = $request->postal_code;
        $property->address = $request->full_address;
        $property->location = $request->latitude . ',' . $request->longitude;
        $property->features = implode(',', $request->facilities ?? []);
        $property->image = implode(',', $imagePaths);
        $property->status = 'active';
        $property->created_by = Auth::id();
        $property->save();

        return response()->json(['success' => true]);
    }
}
