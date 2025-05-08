<?php

namespace App\Http\Controllers\Master\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\MVendor;

class VendorController extends Controller
{
    public function indexVendor(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MVendor::query();

        if ($search) {
            $query->where('vendor_type', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('npwp_id', 'like', "%{$search}%");
        }

        $vendors = $query->paginate($perPage)->appends($request->all());

        return view('pages.master.m_vendor.index_list', compact('vendors', 'perPage'));
    }

    public function indexNewVendor(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MVendor::query();

        if ($search) {
            $query->where('vendor_type', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('npwp_id', 'like', "%{$search}%");
        }

        $vendors = $query->paginate($perPage)->appends($request->all());

        return view('pages.master.m_vendor.index_new', compact('vendors', 'perPage'));
    }

    public function storeVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name'     => 'required|string|max:255',
            'vendor_department' => 'required|string|max:255',
            'address'         => 'required|string',
            'city'            => 'required|string|max:255',
            'country'         => 'required|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'bank_acc_num'    => 'required|string|max:255',
            'bank_name'       => 'required|string|max:255',
            'bank_acc_name'   => 'required|string|max:255',
            'zip_code'        => 'required|string|max:20',
            'term_of_payment' => 'required|integer',
            'npwp_id'         => 'nullable|string|max:255',
            'npwp_address'    => 'nullable|string',
            'npwp_city'       => 'nullable|string|max:255',
            'npwp_country'    => 'nullable|string|max:255',
            'npwp_zipcode'    => 'nullable|string|max:20',
            'files'           => 'required|array',
            'files.*'         => 'file|mimes:pdf,jpeg,jpg|max:25600', // 25MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Mengambil file dari request
        $files = $request->file('files');
        $fileNames = [];
        $base64Data = [];

        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
            $fileNames[] = $fileName;

            $fileData = file_get_contents($file->getRealPath());
            $base64EncodedData = base64_encode($fileData);
            $base64Data[] = $base64EncodedData;
        }

        $pdfJpgData = implode(',', $base64Data);
        try {
            // Create vendor
            $vendor = MVendor::create([
                'vendor_type'     => $request->vendor_department,
                'name'            => $request->vendor_name,
                'address'         => $request->address,
                'city'            => $request->city,
                'country'         => $request->country,
                'phone'           => $request->phone,
                'bank_acc_num'    => $request->bank_acc_num,
                'bank_name'       => $request->bank_name,
                'bank_acc_name'   => $request->bank_acc_name,
                'zip_code'        => $request->zip_code,
                'termin'          => $request->term_of_payment,
                'npwp_id'         => $request->npwp_id,
                'npwp_address'    => $request->npwp_address,
                'npwp_city'       => $request->npwp_city,
                'npwp_country'    => $request->npwp_country,
                'npwp_zipcode'    => $request->npwp_zipcode,
                'npwp_pdf'        => $pdfJpgData,
                'status'          => 'Active',
                'created_at'      => now(),
                'created_by'      => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Vendor added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to save vendor: ' . $e->getMessage())
                ->withInput();
        }
    }



    public function indexEditVendor(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MVendor::query();

        if ($search) {
            $query->where('vendor_type', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('npwp_id', 'like', "%{$search}%");
        }

        $vendors = $query->paginate($perPage)->appends($request->all());

        return view('pages.master.m_vendor.index_edit', compact('vendors', 'perPage'));
    }

    public function updateVendor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name'     => 'required|string|max:255',
            'vendor_department' => 'required|string|max:255',
            'address'         => 'required|string',
            'city'            => 'required|string|max:255',
            'country'         => 'required|string|max:255',
            'phone'           => 'nullable|string|max:50',
            'bank_acc_num'    => 'required|string|max:255',
            'bank_name'       => 'required|string|max:255',
            'bank_acc_name'   => 'required|string|max:255',
            'zip_code'        => 'required|string|max:20',
            'term_of_payment' => 'required|integer',
            'npwp_id'         => 'nullable|string|max:255',
            'npwp_address'    => 'nullable|string',
            'npwp_city'       => 'nullable|string|max:255',
            'npwp_country'    => 'nullable|string|max:255',
            'npwp_zipcode'    => 'nullable|string|max:20',
            'files'           => 'sometimes|array',
            'files.*'         => 'sometimes|file|mimes:pdf,jpeg,jpg|max:25600', // 25MB
            'status'          => 'required|in:Active,Inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Find the vendor to update
            $vendor = MVendor::findOrFail($id);

            // Initialize variables for file handling
            $pdfJpgData = $vendor->npwp_pdf; // Keep existing files if no new ones are uploaded
            $fileNames = [];

            // Handle file uploads if present
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                $base64Data = [];

                foreach ($files as $file) {
                    $fileName = $file->getClientOriginalName();
                    $fileNames[] = $fileName;

                    $fileData = file_get_contents($file->getRealPath());
                    $base64EncodedData = base64_encode($fileData);
                    $base64Data[] = $base64EncodedData;
                }

                $pdfJpgData = implode(',', $base64Data);
            }

            // Update vendor
            $vendor->update([
                'vendor_type'     => $request->vendor_department,
                'name'            => $request->vendor_name,
                'address'         => $request->address,
                'city'            => $request->city,
                'country'         => $request->country,
                'phone'           => $request->phone,
                'bank_acc_num'    => $request->bank_acc_num,
                'bank_name'       => $request->bank_name,
                'bank_acc_name'   => $request->bank_acc_name,
                'zip_code'        => $request->zip_code,
                'termin'          => $request->term_of_payment,
                'npwp_id'         => $request->npwp_id,
                'npwp_address'    => $request->npwp_address,
                'npwp_city'       => $request->npwp_city,
                'npwp_country'    => $request->npwp_country,
                'npwp_zipcode'    => $request->npwp_zipcode,
                'npwp_pdf'        => $pdfJpgData,
                'status'          => $request->status,
                'updated_at'      => now(),
                'updated_by'     => Auth::id(),
            ]);

            return redirect()->back()->with('success', 'Vendor updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update vendor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function indexDeleteVendor(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MVendor::query();

        if ($search) {
            $query->where('vendor_type', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('city', 'like', "%{$search}%")
                ->orWhere('country', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('npwp_id', 'like', "%{$search}%");
        }

        $vendors = $query->paginate($perPage)->appends($request->all());

        return view('pages.master.m_vendor.index_delete', compact('vendors', 'perPage'));
    }

    public function softDeleteVendor($id)
    {
        try {
            $documentType = MVendor::findOrFail($id);
            $documentType->update([
                'status' => 'Inactive',
                'updated_by' => Auth::id(), 
                'updated_at' => now()
            ]);

            return redirect()->route('indexDelete.vendor')->with('success', 'Document type deactivated successfully');
        } catch (\Exception $e) {
            return redirect()->route('indexDelete.vendor')->with('error', 'Failed to deactivate document type');
        }
    }
}
