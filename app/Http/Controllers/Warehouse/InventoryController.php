<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\MBrand;
use App\Models\MInventoryAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $perPage = $request->input('per_page', 5);

        $query = MInventoryAsset::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_inventory', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('unit', 'like', '%' . $search . '%')
                    ->orWhere('price_list', 'like', '%' . $search . '%');
            });
        }

        $inventories = $query->paginate($perPage);

        return view('pages/warehouse/inventory/index_list', compact('inventories', 'perPage', 'search'));
    }

    public function indexNew(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);

        $query = MInventoryAsset::with(['brand', 'modelBrand']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_inventory', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('unit', 'like', '%' . $search . '%')
                    ->orWhere('price_list', 'like', '%' . $search . '%')
                    ->orWhereHas('brand', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('modelName', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $inventories = $query->paginate($perPage);
        $brands = MBrand::where('p_id_brand', 0)
            ->select('id_brand', 'name')
            ->get();

        return view('pages/warehouse/inventory/index_new', compact('inventories', 'perPage', 'brands'));
    }



    public function indexEdit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $query = MInventoryAsset::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_inventory', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('unit', 'like', '%' . $search . '%')
                    ->orWhere('price_list', 'like', '%' . $search . '%');
            });
        }

        $inventories = $query->paginate($perPage);

        return view('pages/warehouse/inventory/index_edit', compact('inventories', 'perPage'));
    }

    public function indexDelete(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $query = MInventoryAsset::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id_inventory', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('unit', 'like', '%' . $search . '%')
                    ->orWhere('price_list', 'like', '%' . $search . '%');
            });
        }

        $inventories = $query->paginate($perPage);

        return view('pages/warehouse/inventory/index_delete', compact('inventories', 'perPage'));
    }


    public function store(Request $request)
    {
        try {

            $msrp = preg_replace('/\D/', '', $request->msrp_input);

            MInventoryAsset::create([
                'id_inventory' => $request->id_inventory_input ?? Str::uuid(),
                'qty' => "1",
                'hpp' => "0",
                'automargin' => "0",
                'minsales' => "0",
                'category_2' => null,
                'currency' => "IDR",
                'last_purch' => "0",
                'ws_price' => "0",
                'plu' => "0",
                'id_supplier' => "0",
                'category' => $request->category_input,
                'name' => $request->name_input,
                'unit' => $request->unit_input,
                'brand' => $request->brand_input,
                'model' => $request->model_input,
                'variant' => $request->variant_input,
                'net_weight' => $request->nett_weight_input,
                'w_unit' => $request->weight_unit_input,
                'aktif_y_n' => "y",
                'price_list' => $msrp ?? null,
            ]);

            return redirect()->route('indexNew.inventory')->with('success', 'Inventory item added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function getModels($brand_id)
    {

        $models = MBrand::where('p_id_brand', $brand_id)->get();

        return response()->json($models);
    }

    public function destroy($id)
    {
        // Check if the user is authorized to delete the inventory item
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Find the inventory item by ID
        $inventory = MInventoryAsset::find($id);

        if (!$inventory) {
            return response()->json(['success' => false, 'message' => 'Inventory item not found'], 404);
        }

        // Delete the inventory item
        $inventory->delete();

        return response()->json(['success' => true, 'message' => 'Inventory item deleted successfully']);
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'category' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'variant' => 'nullable|string|max:255',
            'price_list' => 'nullable|string',
            'net_weight' => 'nullable|numeric',
            'w_unit' => 'nullable|string|max:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the inventory item by ID
        $inventory = MInventoryAsset::find($id);

        if (!$inventory) {
            return redirect()->back()->with('error', 'Inventory item not found.');
        }
        $price = str_replace('.', '', $request->input('price_list'));
        // Update the inventory item with the new data
        $inventory->category = $request->input('category');
        $inventory->name = $request->input('name');
        $inventory->unit = $request->input('unit');
        $inventory->brand = $request->input('brand');
        $inventory->model = $request->input('model');
        $inventory->variant = $request->input('variant');
        $inventory->price_list = $price;
        $inventory->net_weight = number_format($request->input('net_weight'), 4, '.', '');
        $inventory->w_unit = $request->input('weight_unit');
        $inventory->updated_at = now();

        // Save the changes to the database
        $inventory->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Inventory item updated successfully.');
    }
}
