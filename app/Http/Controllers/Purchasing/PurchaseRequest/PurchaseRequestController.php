<?php

namespace App\Http\Controllers\Purchasing\PurchaseRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TInventoryAssetsRequest;
use App\Models\MCompany;
use App\Models\MInventoryAsset;
use App\Models\TPurchaseRequest;
use App\Models\TPurchaseRequestDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'pr_date');
        $sortDirection = $request->input('sort_direction', 'desc');

        $query = TInventoryAssetsRequest::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('idreqform', 'like', '%' . $search . '%')
                    ->orWhere('pr_title', 'like', '%' . $search . '%')
                    ->orWhere('applicant', 'like', '%' . $search . '%')
                    ->orWhere('department', 'like', '%' . $search . '%')
                    ->orWhere('division', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy($sortField, $sortDirection);

        $pRequest = $query->paginate($perPage);

        return view('pages/purchasing/purchaseRequest/index_list', compact('pRequest', 'perPage', 'search', 'sortField', 'sortDirection'));
    }

    public function indexNew(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'pr_date');
        $sortDirection = $request->input('sort_direction', 'desc');

        $query = TInventoryAssetsRequest::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('idreqform', 'like', '%' . $search . '%')
                    ->orWhere('pr_title', 'like', '%' . $search . '%')
                    ->orWhere('applicant', 'like', '%' . $search . '%')
                    ->orWhere('department', 'like', '%' . $search . '%')
                    ->orWhere('division', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy($sortField, $sortDirection);

        $pRequest = $query->paginate($perPage);

        return view('pages/purchasing/purchaseRequest/index_new', compact('pRequest', 'perPage', 'search', 'sortField', 'sortDirection'));
    }

    public function formNew(Request $request)
    {
        // Fetch companies
        $companies = MCompany::select('id_company', 'name', 'address', 'city', 'npwp_city', 'country', 'zip_code')
            ->orderBy('name')
            ->paginate(2);

        // Fetch inventory items with pagination 5
        $inventoryItems = MInventoryAsset::select(
            'idassets as assets_code',
            'name as inventory_name',
            'unit',
            'hpp as price'
        )
            ->when($request->has('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where('idassets', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
            })
            ->paginate(5)
            ->withQueryString();

        // Handle AJAX requests
        if ($request->ajax()) {
            if ($request->has('inventory')) {
                return response()->json([
                    'html' => view('pages.purchasing.purchaseRequest.input.partials.inventory_table', compact('inventoryItems'))->render()
                ]);
            }

            return response()->json([
                'html' => view('pages.purchasing.purchaseRequest.input.partials.company_table', compact('companies'))->render()
            ]);
        }

        return view('pages.purchasing.purchaseRequest.input.formNew', compact('companies', 'inventoryItems'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            // Create main purchase request
            $pr = new TPurchaseRequest();
            $pr->idreqform = $request->idreqform;
            $pr->pr_title = $request->pr_title;
            $pr->pr_type = $request->pr_type;
            $pr->pr_date = $request->pr_date;
            $pr->rab_date = $request->rab_date . '-01';
            $pr->applicant = $request->applicant;
            $pr->company_id = $request->company_id;
            $pr->currency = $request->currency;
            $pr->payment_by = $request->payment_by;
            $pr->reqlevel = $request->reqlevel;
            $pr->delivery_date = $request->delivery_date;
            $pr->note = $request->note;
            $pr->delivery_address = $request->delivery_address;
            $pr->approvalstat = 'Pending';
            $pr->subtotal = $request->subtotal;
            $pr->total = $request->total;
            $pr->gtotal = $request->gtotal;
            $pr->prepared_by = Auth::user()->name;
            $pr->prepared_date = now();
            $pr->created_by = Auth::id();
            $pr->save();

            // Create purchase request details
            foreach ($request->details as $detail) {
                $prDetail = new TPurchaseRequestDetail();
                $prDetail->idreqform = $pr->idreqform;
                $prDetail->idpr = $pr->idrec;
                $prDetail->idassets = $detail['idassets'];
                $prDetail->name_detail = $detail['name_detail'];
                $prDetail->qty = $detail['qty'];
                $prDetail->unit = $detail['unit'];
                $prDetail->currency = $detail['currency'];
                $prDetail->price = $detail['price'];
                $prDetail->total = $detail['total'];
                $prDetail->remarks = $detail['remarks'];
                $prDetail->qtyBalance = $detail['qtyBalance'];
                $prDetail->balance = $detail['balance'];
                $prDetail->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'id' => $pr->idrec,
                'message' => 'Purchase request created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating purchase request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function indexEdit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'pr_date');
        $sortDirection = $request->input('sort_direction', 'desc');

        $query = TInventoryAssetsRequest::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('idreqform', 'like', '%' . $search . '%')
                    ->orWhere('pr_title', 'like', '%' . $search . '%')
                    ->orWhere('applicant', 'like', '%' . $search . '%')
                    ->orWhere('department', 'like', '%' . $search . '%')
                    ->orWhere('division', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy($sortField, $sortDirection);

        $pRequest = $query->paginate($perPage);

        return view('pages/purchasing/purchaseRequest/index_edit', compact('pRequest', 'perPage', 'search', 'sortField', 'sortDirection'));
    }

    public function indexDelete(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5);
        $sortField = $request->input('sort_field', 'pr_date');
        $sortDirection = $request->input('sort_direction', 'desc');

        $query = TInventoryAssetsRequest::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('idreqform', 'like', '%' . $search . '%')
                    ->orWhere('pr_title', 'like', '%' . $search . '%')
                    ->orWhere('applicant', 'like', '%' . $search . '%')
                    ->orWhere('department', 'like', '%' . $search . '%')
                    ->orWhere('division', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy($sortField, $sortDirection);

        $pRequest = $query->paginate($perPage);

        return view('pages/purchasing/purchaseRequest/index_delete', compact('pRequest', 'perPage', 'search', 'sortField', 'sortDirection'));
    }
}
