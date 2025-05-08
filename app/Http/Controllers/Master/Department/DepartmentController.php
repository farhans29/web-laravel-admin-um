<?php

namespace App\Http\Controllers\Master\Department;

use App\Http\Controllers\Controller;
use App\Models\MDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = MDepartment::with('parent')->where('pid', '!=', 0); // Only get sub-departments

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('parent', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $query->orderBy('pid', 'asc')->orderBy('name', 'asc');

        $subDepartments = $query->paginate($perPage);

        // Get all main departments for reference
        $mainDepartments = MDepartment::where('pid', 0)
            ->where('status', 'Active')
            ->get();


        return view('pages.master.m_department.index_list', compact('subDepartments', 'mainDepartments', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'createOption' => 'required|in:department,subdepartment',
            'departmentName' => 'required_if:createOption,department',
            'subDepartmentName' => 'required_if:createOption,subdepartment',
            'parentDepartment' => 'required_if:createOption,subdepartment|nullable|exists:m_department,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $department = new MDepartment();
        if ($request->createOption === 'department') {
            $department->name = $request->departmentName;
            $department->pid = 0;
        } else {
            $department->name = $request->subDepartmentName;
            $department->pid = $request->parentDepartment;
        }

        $department->status = 'Active';
        $department->save();

        return redirect()->back()->with('success', 'Department saved successfully.');
    }

    public function indexNew(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = MDepartment::with('parent')->where('pid', '!=', 0); // Only get sub-departments

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('parent', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $query->orderBy('pid', 'asc')->orderBy('name', 'asc');

        $subDepartments = $query->paginate($perPage);

        // Get all main departments for reference
        $mainDepartments = MDepartment::where('pid', 0)
            ->where('status', 'Active')
            ->get();


        return view('pages.master.m_department.index_new', compact('subDepartments', 'mainDepartments', 'search', 'perPage'));
    }

    public function updateDepartment(Request $request, $id)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'status' => 'sometimes|in:Active,Inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'toast' => [
                        'type' => 'error',
                        'message' => 'Validation Error',
                        'details' => $validator->errors()->first()
                    ],
                    'errors' => $validator->errors()
                ], 422);
            }

            // Temukan department
            $department = MDepartment::find($id);

            if (!$department) {
                return response()->json([
                    'success' => false,
                    'toast' => [
                        'type' => 'error',
                        'message' => 'Department Not Found',
                        'details' => 'The requested department does not exist'
                    ]
                ], 404);
            }

            // Update data
            $department->update([
                'name' => $request->name,
                'pid' => 0, // Pastikan tetap department utama
                'status' => $request->status ?? 'Active'
            ]);

            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'Department Updated',
                    'details' => 'Department has been updated successfully'
                ],
                'data' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Server Error',
                    'details' => 'An unexpected error occurred'
                ],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSubDepartment(Request $request, $id)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'pid' => 'required|exists:m_department,id',
                'status' => 'sometimes|in:Active,Inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'toast' => [
                        'type' => 'error',
                        'message' => 'Validation Error',
                        'details' => $validator->errors()->first()
                    ],
                    'errors' => $validator->errors()
                ], 422);
            }

            // Temukan sub-department
            $subDepartment = MDepartment::find($id);

            if (!$subDepartment) {
                return response()->json([
                    'success' => false,
                    'toast' => [
                        'type' => 'error',
                        'message' => 'Sub-Department Not Found',
                        'details' => 'The requested sub-department does not exist'
                    ]
                ], 404);
            }

            // Pastikan yang diupdate adalah sub-department (bukan department utama)
            if ($subDepartment->pid == 0) {
                return response()->json([
                    'success' => false,
                    'toast' => [
                        'type' => 'error',
                        'message' => 'Invalid Operation',
                        'details' => 'Cannot convert main department to sub-department'
                    ]
                ], 400);
            }

            // Update data
            $subDepartment->update([
                'name' => $request->name,
                'pid' => $request->pid,
                'status' => $request->status ?? 'Active'
            ]);

            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'Sub-Department Updated',
                    'details' => 'Sub-department has been updated successfully'
                ],
                'data' => $subDepartment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Server Error',
                    'details' => 'An unexpected error occurred'
                ],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function indexEdit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = MDepartment::with('parent')->where('pid', '!=', 0); // Only get sub-departments

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('parent', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $query->orderBy('pid', 'asc')->orderBy('name', 'asc');

        $subDepartments = $query->paginate($perPage);

        // Get all main departments for reference
        $mainDepartments = MDepartment::where('pid', 0)
            ->where('status', 'Active')
            ->get();


        return view('pages.master.m_department.index_edit', compact('subDepartments', 'mainDepartments', 'search', 'perPage'));
    }

    public function indexDelete(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = MDepartment::with('parent')->where('pid', '!=', 0); // Only get sub-departments

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('parent', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $query->orderBy('pid', 'asc')->orderBy('name', 'asc');

        $subDepartments = $query->paginate($perPage);

        // Get all main departments for reference
        $mainDepartments = MDepartment::where('pid', 0)
            ->where('status', 'Active')
            ->get();


        return view('pages.master.m_department.index_delete', compact('subDepartments', 'mainDepartments', 'search', 'perPage'));
    }

    public function softDelete($id)
    {
        try {
            $department = MDepartment::findOrFail($id);
            $department->status = 'Non Active';
            $department->updated_at = now();
            $department->save();

            return redirect()->route('index.department')
                ->with('success', 'Department has been deactivated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('index.department')
                ->with('error', 'Error deactivating department: ' . $e->getMessage());
        }
    }
}
