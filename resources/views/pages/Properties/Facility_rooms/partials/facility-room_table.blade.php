<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nama Fasilitas
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Dibuat Oleh
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal Perubahan
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                    <div class="flex items-center space-x-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox"
                                class="sr-only peer facility-room-status-toggle"
                                data-id="{{ $facility->idrec }}"
                                {{ $facility->status == 1 ? 'checked' : '' }}
                                onchange="toggleFacilityRoomStatus(this)">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer-checked:bg-blue-600 transition-all duration-300"></div>
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow transform transition-transform duration-300 peer-checked:translate-x-5"></div>
                        </label>
                        <span class="text-sm font-medium status-label {{ $facility->status == 1 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $facility->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                    <!-- Edit Button -->
                    <div x-data="modalEditFacility()" class="relative">
                        <!-- Trigger -->
                        <button type="button" @click="openEditModal(@js($facility))"
                            class="text-yellow-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
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
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama
                                            Fasilitas <span class="text-red-500">*</span></label>
                                        <input type="text" name="edit_facility" x-model="currentFacility.facility"
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Nama fasilitas" required />
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
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
                                                <label class="relative inline-flex items-center cursor-pointer">
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
