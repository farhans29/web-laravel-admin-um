<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between item-start gap-4 mb-8">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Master Role Management
                </h1>
                <p class="text-sm text-gray-600 mt-2">Kelola role untuk setiap user admin</p>
            </div>
            <div>
                <button onclick="openNewRoleModal()"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-150 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    New Role
                </button>
            </div>
        </div>

        <!-- Role Assignment Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div
                class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Daftar User Admin</h3>
                </div>
                <div class="flex items-center gap-2">
                    <label for="perPageSelect" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                        Show:
                    </label>
                    <select id="perPageSelect" onchange="changePerPage(this.value)"
                        class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-24 md:w-32 lg:w-40">
                        <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                        <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>8</option>
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="all" {{ $perPage === 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
            </div>

            <!-- Users Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Assign Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Access Rights</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($adminUsers as $index => $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $perPage === 'all' ? $index + 1 : ($adminUsers->currentPage() - 1) * $adminUsers->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($user->first_name ?? ($user->name ?? 'U'), 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : $user->name }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $user->username ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($user->role)
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $user->role->name }}
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            No Role
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select name="role_id" data-user-id="{{ $user->id }}"
                                        class="role-select block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                        <option value="">-- Select Role --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="updateUserRole({{ $user->id }})"
                                        class="btn-update-role inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 mr-2">
                                        <i class="fas fa-save mr-2"></i>
                                        Update Role
                                    </button>
                                    <button
                                        onclick="manageAccessRights({{ $user->id }}, '{{ $user->first_name ?? $user->name }}')"
                                        class="btn-access-rights inline-flex items-center px-3 py-2 border border-blue-600 text-sm leading-4 font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                        <i class="fas fa-key mr-2"></i>
                                        Access Rights
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-4xl mb-4 block text-gray-300"></i>
                                    <p class="text-lg font-medium">No Admin Users Found</p>
                                    <p class="text-sm">Users with is_admin = 1 will appear here</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($perPage !== 'all' && $adminUsers->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="ml-4">
                        {{ $adminUsers->links() }}
                    </div>
                </div>
            @endif

        </div>

        <!-- Information Card -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 mb-1">Information</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>Pilih peran dari menu <i>dropdown</i> dan klik <b>"<i>Update Role</i>"</b> untuk
                            menetapkannya</b></li>
                        <li>Klik <b>"<i>Access Rights</i>"</b> untuk mengelola izin menu untuk setiap pengguna</li>
                        <li>Setiap pengguna hanya dapat memiliki satu peran yang ditetapkan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- New Role Modal -->
    <div id="newRoleModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-900">
                    Create New Role
                </h3>
                <button onclick="closeNewRoleModal()" class="text-gray-400 hover:text-gray-500 transition duration-150">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="newRoleForm" class="mt-4">
                <div class="mb-4">
                    <label for="roleName" class="block text-sm font-medium text-gray-700 mb-2">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="roleName" name="name" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        placeholder="Enter role name">
                    <p class="mt-1 text-xs text-gray-500">Enter a unique name for the role</p>
                </div>

                <div class="flex justify-end items-center gap-3 pt-4 border-t mt-4">
                    <button type="button" onclick="closeNewRoleModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors duration-150">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-150">
                        <i class="fas fa-save mr-2"></i>
                        Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Access Rights Modal -->
    <div id="accessRightsModal"
        class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-900">
                    Access Rights - <span id="modalUserName"></span>
                </h3>
                <button onclick="closeAccessRightsModal()"
                    class="text-gray-400 hover:text-gray-500 transition duration-150">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mt-4">
                <!-- Filter Section -->
                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center mb-3 sm:mb-0">
                        <i class="fas fa-filter text-blue-500 mr-2"></i>
                        <label for="modalMainMenuSelect" class="text-sm font-medium text-gray-700 mr-2">Filter by Main
                            Menu:</label>
                        <select id="modalMainMenuSelect"
                            class="select-custom border border-gray-300 rounded-lg text-sm px-3 py-2 w-full sm:w-64">
                            <option value="">Show All Menus</option>
                            @foreach ($sidebarItems->whereNull('parent_id') as $menu)
                                <option value="{{ $menu->id }}">{{ $menu->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 mr-2">Quick Actions:</span>
                        <button id="modalSelectAll" class="text-xs text-blue-600 hover:text-blue-800 mr-3">Select
                            All</button>
                        <button id="modalDeselectAll" class="text-xs text-gray-600 hover:text-gray-800">Deselect
                            All</button>
                    </div>
                </div>

                <!-- Permissions Table -->
                <div class="overflow-x-auto rounded-lg border border-gray-200 max-h-96">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    Access</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Menu Detail</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="modalPermissionsTableBody">
                            @foreach ($sidebarItems as $item)
                                @php
                                    $isMainMenu = is_null($item->parent_id);
                                    $isSubMenu = !$isMainMenu && is_null($item->route);
                                    $isListMenu = !$isMainMenu && !is_null($item->route);

                                    $mainMenuId = $isMainMenu
                                        ? $item->id
                                        : ($isSubMenu
                                            ? $item->parent_id
                                            : $sidebarItems->firstWhere('id', $item->parent_id)->parent_id ??
                                                $item->parent_id);

                                    $subMenuId = $isSubMenu ? $item->id : ($isListMenu ? $item->parent_id : null);

                                    $icon = 'fas fa-folder';
                                    $color = 'text-blue-500';
                                    $typeClass = 'bg-blue-100 text-blue-800';
                                    $typeText = 'Main Menu';

                                    if ($isSubMenu) {
                                        $icon = 'fas fa-folder-open';
                                        $color = 'text-purple-500';
                                        $typeClass = 'bg-purple-100 text-purple-800';
                                        $typeText = 'Sub Menu';
                                    } elseif ($isListMenu) {
                                        $icon = 'fas fa-list';
                                        $color = 'text-green-500';
                                        $typeClass = 'bg-green-100 text-green-800';
                                        $typeText = 'List Menu';
                                    }

                                    $indentClass = '';
                                    if ($isSubMenu) {
                                        $indentClass = 'ml-6';
                                    } elseif ($isListMenu) {
                                        $indentClass = 'ml-12';
                                    }
                                @endphp

                                <tr class="menu-item group hover:bg-gray-50 transition-colors duration-150"
                                    data-main-menu="{{ $mainMenuId }}" data-sub-menu="{{ $subMenuId }}"
                                    data-is-main="{{ $isMainMenu ? 'true' : 'false' }}"
                                    data-is-sub="{{ $isSubMenu ? 'true' : 'false' }}"
                                    data-is-list="{{ $isListMenu ? 'true' : 'false' }}">
                                    <td class="px-4 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" class="modal-checkbox-round"
                                            value="{{ $item->id }}">
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <div class="flex items-center {{ $indentClass }}">
                                            <i class="{{ $icon }} {{ $color }} mr-3"></i>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->name }}
                                                </div>
                                                @if ($item->route)
                                                    <div class="text-xs text-gray-500">Route: {{ $item->route }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-medium {{ $typeClass }} rounded-full">{{ $typeText }}</span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="permission-badge badge-inactive">No Access</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <i class="fas fa-info-circle text-blue-400 mr-2"></i>
                    <span>Selecting a Main Menu will automatically select all Sub Menus and List Menus under it</span>
                </div>
            </div>

            <div class="flex justify-end items-center gap-3 pt-4 border-t mt-4">
                <button onclick="closeAccessRightsModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors duration-150">
                    Cancel
                </button>
                <button onclick="saveAccessRights()"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-150">
                    <i class="fas fa-save mr-2"></i>
                    Save Access Rights
                </button>
            </div>
        </div>
    </div>

    <style>
        .role-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px;
            padding-right: 2.5rem;
        }

        .select-custom {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px;
        }

        .btn-update-role:hover,
        .btn-access-rights:hover {
            transform: translateY(-1px);
        }

        .modal-checkbox-round {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #d1d5db;
            appearance: none;
            -webkit-appearance: none;
            outline: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
            background-color: white;
        }

        .modal-checkbox-round:hover {
            border-color: #3b82f6;
        }

        .modal-checkbox-round:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .modal-checkbox-round:checked:after {
            content: '';
            position: absolute;
            left: 5px;
            top: 2px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .permission-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-active {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .badge-inactive {
            background-color: #f3f4f6;
            color: #6b7280;
        }
    </style>

    <script>
        let currentUserId = null;

        // Pagination per page change handler
        function changePerPage(perPage) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        }

        // New Role Modal Functions
        function openNewRoleModal() {
            document.getElementById('newRoleModal').classList.remove('hidden');
            document.getElementById('roleName').value = '';
        }

        function closeNewRoleModal() {
            document.getElementById('newRoleModal').classList.add('hidden');
            document.getElementById('roleName').value = '';
        }

        // Handle New Role Form Submission
        document.getElementById('newRoleForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const roleName = document.getElementById('roleName').value.trim();

            if (!roleName) {
                Toastify({
                    text: "Role name is required!",
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "left",
                    style: {
                        background: "#FF5733",
                    },
                }).showToast();
                return;
            }

            const submitButton = e.target.querySelector('button[type="submit"]');
            const originalContent = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';

            fetch('/master-role/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: roleName
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            close: true,
                            gravity: "bottom",
                            position: "left",
                            style: {
                                background: "#4CAF50",
                            },
                        }).showToast();

                        closeNewRoleModal();

                        // Reload page to show new role
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to create role');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: error.message || "Failed to create role!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        style: {
                            background: "#FF5733",
                        },
                    }).showToast();

                    submitButton.disabled = false;
                    submitButton.innerHTML = originalContent;
                });
        });

        function updateUserRole(userId) {
            const selectElement = document.querySelector(`select[data-user-id="${userId}"]`);
            const roleId = selectElement.value;

            if (!roleId) {
                Toastify({
                    text: "Please select a role first!",
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "left",
                    style: {
                        background: "#FF5733",
                    },
                }).showToast();
                return;
            }

            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';

            fetch(`/master-role/update/${userId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        role_id: roleId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Toastify({
                            text: data.message,
                            duration: 3000,
                            close: true,
                            gravity: "bottom",
                            position: "left",
                            style: {
                                background: "#4CAF50",
                            },
                        }).showToast();

                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Failed to update role');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: error.message || "Failed to update role!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        style: {
                            background: "#FF5733",
                        },
                    }).showToast();

                    button.disabled = false;
                    button.innerHTML = originalContent;
                });
        }

        function manageAccessRights(userId, userName) {
            currentUserId = userId;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('accessRightsModal').classList.remove('hidden');

            // Fetch user permissions
            fetch(`/master-role/permissions/${userId}`)
                .then(response => response.json())
                .then(data => {
                    const userPermissions = Array.isArray(data.permissions) ? data.permissions : [];

                    document.querySelectorAll('.modal-checkbox-round').forEach(checkbox => {
                        checkbox.checked = userPermissions.includes(parseInt(checkbox.value));
                        updateModalBadgeStatus(checkbox);
                    });

                    updateAllModalMenuStatuses();
                })
                .catch(error => {
                    console.error('Error fetching permissions:', error);
                    Toastify({
                        text: "Failed to load permissions!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        style: {
                            background: "#FF5733",
                        },
                    }).showToast();
                });
        }

        function closeAccessRightsModal() {
            document.getElementById('accessRightsModal').classList.add('hidden');
            currentUserId = null;
        }

        function saveAccessRights() {
            if (!currentUserId) return;

            const checkboxes = document.querySelectorAll('.modal-checkbox-round');
            const selectedPermissions = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedPermissions.push(checkbox.value);
                }
            });

            fetch(`/master-role/update-permissions/${currentUserId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        permissions: selectedPermissions
                    })
                })
                .then(response => response.json())
                .then(data => {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        style: {
                            background: "#4CAF50",
                        },
                    }).showToast();

                    closeAccessRightsModal();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: "Failed to update permissions!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        style: {
                            background: "#FF5733",
                        },
                    }).showToast();
                });
        }

        // Modal filter and selection handlers
        document.getElementById('modalMainMenuSelect').addEventListener('change', function() {
            const selectedMainMenu = this.value;
            document.querySelectorAll('#modalPermissionsTableBody .menu-item').forEach(row => {
                const menuGroup = row.getAttribute('data-main-menu');
                row.style.display = (selectedMainMenu === "" || menuGroup === selectedMainMenu) ? "" :
                    "none";
            });
        });

        document.getElementById('modalSelectAll').addEventListener('click', function() {
            const visibleRows = Array.from(document.querySelectorAll('#modalPermissionsTableBody .menu-item'))
                .filter(row => row.style.display !== 'none');

            visibleRows.forEach(row => {
                const checkbox = row.querySelector('.modal-checkbox-round');
                if (checkbox) {
                    checkbox.checked = true;
                    updateModalBadgeStatus(checkbox);
                }
            });

            updateAllModalMenuStatuses();
        });

        document.getElementById('modalDeselectAll').addEventListener('click', function() {
            const visibleRows = Array.from(document.querySelectorAll('#modalPermissionsTableBody .menu-item'))
                .filter(row => row.style.display !== 'none');

            visibleRows.forEach(row => {
                const checkbox = row.querySelector('.modal-checkbox-round');
                if (checkbox) {
                    checkbox.checked = false;
                    updateModalBadgeStatus(checkbox);
                }
            });

            updateAllModalMenuStatuses();
        });

        // Checkbox change handlers
        document.querySelectorAll('.modal-checkbox-round').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateModalBadgeStatus(this);

                const row = this.closest('tr');
                const isMainMenu = row.getAttribute('data-is-main') === 'true';
                const isSubMenu = row.getAttribute('data-is-sub') === 'true';
                const isChecked = this.checked;

                if (isMainMenu) {
                    const mainMenuId = row.getAttribute('data-main-menu');
                    document.querySelectorAll(
                        `#modalPermissionsTableBody .menu-item[data-main-menu="${mainMenuId}"]:not([data-is-main="true"]) .modal-checkbox-round`
                    ).forEach(childCheckbox => {
                        childCheckbox.checked = isChecked;
                        updateModalBadgeStatus(childCheckbox);
                    });
                } else if (isSubMenu) {
                    const mainMenuId = row.getAttribute('data-main-menu');
                    updateModalMainMenuStatus(mainMenuId);

                    const subMenuId = row.getAttribute('data-sub-menu');
                    document.querySelectorAll(
                        `#modalPermissionsTableBody .menu-item[data-sub-menu="${subMenuId}"] .modal-checkbox-round`
                    ).forEach(listCheckbox => {
                        listCheckbox.checked = isChecked;
                        updateModalBadgeStatus(listCheckbox);
                    });
                } else {
                    const mainMenuId = row.getAttribute('data-main-menu');
                    updateModalMainMenuStatus(mainMenuId);
                }
            });
        });

        function updateModalBadgeStatus(checkbox) {
            const row = checkbox.closest('tr');
            const badge = row.querySelector('.permission-badge');

            if (checkbox.checked) {
                badge.textContent = 'Access Granted';
                badge.classList.remove('badge-inactive');
                badge.classList.add('badge-active');
            } else {
                badge.textContent = 'No Access';
                badge.classList.remove('badge-active');
                badge.classList.add('badge-inactive');
            }
        }

        function updateAllModalMenuStatuses() {
            const mainMenuIds = new Set();
            document.querySelectorAll('#modalPermissionsTableBody .menu-item[data-is-main="true"]').forEach(row => {
                mainMenuIds.add(row.getAttribute('data-main-menu'));
            });

            mainMenuIds.forEach(mainMenuId => {
                updateModalMainMenuStatus(mainMenuId);
            });
        }

        function updateModalMainMenuStatus(mainMenuId) {
            const mainMenuCheckbox = document.querySelector(
                `#modalPermissionsTableBody .menu-item[data-main-menu="${mainMenuId}"][data-is-main="true"] .modal-checkbox-round`
            );

            if (mainMenuCheckbox) {
                const childCheckboxes = document.querySelectorAll(
                    `#modalPermissionsTableBody .menu-item[data-main-menu="${mainMenuId}"]:not([data-is-main="true"]) .modal-checkbox-round`
                );

                const checkedCount = Array.from(childCheckboxes).filter(cb => cb.checked).length;
                mainMenuCheckbox.checked = checkedCount > 0;

                updateModalBadgeStatus(mainMenuCheckbox);
            }
        }
    </script>
</x-app-layout>
