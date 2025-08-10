<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Manajemen Fasilitas Ruangan
            </h1>
            <div class="mt-4 md:mt-0">
                <div x-data="modalFacility()" class="relative">
                    <!-- Trigger -->
                    <button type="button" @click="modalOpenDetail = true"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg shadow transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Fasilitas
                    </button>

                    <!-- Backdrop -->
                    <div x-show="modalOpenDetail"
                        class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity" x-transition.opacity
                        @click="modalOpenDetail = false" x-cloak></div>

                    <!-- Modal -->
                    <div x-show="modalOpenDetail" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak
                        @keydown.escape.window="modalOpenDetail = false">

                        <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden border border-gray-100"
                            @click.outside="modalOpenDetail = false">
                            <!-- Header -->
                            <div
                                class="px-5 py-4 bg-gradient-to-r from-blue-100 to-indigo-100 border-b flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-800"
                                    x-text="currentFacility.id ? 'Edit Fasilitas' : 'Tambah Fasilitas'"></h3>
                                <button @click="modalOpenDetail = false"
                                    class="text-gray-400 hover:text-gray-600 transition">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 8.586l4.95-4.95a1 1 0 011.414 1.414L11.414 10l4.95 4.95a1 1 0 01-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 01-1.414-1.414L8.586 10l-4.95-4.95a1 1 0 011.414-1.414L10 8.586z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Form -->
                            <form class="px-5 py-4 space-y-4" @submit.prevent="submitForm">
                                @csrf                              
                                <!-- Facility Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="facility" x-model="currentFacility.facility"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Nama fasilitas" required />
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <textarea name="description" x-model="currentFacility.description" rows="3"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Deskripsi fasilitas (opsional)"></textarea>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end gap-2 pt-2 border-t">
                                    <button type="button" @click="modalOpenDetail = false"
                                        class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-1.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Facility Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Search and Filter Section -->
            <div class="p-4 border-b border-gray-200">
                <form id="searchForm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Search Input -->
                        <div class="w-full md:w-1/3">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Cari fasilitas...">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Status:</span>
                            <select name="status" id="statusFilter"
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Semua</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>                       

                        <!-- Items per Page -->
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Per halaman:</span>
                            <select name="per_page" id="perPageSelect"
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10
                                </option>
                                <option value="15" {{ request('per_page', 10) == 15 ? 'selected' : '' }}>15
                                </option>
                                <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20
                                </option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Fasilitas
                            </th>                           
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Dibuat Oleh
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Perubahan
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($facilities as $facility)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $facility->facility }}
                                        </div>
                                        <div class="text-sm text-gray-500 break-words whitespace-normal">
                                            {{ $facility->description ?? 'No description' }}
                                        </div>
                                    </div>
                                </td>                               
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $facility->createdBy->username ?? 'System' }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $facility->created_at->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($facility->updated_by)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $facility->updatedBy->username ?? 'System' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $facility->updated_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <div class="text-sm font-medium text-gray-400 italic">
                                            Not updated yet
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $facility->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $facility->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                    <!-- Edit Button -->
                                    <div x-data="modalEditFacility()" class="relative">
                                        <!-- Trigger -->
                                        <button type="button" @click="openEditModal(@js($facility))"
                                            class="text-yellow-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>
                                        <!-- Backdrop -->
                                        <div x-show="modalOpenEdit"
                                            class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                                            x-transition.opacity @click="modalOpenEdit = false" x-cloak></div>

                                        <!-- Modal -->
                                        <div x-show="modalOpenEdit"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                            x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak
                                            @keydown.escape.window="modalOpenEdit = false">

                                            <div class="bg-white w-full max-w-md rounded-xl shadow-lg overflow-hidden border border-gray-100"
                                                @click.outside="modalOpenEdit = false">
                                                <!-- Header -->
                                                <div
                                                    class="px-5 py-4 bg-gradient-to-r from-blue-100 to-indigo-100 border-b flex items-center justify-between">
                                                    <h3 class="text-sm font-semibold text-gray-800">
                                                        Edit Fasilitas
                                                    </h3>
                                                    <button @click="modalOpenEdit = false"
                                                        class="text-gray-400 hover:text-gray-600 transition">
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 8.586l4.95-4.95a1 1 0 011.414 1.414L11.414 10l4.95 4.95a1 1 0 01-1.414 1.414L10 11.414l-4.95 4.95a1 1 0 01-1.414-1.414L8.586 10l-4.95-4.95a1 1 0 011.414-1.414L10 8.586z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Form -->
                                                <form class="px-5 py-4 space-y-4" @submit.prevent="submitForm">
                                                    @csrf                                                   
                                                    <!-- Facility Name -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1">Nama
                                                            Fasilitas <span class="text-red-500">*</span></label>
                                                        <input type="text" name="edit_facility"
                                                            x-model="currentFacility.facility"
                                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="Nama fasilitas" required />
                                                    </div>

                                                    <!-- Description -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                                        <textarea name="edit_description" x-model="currentFacility.description" rows="3"
                                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="Deskripsi fasilitas (opsional)"></textarea>
                                                    </div>

                                                    <!-- Status Section -->
                                                    <div class="mb-4">
                                                        <div class="flex justify-between items-center">
                                                            <label class="text-sm font-medium text-gray-700">
                                                                Status <span class="text-red-500">*</span>
                                                            </label>
                                                            <div class="flex items-center space-x-2">
                                                                <span class="text-sm text-gray-600">Inactive</span>
                                                                <label
                                                                    class="relative inline-flex items-center cursor-pointer">
                                                                    <input type="checkbox" class="sr-only peer"
                                                                        x-model="currentFacility.status"
                                                                        :checked="currentFacility.status === 1"
                                                                        :value="currentFacility.status === 1 ? 1 : 0">
                                                                    <div
                                                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer-checked:bg-blue-600 transition-all duration-300">
                                                                    </div>
                                                                    <div
                                                                        class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transform transition-transform duration-300 peer-checked:translate-x-5">
                                                                    </div>
                                                                </label>
                                                                <span class="text-sm text-gray-600">Active</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="flex justify-end gap-2 pt-2 border-t">
                                                        <button type="button" @click="modalOpenEdit = false"
                                                            class="px-3 py-1.5 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-md bg-white hover:bg-gray-50">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="px-4 py-1.5 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No facilities found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 rounded p-4" id="paginationContainer">
                {{ $facilities->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalFacility', () => ({
                modalOpenDetail: false,
                currentFacility: {
                    id: null,
                    facility: '',
                    description: '',                    
                    status: '1',                    
                },
                facilities: [],
                

                showSuccessToast(message) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: message
                    });
                },

                showErrorToast(message) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'error',
                        title: message
                    });
                },

                async submitForm() {
                    let originalBtnText = '';
                    let submitBtn = null;

                    try {
                        submitBtn = document.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            originalBtnText = submitBtn.innerHTML;
                            submitBtn.innerHTML = 'Menyimpan...';
                            submitBtn.disabled = true;
                        }

                        const formData = {
                            facility: this.currentFacility.facility,
                            description: this.currentFacility.description || '',                            
                            status: this.currentFacility.status ? 1 : 0
                        };

                        const url = this.currentFacility.id ?
                            `/properties/rooms/facilityRooms/update/${this.currentFacility.id}` :
                            '/properties/rooms/facilityRooms/store';

                        const method = this.currentFacility.id ? 'PUT' : 'POST';

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(formData)
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            if (data.errors) {
                                let errorMessages = [];
                                for (const [field, errors] of Object.entries(data.errors)) {
                                    errorMessages.push(`${errors.join(', ')}`);
                                }
                                throw new Error(errorMessages.join('\n'));
                            }
                            throw new Error(data.message ||
                                'Terjadi kesalahan saat menyimpan data');
                        }

                        this.showSuccessToast(
                            this.currentFacility.id ?
                            'Fasilitas berhasil diperbarui' :
                            'Fasilitas berhasil ditambahkan'
                        );

                        // Redirect to facility index page after 1.5 seconds
                        setTimeout(() => {
                            window.location.href = '/properties/m-rooms/facilityRooms';
                        }, 500);

                    } catch (error) {
                        console.error('Error:', error);
                        this.showErrorToast(error.message ||
                            'Terjadi kesalahan saat menyimpan data');
                    } finally {
                        if (submitBtn && originalBtnText) {
                            submitBtn.innerHTML = originalBtnText;
                            submitBtn.disabled = false;
                        }
                    }
                },
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modalEditFacility', () => ({
                modalOpenEdit: false,
                currentFacility: {
                    id: null,
                    facility: '',
                    description: '',                    
                    status: 1
                },

                // Add these toast notification methods
                showSuccessToast(message) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                },

                showErrorToast(message) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 5000
                    });
                },

                openEditModal(facility) {
                    this.currentFacility = {
                        ...facility,
                        id: facility.idrec || facility.id,
                        status: parseInt(facility.status)
                    };
                    this.modalOpenEdit = true;
                },

                async submitForm() {
                    let submitBtn = document.querySelector('button[type="submit"]');
                    let originalBtnText = submitBtn?.innerHTML;

                    try {
                        if (submitBtn) {
                            submitBtn.innerHTML = 'Menyimpan...';
                            submitBtn.disabled = true;
                        }

                        const formData = {
                            facility: this.currentFacility.facility,
                            description: this.currentFacility.description || '',                            
                            status: this.currentFacility.status
                        };

                        const response = await fetch(
                            `/properties/rooms/facilityRooms/update/${this.currentFacility.id}`, {
                                method: 'PUT',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify(formData)
                            });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message ||
                                'Terjadi kesalahan saat menyimpan data');
                        }

                        this.showSuccessToast('Fasilitas berhasil diperbarui');
                        setTimeout(() => {
                            window.location.href = '/properties/m-rooms/facilityRooms';
                        }, 500);

                    } catch (error) {
                        console.error('Error:', error);
                        this.showErrorToast(error.message ||
                            'Terjadi kesalahan saat menyimpan data');
                    } finally {
                        if (submitBtn && originalBtnText) {
                            submitBtn.innerHTML = originalBtnText;
                            submitBtn.disabled = false;
                        }
                    }
                },
            }));
        });
    </script>
</x-app-layout>
