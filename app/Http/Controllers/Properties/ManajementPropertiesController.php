<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class ManajementPropertiesController extends Controller
{
    public function index()
    {
        $properties = Property::orderBy('created_at', 'desc')->paginate(5);
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
}
