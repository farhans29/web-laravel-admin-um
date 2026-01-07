<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UserAccessLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 8); // Default to 10 if not specified

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::where('name', '!=', 'Admin')->get();

        return view('pages/settings/users-management', [
            'users' => $users,
            'roles' => $roles,
            'perPage' => $perPage
        ]);
    }

    public function show(User $user)
    {
        $user = Auth::user();
        $roles = Role::where('name', '!=', 'Admin')->get();
        return view('pages.settings.user-account-settings', compact('user', 'roles'));
    }

    public function indexNew(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 8);

        $users = User::with(['role', 'property'])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                        ->orWhere('username', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::where('name', '!=', 'Admin')->where('id', '!=', 165)->get();
        $properties = \App\Models\Property::all();

        return view('pages.settings.users-management-new', compact('users', 'roles', 'perPage', 'properties'));
    }


    public function checkEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $emailExists]);
    }


    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'username'   => 'required|string|max:255|unique:users,username',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'password'   => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // Updated regex to allow any symbol, not just specific ones
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'
            ],
            'role'       => 'required|exists:m_roles,id',
            'property_id' => 'nullable|exists:m_properties,idrec',
        ], [
            'password.confirmed' => 'Password and Confirm Password must match.',
            'password.regex'     => 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 symbol.',
        ]);

        // Simpan data user baru
        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name'  => $validatedData['last_name'],
            'username'   => $validatedData['username'],
            'email'      => $validatedData['email'],
            'password'   => Hash::make($validatedData['password']),
            'is_admin'   => 1,
            'role_id'    => $validatedData['role'],
            'property_id' => $validatedData['property_id'] ?? null,
            'status'     => 1, // default active
            'created_by' => Auth::id(),
            'created_at' => Carbon::now(),
        ]);

        // Tambahkan permission ID 1 dan 2 untuk user baru
        $defaultPermissions = [1, 2];

        foreach ($defaultPermissions as $permissionId) {
            $existingPermission = DB::table('role_permission')
                ->where('user_id', $user->id)
                ->where('permission_id', $permissionId)
                ->exists();

            if (!$existingPermission) {
                DB::table('role_permission')->insert([
                    'user_id' => $user->id,
                    'permission_id' => $permissionId,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('users-newManagement')
            ->with('success', 'User successfully created.');
    }



    public function indexEdit(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::where('name', '!=', 'Admin')->get();

        $departments = DB::table('m_department')->where('pid', 0)->get();

        return view('pages.settings.users-management-edit', compact('users', 'roles', 'perPage', 'departments'));
    }

    public function updateUsers(Request $request, $id)
    {
        // Temukan user
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User Not Found');
        }

        // Validasi input - sesuaikan dengan field yang ada di form
        $validationRules = [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'name'       => 'required|string|max:255|unique:users,username,' . $id,
            'email'      => 'required|email|unique:users,email,' . $id,
        ];

        // Jika ada field role dan status (untuk admin management)
        if ($request->has('role')) {
            $validationRules['role'] = 'required|exists:m_roles,id';
        }
        if ($request->has('status')) {
            $validationRules['status'] = 'required|in:0,1';
        }
        if ($request->has('property_id')) {
            $validationRules['property_id'] = 'nullable|exists:m_properties,idrec';
        }

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation Error: ' . $validator->errors()->first());
        }

        // Prepare update data
        $updateData = [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'username'   => $request->name,
            'email'      => $request->email,
            'updated_by' => Auth::id(),
            'updated_at' => now()
        ];

        // Update role jika ada (untuk admin management)
        if ($request->has('role')) {
            $updateData['role_id'] = $request->role;
        }

        // Update property_id jika ada
        if ($request->has('property_id')) {
            $updateData['property_id'] = $request->property_id ?: null;
        }

        // Update status jika ada (untuk admin management)
        if ($request->has('status')) {
            $updateData['status'] = $request->status;
        }

        // Update password jika ada
        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($updateData);

        return redirect()->route('users-newManagement')->with('success', 'User Updated Successfully!');
    }



    public function indexDelete(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Default to 10 if not specified

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhereHas('role', function ($roleQuery) use ($search) {
                            $roleQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->paginate($perPage)
            ->appends([
                'search' => $search,
                'per_page' => $perPage
            ]);

        $roles = Role::where('name', '!=', 'Admin')->get();

        return view('pages/settings/users-management-delete', [
            'users' => $users,
            'roles' => $roles,
            'perPage' => $perPage
        ]);
    }

    public function deactivateUser($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'status' => 'Inactive',
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'User Deactivated',
                    'details' => 'User has been deactivated successfully'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Error',
                    'details' => 'Failed to deactivate user'
                ],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function indexUserAccessManagement()
    {
        $users = User::all();
        $permissions = Permission::all();
        $sidebarItems = DB::table('sidebar_items')
            ->select('id', 'name', 'parent_id', 'route')
            ->get();

        return view(
            'pages/settings/user-access-management',
            compact('users', 'permissions', 'sidebarItems')
        );
    }

    public function getUserPermissions($userId)
    {
        $userPermissions = DB::table('role_permission')
            ->where('user_id', $userId)
            ->pluck('permission_id')
            ->toArray();

        return response()->json(['permissions' => $userPermissions]);
    }

    public function update(Request $request, $userId)
    {
        // Clear existing permissions
        DB::table('role_permission')->where('user_id', $userId)->delete();

        // Insert new permissions
        foreach ($request->permissions as $permissionId) {
            DB::table('role_permission')->insert([
                'user_id' => $userId,
                'permission_id' => $permissionId,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);
        }

        return response()->json(['message' => 'Permissions updated successfully!']);
    }

    public function accountGetData()
    {
        $users = User::select(['id', 'name', 'email', 'role'])->get();

        return response()->json($users);
    }

    public function userGetData()
    {
        $users = User::select(['id', 'name', 'email', 'role'])->get();

        return response()->json($users);
    }

    public function usergetMainMenus()
    {
        $mainMenus = Menu::select('id', 'header_menu')->distinct()->get();
        return response()->json($mainMenus);
    }

    // Method to get all menus
    public function getData(Request $request)
    {
        // Fetch all menus from m_menus
        $menus = Menu::whereNotNull('header_menu')
            ->whereNotNull('sub_menu')
            ->whereNotNull('menu')
            ->whereNotNull('kode')
            ->get();

        return response()->json($menus);
    }

    // Method to check user access for a specific menu
    public function getUserAccess(Request $request)
    {
        $userId = $request->query('user_id');
        $menuId = $request->query('menu_id');

        // Check if the user has access to the specified menu
        $hasAccess = UserAccessLevel::where('iduser', $userId)
            ->where('kode_menu', $menuId)
            ->exists();

        return response()->json(['hasAccess' => $hasAccess]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|boolean'
            ]);

            $user = User::findOrFail($id);
            $user->update([
                'status' => $request->status,
                'updated_at' => now(),
                'updated_by' => Auth::id(),
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    /**
     * Display master role management page
     */
    public function indexMasterRole(Request $request)
    {
        $perPage = $request->input('per_page', 8);

        // Get only users with is_admin = 1
        if ($perPage === 'all') {
            $adminUsers = User::with('role')
                ->where('is_admin', 1)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $adminUsers = User::with('role')
                ->where('is_admin', 1)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->appends(['per_page' => $perPage]);
        }

        $roles = Role::all();

        // Get sidebar items for permissions
        $sidebarItems = DB::table('sidebar_items')
            ->select('id', 'name', 'parent_id', 'route')
            ->get();

        return view('pages.settings.master-role-management', compact('adminUsers', 'roles', 'sidebarItems', 'perPage'));
    }

    /**
     * Update user role
     */
    public function updateMasterRole(Request $request, $userId)
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:m_roles,id'
            ]);

            $user = User::findOrFail($userId);

            // Check if user is admin
            if ($user->is_admin != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not an admin user'
                ], 403);
            }

            $user->update([
                'role_id' => $request->role_id,
                'updated_by' => Auth::id(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user permissions for master role management
     */
    public function getMasterRolePermissions($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Check if user is admin
            if ($user->is_admin != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not an admin user',
                    'permissions' => []
                ], 403);
            }

            $userPermissions = DB::table('role_permission')
                ->where('user_id', $userId)
                ->pluck('permission_id')
                ->toArray();

            return response()->json([
                'success' => true,
                'permissions' => $userPermissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch permissions',
                'permissions' => []
            ], 500);
        }
    }

    /**
     * Update user permissions for master role management
     */
    public function updateMasterRolePermissions(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Check if user is admin
            if ($user->is_admin != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not an admin user'
                ], 403);
            }

            // Clear existing permissions
            DB::table('role_permission')->where('user_id', $userId)->delete();

            // Insert new permissions
            if ($request->has('permissions') && is_array($request->permissions)) {
                foreach ($request->permissions as $permissionId) {
                    DB::table('role_permission')->insert([
                        'user_id' => $userId,
                        'permission_id' => $permissionId,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Access rights updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update permissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new role
     */
    public function createRole(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:m_roles,name'
            ], [
                'name.required' => 'Role name is required',
                'name.unique' => 'Role name already exists'
            ]);

            $role = Role::create([
                'name' => $request->name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully!',
                'role' => $role
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role: ' . $e->getMessage()
            ], 500);
        }
    }
}
