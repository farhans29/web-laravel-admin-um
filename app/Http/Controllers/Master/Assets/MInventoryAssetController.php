<?php

namespace App\Http\Controllers\Master\Assets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MInventoryAsset;
use App\Models\MDepartment;

class MInventoryAssetController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MInventoryAsset::with(['mainBrand', 'modelBrand']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('idassets', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('model_number', 'like', '%' . $search . '%')
                    ->orWhere('color', 'like', '%' . $search . '%')
                    ->orWhere('vendor_preference', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('sub_category', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('category2', 'like', '%' . $search . '%')
                    ->orWhere('plu', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('name', 'asc');

        $assets = $query->paginate($perPage);

        return view('pages.master.m_assets_code.index_list', compact('assets', 'search', 'perPage'));
    }

    public function indexCatalogue(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = MInventoryAsset::with(['mainBrand', 'modelBrand']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('idassets', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('model_number', 'like', '%' . $search . '%')
                    ->orWhere('color', 'like', '%' . $search . '%')
                    ->orWhere('vendor_preference', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('sub_category', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('category2', 'like', '%' . $search . '%')
                    ->orWhere('plu', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('name', 'asc');

        $assets = $query->paginate($perPage);

        return view('pages.master.m_assets_code.index_catalogue', compact('assets', 'search', 'perPage'));
    }

    public function indexNew(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MInventoryAsset::with(['mainBrand', 'modelBrand']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('idassets', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('model_number', 'like', '%' . $search . '%')
                    ->orWhere('color', 'like', '%' . $search . '%')
                    ->orWhere('vendor_preference', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('sub_category', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('category2', 'like', '%' . $search . '%')
                    ->orWhere('plu', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('name', 'asc');

        $assets = $query->paginate($perPage);

        $deps = MDepartment::getDepartments();
        $subDeps = MDepartment::getSubDepartments();

        return view('pages.master.m_assets_code.index_new', compact('assets', 'deps', 'subDeps', 'search', 'perPage'));
    }

    public function indexEdit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MInventoryAsset::with(['mainBrand', 'modelBrand']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('idassets', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('model_number', 'like', '%' . $search . '%')
                    ->orWhere('color', 'like', '%' . $search . '%')
                    ->orWhere('vendor_preference', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('sub_category', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('category2', 'like', '%' . $search . '%')
                    ->orWhere('plu', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('name', 'asc');

        $assets = $query->paginate($perPage);

        return view('pages.master.m_assets_code.index_edit', compact('assets', 'search', 'perPage'));
    }

    public function indexDelete(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MInventoryAsset::with(['mainBrand', 'modelBrand']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('idassets', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%')
                    ->orWhere('model_number', 'like', '%' . $search . '%')
                    ->orWhere('color', 'like', '%' . $search . '%')
                    ->orWhere('vendor_preference', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('sub_category', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhere('category2', 'like', '%' . $search . '%')
                    ->orWhere('plu', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('name', 'asc');

        $assets = $query->paginate($perPage);

        return view('pages.master.m_assets_code.index_delete', compact('assets', 'search', 'perPage'));
    }
}
