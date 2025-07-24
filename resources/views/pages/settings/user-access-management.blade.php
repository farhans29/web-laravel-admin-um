<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-5xl mx-auto">

        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Menu Access Management</h2>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                <label for="userSelect" class="text-sm font-medium text-gray-700">Select User:</label>
                <select id="userSelect"
                    class="block w-full sm:w-64 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    <option value="">-- Select User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div
                class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-4 py-3 sm:px-6 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Menu Permissions</h3>
                <div class="flex gap-2 mt-2 sm:mt-0">
                    <button id="updateAccess"
                        class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                    <button id="cancelAccess"
                        class="px-3 py-1.5 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 hidden">
                        Cancel
                    </button>
                </div>
            </div>

            <div id="permissionsContainer" class="px-6 py-4 hidden">
                <div class="flex items-center mb-2">
                    <label for="mainMenuSelect" class="text-xs font-medium text-gray-500 mr-2">Main Menu:</label>
                    <select id="mainMenuSelect" class="border border-gray-300 rounded-md text-xs px-2 py-1 w-64">
                        <option value="">Select All</option>
                        @foreach ($sidebarItems->whereNull('parent_id') as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->username }}</option>
                        @endforeach
                    </select>
                </div>
                <table class="min-w-full max-w-2xl border border-gray-300 divide-y divide-gray-300 mt-4">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                Access</th>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                Main Menu</th>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                Sub Menu</th>
                            <th
                                class="border border-gray-300 px-2 py-3 text-center text-xs font-medium text-gray-500 uppercase w-1/12">
                                List Menu</th>
                        </tr>
                    </thead>
                    <tbody id="permissionsTableBody">
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
                            @endphp

                            <tr class="odd:bg-gray-100 menu-item" data-main-menu="{{ $mainMenuId }}"
                                data-sub-menu="{{ $subMenuId }}" data-is-main="{{ $isMainMenu ? 'true' : 'false' }}"
                                data-is-sub="{{ $isSubMenu ? 'true' : 'false' }}"
                                data-is-list="{{ $isListMenu ? 'true' : 'false' }}">
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 text-center w-1/12">
                                    <input type="checkbox" class="form-checkbox" value="{{ $item->id }}">
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 w-1/12">
                                    @if ($isMainMenu)
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 w-1/12">
                                    @if ($isSubMenu)
                                        {{ $item->name }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-sm text-gray-900 w-1/12">
                                    @if ($isListMenu)
                                        {{ $item->name }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const userSelect = document.getElementById('userSelect');
        const updateAccessButton = document.getElementById('updateAccess');
        const cancelAccessButton = document.getElementById('cancelAccess');
        const permissionsContainer = document.getElementById('permissionsContainer');

        userSelect.addEventListener('change', function() {
            const userId = this.value;

            if (userId) {
                userSelect.disabled = true;
                cancelAccessButton.classList.remove('hidden');

                fetch(`/user-access/${userId}/permissions`)
                    .then(response => response.json())
                    .then(data => {
                        permissionsContainer.classList.remove('hidden');
                        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                            checkbox.checked = data.permissions.includes(parseInt(checkbox.value));
                        });
                    });
            } else {
                userSelect.disabled = false;
                cancelAccessButton.classList.add('hidden');
                permissionsContainer.classList.add('hidden');
            }
        });

        updateAccessButton.addEventListener('click', function() {
            const userId = userSelect.value;
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const selectedPermissions = [];

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedPermissions.push(checkbox.value);
                }
            });

            fetch(`/user-access/${userId}/update`, {
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
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        backgroundColor: "#4CAF50",
                        stopOnFocus: true,
                    }).showToast();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: "Gagal memperbarui akses!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "left",
                        backgroundColor: "#FF5733",
                    }).showToast();
                });
        });

        cancelAccessButton.addEventListener('click', function() {
            userSelect.disabled = false;
            cancelAccessButton.classList.add('hidden');
            permissionsContainer.classList.add('hidden');
            userSelect.value = '';
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });

        document.getElementById('mainMenuSelect').addEventListener('change', function() {
            const selectedMainMenu = this.value;
            document.querySelectorAll('.menu-item').forEach(row => {
                const menuGroup = row.getAttribute('data-main-menu');
                row.style.display = (selectedMainMenu === "" || menuGroup === selectedMainMenu) ? "" :
                    "none";
            });
        });

        // Enhanced checkbox logic
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const row = this.closest('tr');
                const isMainMenu = row.getAttribute('data-is-main') === 'true';
                const isSubMenu = row.getAttribute('data-is-sub') === 'true';
                const isListMenu = row.getAttribute('data-is-list') === 'true';
                const menuId = this.value;
                const isChecked = this.checked;

                if (isMainMenu) {
                    // When main menu is checked/unchecked, update all its children
                    const mainMenuId = row.getAttribute('data-main-menu');

                    // Update all sub menus under this main menu
                    document.querySelectorAll(
                        `.menu-item[data-main-menu="${mainMenuId}"][data-is-sub="true"] input[type="checkbox"]`
                    ).forEach(subMenuCheckbox => {
                        subMenuCheckbox.checked = isChecked;

                        // For each sub menu, update its list menus
                        const subMenuRow = subMenuCheckbox.closest('tr');
                        const subMenuId = subMenuRow.getAttribute('data-sub-menu');

                        document.querySelectorAll(
                            `.menu-item[data-sub-menu="${subMenuId}"] input[type="checkbox"]`
                        ).forEach(listCheckbox => {
                            listCheckbox.checked = isChecked;
                        });
                    });
                } else if (isSubMenu) {
                    const subMenuId = row.getAttribute('data-sub-menu');
                    const mainMenuId = row.getAttribute('data-main-menu');

                    // Update all list menus under this sub menu
                    document.querySelectorAll(
                        `.menu-item[data-sub-menu="${subMenuId}"] input[type="checkbox"]`
                    ).forEach(listCheckbox => {
                        listCheckbox.checked = isChecked;
                    });

                    // Update main menu status based on all sub menus
                    updateMainMenuStatus(mainMenuId);
                } else if (isListMenu) {
                    const subMenuId = row.getAttribute('data-sub-menu');
                    const mainMenuId = row.getAttribute('data-main-menu');

                    // Update sub menu status based on list menus
                    updateSubMenuStatus(subMenuId);

                    // Update main menu status based on sub menus
                    updateMainMenuStatus(mainMenuId);
                }
            });
        });

        function updateSubMenuStatus(subMenuId) {
            const subMenuCheckbox = document.querySelector(
                `.menu-item[data-sub-menu="${subMenuId}"][data-is-sub="true"] input[type="checkbox"]`
            );

            if (subMenuCheckbox) {
                // Count checked list menus under this sub menu
                const listMenuCheckboxes = document.querySelectorAll(
                    `.menu-item[data-sub-menu="${subMenuId}"][data-is-list="true"] input[type="checkbox"]`
                );

                const checkedCount = Array.from(listMenuCheckboxes).filter(cb => cb.checked).length;

                // Sub menu should be checked if at least one list menu is checked
                subMenuCheckbox.checked = checkedCount > 0;
            }
        }

        function updateMainMenuStatus(mainMenuId) {
            const mainMenuCheckbox = document.querySelector(
                `.menu-item[data-main-menu="${mainMenuId}"][data-is-main="true"] input[type="checkbox"]`
            );

            if (mainMenuCheckbox) {
                // Get all sub menus under this main menu
                const subMenuCheckboxes = document.querySelectorAll(
                    `.menu-item[data-main-menu="${mainMenuId}"][data-is-sub="true"] input[type="checkbox"]`
                );

                const checkedCount = Array.from(subMenuCheckboxes).filter(cb => cb.checked).length;

                // Main menu should be checked if at least one sub menu is checked
                mainMenuCheckbox.checked = checkedCount > 0;
            }
        }
    </script>
</x-app-layout>
