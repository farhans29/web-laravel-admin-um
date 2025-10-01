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

        $users = User::with('role')
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

        $roles = Role::where('name', '!=', 'Admin')->get();

        return view('pages.settings.users-management-new', compact('users', 'roles', 'perPage'));
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
            'role'       => 'required|exists:roles,id',
        ], [
            'password.confirmed' => 'Password and Confirm Password must match.',
            'password.regex'     => 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 symbol.',
        ]);

        // Simpan data user baru
        User::create([
            'first_name' => $validatedData['first_name'],
            'last_name'  => $validatedData['last_name'],
            'username'   => $validatedData['username'],
            'email'      => $validatedData['email'],
            'password'   => Hash::make($validatedData['password']),
            'role_id'    => $validatedData['role'],
            'status'     => 1, // default active
            'created_by' => Auth::id(),
            'created_at' => Carbon::now(),
        ]);

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
        
        // Validasi input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|exists:roles,id',
            'status' => 'required|in:0,1',
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

        // Temukan user
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'User Not Found',
                    'details' => 'The requested user does not exist'
                ]
            ], 404);
        }

        // Prepare update data
        $updateData = [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'name' => $request->name, // kalau mau gabungan first + last bisa di-handle di sini
            'email' => $request->email,
            'role_id' => $request->role,
            'status' => $request->status,
            'updated_by' => Auth::id(),
            'updated_at' => now()
        ];

        // Update password jika ada
        if (!empty($request->password)) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Update user
        $user->update($updateData);

        return redirect()->back()->with('success', 'User Update Successfully!');
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
            ->where('role_id', $userId)
            ->pluck('permission_id')
            ->toArray();

        return response()->json(['permissions' => $userPermissions]);
    }

    public function update(Request $request, $userId)
    {
        // Clear existing permissions
        DB::table('role_permission')->where('role_id', $userId)->delete();

        // Insert new permissions
        foreach ($request->permissions as $permissionId) {
            DB::table('role_permission')->insert([
                'role_id' => $userId,
                'permission_id' => $permissionId,
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
}
