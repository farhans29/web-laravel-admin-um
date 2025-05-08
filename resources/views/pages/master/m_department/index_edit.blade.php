<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Department Management</h2>

        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Department List</h2>
                <div class="flex items-center">
                    <div class="relative">
                        <form method="GET" action="{{ route('indexEdit.department') }}">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search..."
                                class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-48">
                            <div class="absolute left-3 top-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Main Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sub Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($subDepartments as $index => $subDept)
                            <tr class="hover:bg-gray-100 odd:bg-gray-100 even:bg-white">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $subDepartments->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $mainDepartments->firstWhere('id', $subDept->pid)->name ?? '' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $subDept->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $subDept->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $subDept->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div x-data="editModal({{ json_encode($subDept) }}, {{ json_encode($mainDepartments) }})">
                                        <button
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded cursor-pointer"
                                            type="button" @click.prevent="openModal()">
                                            Edit
                                        </button>

                                        <!-- Modal backdrop -->
                                        <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity"
                                            x-show="isOpen" x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-out duration-100"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                            aria-hidden="true" x-cloak></div>

                                        <!-- Modal dialog -->
                                        <div id="edit-modal"
                                            class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                            role="dialog" aria-modal="true" x-show="isOpen"
                                            x-transition:enter="transition ease-in-out duration-200"
                                            x-transition:enter-start="opacity-0 translate-y-4"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in-out duration-200"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                                            <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full"
                                                @click.outside="closeModal" @keydown.escape.window="closeModal">
                                                <!-- Modal header -->
                                                <div class="px-5 py-3 border-b border-slate-200">
                                                    <div class="flex justify-between items-center">
                                                        <div class="font-semibold text-slate-800">Edit Department/Sub
                                                            Department</div>
                                                        <button type="button"
                                                            class="text-slate-400 hover:text-slate-500"
                                                            @click="closeModal">
                                                            <div class="sr-only">Close</div>
                                                            <svg class="w-4 h-4 fill-current">
                                                                <path
                                                                    d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Modal content -->
                                                <div class="modal-content text-xs px-5 py-4">
                                                    <form @submit.prevent="submitForm">
                                                        <input type="hidden" name="id" x-model="formData.id">

                                                        <!-- Edit Type Dropdown -->
                                                        <div class="relative z-0 w-full mb-5 group">
                                                            <select x-model="editType" id="editType"
                                                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                required>
                                                                <option value="department">Department</option>
                                                                <option value="subdepartment">Sub Department</option>
                                                            </select>
                                                            <label for="editType"
                                                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                Edit Type*
                                                            </label>
                                                        </div>

                                                        <!-- Department Edit Form -->
                                                        <div x-show="editType === 'department'" class="space-y-4">
                                                            <div class="relative z-0 w-full mb-5 group">
                                                                <input type="text" id="departmentName"
                                                                    name="departmentName"
                                                                    x-model="formData.departmentName"
                                                                    autocomplete="off"
                                                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                    placeholder=" " required />
                                                                <label for="departmentName"
                                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                    Department Name*
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Sub Department Edit Form -->
                                                        <div x-show="editType === 'subdepartment'" class="space-y-4">
                                                            <div class="relative z-0 w-full mb-5 group">
                                                                <select id="parentDepartment" name="parentDepartment"
                                                                    x-model="formData.parentId"
                                                                    @change="updateDepartmentName()"
                                                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                    required>
                                                                    <option value="" disabled selected hidden>--
                                                                        Select Department --</option>
                                                                    <template x-for="dept in mainDepartments"
                                                                        :key="dept.id">
                                                                        <option :value="dept.id"
                                                                            :selected="dept.id == formData.parentId"
                                                                            x-text="dept.name"></option>
                                                                    </template>
                                                                </select>
                                                                <label for="parentDepartment"
                                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                    Parent Department*
                                                                </label>
                                                            </div>

                                                            <div class="relative z-0 w-full mb-5 group">
                                                                <input type="text" id="subDepartmentName"
                                                                    name="subDepartmentName"
                                                                    x-model="formData.subDepartmentName"
                                                                    autocomplete="off"
                                                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                    placeholder=" " required />
                                                                <label for="subDepartmentName"
                                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                    Sub Department Name*
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div
                                                            class="px-5 py-3 border-t border-slate-200 flex justify-end mt-4">
                                                            <button type="button" @click="closeModal"
                                                                class="mr-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                                Cancel
                                                            </button>
                                                            <button type="button" @click="submitForm"
                                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                                Save Changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('indexEdit.department') }}">
                        <div class="flex items-center">
                            <label for="per_page" class="mr-2">Show:</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded px-4 py-2 w-32">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="px-6 py-4">
                    {{ $subDepartments->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editModal', (subDept, mainDepartments) => ({
                isOpen: false,
                editType: subDept.isDepartment ? 'department' : 'subdepartment',
                formData: {
                    id: subDept.id,
                    departmentName: subDept.departmentName || subDept.name || '',
                    subDepartmentName: subDept.name || '',
                    parentId: subDept.pid || null,
                    status: subDept.status || 'Active'
                },
                mainDepartments: mainDepartments,
                selectedDepartment: null,

                init() {
                    if (this.formData.parentId) {
                        this.selectedDepartment = this.mainDepartments.find(
                            dept => dept.id == this.formData.parentId
                        );
                        if (this.selectedDepartment) {
                            this.formData.departmentName = this.selectedDepartment.name;
                        }
                    }

                    if (this.editType === 'department') {
                        this.formData.departmentName = subDept.name;
                    }
                },

                openModal() {
                    this.isOpen = true;
                },

                closeModal() {
                    this.isOpen = false;
                },

                async submitForm() {
                    try {
                        let url, data;

                        if (this.editType === 'department') {
                            url = '/master/api/departments/' + this.formData.id;
                            data = {
                                name: this.formData.departmentName,
                                status: this.formData.status
                            };
                        } else {
                            if (!this.formData.parentId) {
                                Toastify({
                                    text: "Please select a parent department",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#ef4444",
                                }).showToast();
                                return;
                            }
                            if (!this.formData.subDepartmentName.trim()) {
                                Toastify({
                                    text: "Please enter a sub-department name",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#ef4444",
                                }).showToast();
                                return;
                            }

                            url = '/master/api/sub-departments/' + this.formData.id;
                            data = {
                                name: this.formData.subDepartmentName,
                                pid: this.formData.parentId,
                                status: this.formData.status
                            };
                        }

                        const response = await fetch(url, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(data)
                        });

                        const responseData = await response.json();

                        if (response.ok) {
                            // Tampilkan toast sukses
                            Toastify({
                                text: responseData.message || "Data updated successfully!",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#10b981",
                            }).showToast();

                            // Tutup modal dan refresh data setelah 1 detik
                            setTimeout(() => {
                                this.closeModal();
                                window.location.reload();
                            }, 1000);
                        } else {
                            // Tampilkan toast error
                            Toastify({
                                text: responseData.message || "Error updating data",
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#ef4444",
                            }).showToast();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Toastify({
                            text: "An unexpected error occurred",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#ef4444",
                        }).showToast();
                    }
                },

                updateDepartmentName() {
                    if (this.formData.parentId) {
                        this.selectedDepartment = this.mainDepartments.find(
                            dept => dept.id == this.formData.parentId
                        );
                        if (this.selectedDepartment) {
                            this.formData.departmentName = this.selectedDepartment.name;
                        }
                    } else {
                        this.formData.departmentName = '';
                        this.selectedDepartment = null;
                    }
                }
            }));
        });
    </script>
</x-app-layout>
