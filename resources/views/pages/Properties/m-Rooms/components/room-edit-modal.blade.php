<div x-data="modalRoomEdit({{ $room }})" class="relative group">
    @php
        $roomImages = $room->roomImages
            ->map(function ($image) {
                return [
                    'id' => $image->idrec,
                    'url' => asset('storage/' . $image->image),
                    'caption' => $image->caption,
                    'name' => 'Image_' . $image->idrec . '.jpg',
                    'is_thumbnail' => $image->thumbnail == 1,
                ];
            })
            ->toJson();

        // Process facilities data
        $facilities = [];
        if (!empty($room->facility)) {
            if (is_string($room->facility)) {
                // Handle JSON string
                $decoded = json_decode($room->facility, true);
                $facilities = is_array($decoded) ? $decoded : [];
            } elseif (is_array($room->facility)) {
                $facilities = $room->facility;
            }
        }

        // Convert all facility IDs to strings for consistency
        $facilities = array_map('strval', $facilities);

        // Get facility data from database
        $facilityData = \App\Models\RoomFacility::where('status', 1)
            ->select('idrec as id', 'facility as name', 'description')
            ->get()
            ->toArray();
    @endphp

    <button
        class="p-2 flex items-center justify-center text-amber-600 hover:text-amber-900 transition-colors duration-200 rounded-full hover:bg-amber-50"
        type="button"
        @click.prevent='openModal({
                        id: {{ $room->idrec }},
                        property_id: {{ $room->property_id }},
                        name: @json($room->name),
                        number: @json($room->no),
                        size: @json($room->size),
                        bed: @json($room->bed_type),
                        capacity: @json($room->capacity),
                        description: @json($room->descriptions),
                        daily_price: "{{ $room->price_original_daily }}",
                        monthly_price: "{{ $room->price_original_monthly }}",
                        facilities: {{ json_encode($facilities) }},
                        existingImages: {!! $roomImages !!}
                        })'
        aria-controls="room-edit-modal-{{ $room->idrec }}" title="Edit Room">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path
                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
        </svg>
    </button>

    <!-- Modal backdrop -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity" x-show="editModalOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>

    <!-- Modal dialog -->
    <div id="room-edit-modal-{{ $room->idrec }}"
        class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6" role="dialog"
        aria-modal="true" x-show="editModalOpen" x-transition:enter="transition ease-in-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in-out duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak>

        <div class="bg-white rounded-2xl shadow-lg overflow-auto w-3/4 max-h-full flex flex-col text-left"
            @click.outside="editModalOpen = false" @keydown.escape.window="editModalOpen = false">

            <!-- Modal header with step indicator -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex justify-between items-center mb-4">
                    <div class="font-bold text-xl text-gray-800">Edit Kamar</div>
                    <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                        @click="editModalOpen = false">
                        <div class="sr-only">Close</div>
                        <svg class="w-6 h-6 fill-current">
                            <path
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                        </svg>
                    </button>
                </div>

                <!-- Step Indicator -->
                <div class="flex items-center justify-center space-x-4">
                    <!-- Step 1 -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                            :class="editStep >= 1 ?
                                'bg-blue-600 border-blue-600 text-white' :
                                'border-gray-300 text-gray-500'">
                            <span class="text-sm font-semibold" x-show="editStep < 1">1</span>
                            <svg x-show="editStep >= 1" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">
                            <p class="font-medium transition-colors duration-300"
                                :class="editStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
                                Informasi Dasar
                            </p>
                        </div>
                    </div>

                    <!-- Connector -->
                    <div class="w-16 h-0.5 transition-colors duration-300"
                        :class="editStep >= 2 ? 'bg-blue-600' : 'bg-gray-300'">
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                            :class="editStep >= 2 ?
                                'bg-blue-600 border-blue-600 text-white' :
                                'border-gray-300 text-gray-500'">
                            <span class="text-sm font-semibold" x-show="editStep < 2">2</span>
                            <svg x-show="editStep >= 2" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">
                            <p class="font-medium transition-colors duration-300"
                                :class="editStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
                                Harga
                            </p>
                        </div>
                    </div>

                    <!-- Connector -->
                    <div class="w-16 h-0.5 transition-colors duration-300"
                        :class="editStep >= 3 ? 'bg-blue-600' : 'bg-gray-300'">
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                            :class="editStep >= 3 ?
                                'bg-blue-600 border-blue-600 text-white' :
                                'border-gray-300 text-gray-500'">
                            <span class="text-sm font-semibold" x-show="editStep < 3">3</span>
                            <svg x-show="editStep >= 3" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">
                            <p class="font-medium transition-colors duration-300"
                                :class="editStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
                                Fasilitas
                            </p>
                        </div>
                    </div>

                    <!-- Connector -->
                    <div class="w-16 h-0.5 transition-colors duration-300"
                        :class="editStep >= 4 ? 'bg-blue-600' : 'bg-gray-300'">
                    </div>

                    <!-- Step 4 -->
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                            :class="editStep >= 4 ?
                                'bg-blue-600 border-blue-600 text-white' :
                                'border-gray-300 text-gray-500'">
                            <span class="text-sm font-semibold" x-show="editStep < 4">4</span>
                            <svg x-show="editStep >= 4" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">
                            <p class="font-medium transition-colors duration-300"
                                :class="editStep >= 4 ? 'text-blue-600' : 'text-gray-500'">
                                Foto
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal content -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <form id="roomFormEdit-{{ $room->idrec }}" method="POST"
                    action="{{ route('rooms.update', $room->idrec) }}" enctype="multipart/form-data"
                    @submit.prevent="submitEditForm">
                    @csrf
                    @method('PUT')

                    <!-- Step 1 - Basic Information -->
                    <div x-show="editStep === 1" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">

                        <div class="space-y-6">

                            <!-- Properti -->
                            <div>
                                <label for="edit_property_id_{{ $room->idrec }}"
                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                    Properti <span class="text-red-500">*</span>
                                </label>

                                <input type="text" id="edit_property_id_{{ $room->idrec }}" name="property_name"
                                    value="{{ $room->property->name ?? '' }}" readonly
                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 bg-gray-100 text-gray-700">

                                <input type="hidden" name="property_id" value="{{ $room->property_id }}">
                            </div>


                            <!-- Row digabung 1 baris -->
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

                                <!-- Nomor Kamar -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Kamar <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="room_no" required
                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                        placeholder="Nomor Kamar" x-model="roomData.number">
                                </div>

                                <!-- Nama / Tipe Kamar -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama / Tipe Kamar <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="room_name" required
                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                        placeholder="Nama / Tipe" x-model="roomData.name">
                                </div>

                                <!-- Jenis Kasur -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Tempat Tidur <span class="text-red-500">*</span>
                                    </label>
                                    <select name="room_bed" required
                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                        x-model="roomData.bed" @change="updateCapacity()">
                                        <option value="">Pilih Jenis Kasur</option>
                                        <option value="Single">Single</option>
                                        <option value="Twin">Twin</option>
                                        <option value="Double">Double</option>
                                        <option value="Queen">Queen</option>
                                        <option value="King">King</option>
                                    </select>
                                </div>

                                <!-- Ukuran Kamar -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Ukuran Kamar (m²) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="room_size" required
                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                        placeholder="Ukuran" x-model="roomData.size">
                                </div>

                                <!-- Kapasitas -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kapasitas (Pax) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="room_capacity" required
                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 bg-gray-100 cursor-not-allowed"
                                        x-model="roomData.capacity" readonly />
                                </div>
                            </div>


                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Deskripsi Kamar <span class="text-red-500">*</span>
                                </label>
                                <textarea name="description" rows="4" required
                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4" placeholder="Deskripsikan kamar..."
                                    x-model="roomData.description"></textarea>
                            </div>

                        </div>
                    </div>


                    <!-- Step 2 - Pricing -->
                    <div x-show="editStep === 2" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                        <div class="space-y-6">
                            <div class="mb-4">
                                <h3 class="text-md font-semibold text-gray-700 mb-2">
                                    Jenis Harga</h3>
                                <div class="flex space-x-6">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" value="daily" x-model="priceTypes"
                                            class="form-checkbox text-blue-600">
                                        <span class="ml-2">Harian</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" value="monthly" x-model="priceTypes"
                                            class="form-checkbox text-blue-600">
                                        <span class="ml-2">Bulanan</span>
                                    </label>
                                </div>
                            </div>

                            <div x-show="priceTypes.includes('daily')" x-transition>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Harga Harian <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">Rp</span>
                                    </div>
                                    <input type="text" x-ref="dailyPriceInput"
                                        class="w-full pl-10 border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Masukkan harga harian" x-model="roomData.daily_price"
                                        @input="formatPriceInput($event, 'daily_price')">
                                    <input type="hidden" name="daily_price" x-model="dailyPrice">
                                </div>
                            </div>

                            <div x-show="priceTypes.includes('monthly')" x-transition class="mt-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Harga Bulanan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500">Rp</span>
                                    </div>
                                    <input type="text" x-ref="monthlyPriceInput"
                                        class="w-full pl-10 border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                        placeholder="Masukkan harga bulanan" x-model="roomData.monthly_price"
                                        @input="formatPriceInput($event, 'monthly_price')">
                                    <input type="hidden" name="monthly_price" x-model="monthlyPrice">
                                </div>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mt-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Anda bisa memilih salah satu atau kedua
                                            jenis harga. Pastikan
                                            mengisi harga yang sesuai dengan jenis
                                            yang
                                            dipilih.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 - Facilities -->
                    <div x-show="editStep === 3" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                        <div class="space-y-6">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                    </svg>
                                    Fasilitas Kamar
                                </h3>

                                <!-- Facilities Grid -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
                                    <template x-for="facility in facilityData" :key="facility.id">
                                        <div class="relative">
                                            <label
                                                class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200"
                                                :class="{
                                                    'border-blue-500 bg-blue-50': roomData
                                                        .facilities.includes(facility.id
                                                            .toString())
                                                }">
                                                <input type="checkbox" :value="facility.id"
                                                    x-model="roomData.facilities"
                                                    class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                    :id="'facility_' + facility.id">

                                                <div class="ml-3 flex-1">
                                                    <span class="block text-sm font-medium text-gray-900"
                                                        x-text="facility.name"></span>
                                                    <span class="block text-xs text-gray-500 mt-1"
                                                        x-text="facility.description"></span>
                                                </div>
                                            </label>
                                        </div>
                                    </template>
                                </div>

                                <!-- No Facilities Available Message -->
                                <div x-show="facilityData.length === 0" class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Tidak ada
                                        fasilitas tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 - Photos -->
                    <div x-show="editStep === 4" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                        <div class="space-y-6">
                            <div>
                                <!-- Hidden field to store thumbnail index -->
                                <input type="hidden" name="thumbnail_index" x-model="thumbnailIndex">

                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Foto Kamar <span class="text-red-500">*</span>
                                    <span class="text-sm font-normal text-gray-500">
                                        (Minimal <span x-text="editMinImages"></span> foto,
                                        maksimal <span x-text="editMaxImages"></span> foto)
                                    </span>
                                </label>

                                <!-- Thumbnail Preview Section -->
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                        Thumbnail Saat Ini <span class="text-red-500">*</span>
                                        <span class="text-xs font-normal text-gray-500">(Foto utama kamar)</span>
                                    </h4>

                                    <div class="flex items-center space-x-4">
                                        <!-- Thumbnail Preview -->
                                        <div
                                            class="w-32 h-32 bg-gray-100 rounded-lg border-2 border-gray-300 overflow-hidden relative flex items-center justify-center">
                                            <template x-if="getCurrentThumbnail()">
                                                <img :src="getCurrentThumbnail().url"
                                                    class="w-full h-full object-cover" alt="Current Thumbnail">
                                            </template>
                                            <div class="absolute inset-0 flex items-center justify-center text-gray-400"
                                                x-show="!getCurrentThumbnail()">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Thumbnail Selection Instructions -->
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-600 mb-2">
                                                <span x-show="thumbnailIndex === null"
                                                    class="font-medium text-red-500">
                                                    Belum ada thumbnail dipilih!
                                                </span>
                                                <span x-show="thumbnailIndex !== null"
                                                    class="font-medium text-green-600">
                                                    Thumbnail sudah dipilih.
                                                </span>
                                                Klik salah satu foto di bawah untuk memilih sebagai thumbnail.
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Pastikan memilih foto terbaik sebagai thumbnail karena ini akan menjadi
                                                gambar utama kamar Anda.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Area -->
                                <div x-show="editCanUploadMore" @dragover="handleEditDragOver($event)"
                                    @dragleave="handleEditDragLeave($event)" @drop="handleEditDrop($event)"
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200 cursor-pointer"
                                    :class="{ 'border-blue-400 bg-blue-50': editCanUploadMore }">
                                    <div class="space-y-2">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label for="edit_room_images_{{ $room->idrec }}"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload foto</span>
                                                <input id="edit_room_images_{{ $room->idrec }}"
                                                    name="edit_room_images[]" type="file" multiple
                                                    accept="image/*" @change="handleEditFileSelect($event)"
                                                    class="sr-only">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG (maks. 5MB per file)</p>
                                        <p class="text-xs text-blue-600"
                                            x-text="`Dapat upload ${editRemainingSlots} foto lagi`">
                                        </p>
                                    </div>
                                </div>

                                <!-- Full Upload Message -->
                                <div x-show="!editCanUploadMore"
                                    class="border-2 border-green-300 rounded-lg p-8 text-center bg-green-50">
                                    <div class="space-y-2">
                                        <svg class="w-12 h-12 mx-auto text-green-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <p class="text-sm text-green-600 font-medium">
                                            <span x-text="editMaxImages"></span> foto telah diupload!
                                        </p>
                                        <p class="text-xs text-green-500">Maksimal upload foto tercapai</p>
                                    </div>
                                </div>

                                <!-- Image Preview Grid -->
                                <div x-show="getAllImages().length > 0" class="mt-4">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Foto Terupload</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100">

                                        <!-- Existing Images -->
                                        <template x-for="(image, index) in roomData.existingImages"
                                            :key="'existing-' + index">
                                            <div class="relative group" x-show="!image.markedForDeletion"
                                                @click="setThumbnail(getImageIndex('existing', index))">
                                                <!-- Image Container -->
                                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                                    :class="thumbnailIndex === getImageIndex('existing', index) ?
                                                        'border-blue-600 ring-2 ring-blue-400' :
                                                        'border-gray-200 hover:border-blue-400'">
                                                    <img :src="image.url" class="w-full h-full object-cover"
                                                        :alt="'Existing Image ' + (getDisplayIndex('existing', index) + 1)">

                                                    <!-- Thumbnail Badge -->
                                                    <div x-show="thumbnailIndex === getImageIndex('existing', index)"
                                                        class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                        Thumbnail
                                                    </div>

                                                    <!-- Image Number -->
                                                    <div
                                                        class="absolute bottom-1 left-1 bg-gray-800 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                        <span x-text="getDisplayIndex('existing', index) + 1"></span>
                                                    </div>
                                                </div>

                                                <!-- Remove Button -->
                                                <button type="button" @click.stop="removeEditExistingImage(index)"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>

                                                <!-- Hidden Image ID -->
                                                <input type="hidden" name="existing_images[]"
                                                    :value="image.id">
                                            </div>
                                        </template>

                                        <!-- New Images -->
                                        <template x-for="(image, index) in editImages" :key="'new-' + index">
                                            <div class="relative group"
                                                @click="setThumbnail(getImageIndex('new', index))">
                                                <!-- Image Container -->
                                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                                    :class="thumbnailIndex === getImageIndex('new', index) ?
                                                        'border-blue-600 ring-2 ring-blue-400' :
                                                        'border-gray-200 hover:border-blue-400'">
                                                    <img :src="image.url" class="w-full h-full object-cover"
                                                        :alt="'New Image ' + (getDisplayIndex('new', index) + 1)">

                                                    <!-- Thumbnail Badge -->
                                                    <div x-show="thumbnailIndex === getImageIndex('new', index)"
                                                        class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                        Thumbnail
                                                    </div>

                                                    <!-- Image Number -->
                                                    <div
                                                        class="absolute bottom-1 left-1 bg-gray-800 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                        <span x-text="getDisplayIndex('new', index) + 1"></span>
                                                    </div>
                                                </div>

                                                <!-- Remove Button -->
                                                <button @click.stop="removeEditImage(index)"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Progress Indicator -->
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Progress Upload</span>
                                        <span x-text="`${getAllImages().length}/${editMaxImages} foto`"></span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                            :style="`width: ${(getAllImages().length / editMaxImages) * 100}%`">
                                        </div>
                                    </div>
                                </div>

                                <!-- Validation Messages -->
                                <div class="mt-3 space-y-2">
                                    <div x-show="getAllImages().length < editMinImages"
                                        class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm text-red-600">
                                            <span class="font-medium">Perhatian:</span>
                                            Anda harus mengupload minimal <span x-text="editMinImages"></span> foto.
                                        </p>
                                    </div>

                                    <div x-show="thumbnailIndex === null && getAllImages().length > 0"
                                        class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-600">
                                            <span class="font-medium">Perhatian:</span>
                                            Anda harus memilih thumbnail untuk melanjutkan.
                                        </p>
                                    </div>

                                    <div x-show="getAllImages().length >= editMinImages && thumbnailIndex !== null"
                                        class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                        <p class="text-sm text-green-600">
                                            <span class="font-medium">Sempurna!</span>
                                            Foto sudah memenuhi syarat dan thumbnail telah dipilih.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 flex justify-end">
                        <div>
                            <button type="button" x-show="editStep > 1" @click="editStep--"
                                class="px-6 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Sebelumnya
                            </button>
                            <button type="button" x-show="editStep < 4"
                                @click="validateEditStep(editStep) && editStep++"
                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                Selanjutnya
                                <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <button type="submit" x-show="editStep === 4"
                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('modalRoomEdit', (room) => ({
            editModalOpen: false,
            editStep: 1,
            editMinImages: 3,
            editMaxImages: 5,
            editImages: [],
            priceTypes: [],
            dailyPrice: 0,
            monthlyPrice: 0,
            thumbnailIndex: null,
            thumbnailType: null,
            isDragging: false,
            isSubmitting: false,
            originalRoomData: {},

            facilityData: @json($facilityData),

            roomData: {
                id: room.idrec || '',
                property_id: room.property_id || '',
                name: room.name || '',
                number: room.no || '',
                size: room.size || '',
                bed: room.bed_type || 'Single',
                capacity: room.capacity || '',
                description: room.descriptions || '',
                daily_price: room.price_original_daily ? room.price_original_daily.toString() : '',
                monthly_price: room.price_original_monthly ? room.price_original_monthly
                    .toString() : '',
                facilities: [],
                existingImages: room.roomImages || [],
                thumbnailIndex: null,
                thumbnailType: null
            },

            init() {
                this.originalRoomData = JSON.parse(JSON.stringify(this.roomData));

                // Format harga
                this.roomData.daily_price = this.roomData.daily_price !== null ?
                    formatRupiah(this.roomData.daily_price) : '';
                this.roomData.monthly_price = this.roomData.monthly_price !== null ?
                    formatRupiah(this.roomData.monthly_price) : '';

                this.priceTypes = [];

                // Inisialisasi harga
                const rawDailyPrice = this.roomData.daily_price !== null ?
                    parseFloat(this.originalRoomData.daily_price) : 0;
                if (rawDailyPrice > 0) {
                    this.priceTypes.push('daily');
                    this.dailyPrice = rawDailyPrice;
                }

                // Inisialisasi fasilitas - pastikan format konsisten
                this.initializeFacilities();

                const rawMonthlyPrice = this.roomData.monthly_price !== null ?
                    parseFloat(this.originalRoomData.monthly_price) : 0;
                if (rawMonthlyPrice > 0) {
                    this.priceTypes.push('monthly');
                    this.monthlyPrice = rawMonthlyPrice;
                }

                // Inisialisasi thumbnail
                const thumbnailIndex = this.roomData.existingImages.findIndex(
                    img => img.is_thumbnail
                );
                this.thumbnailIndex = thumbnailIndex !== -1 ? thumbnailIndex :
                    (this.roomData.existingImages.length > 0 ? 0 : null);
            },

            initializeFacilities() {
                let facilities = [];

                // Handle different formats of facility data
                if (room.facility) {
                    if (Array.isArray(room.facility)) {
                        facilities = room.facility.map(id => id.toString());
                    } else if (typeof room.facility === 'string') {
                        try {
                            const parsed = JSON.parse(room.facility);
                            if (Array.isArray(parsed)) {
                                facilities = parsed.map(id => id.toString());
                            }
                        } catch (e) {
                            // If it's a simple string, try to split by comma
                            facilities = room.facility.split(',').map(id => id.toString().trim());
                        }
                    }
                }

                // Remove empty values and ensure all are strings
                this.roomData.facilities = facilities.filter(id => id && id !== '');
            },

            // Method untuk mendapatkan nama fasilitas berdasarkan ID
            getFacilityName(id) {
                const facility = this.facilityData.find(f => f.id.toString() === id.toString());
                return facility ? facility.name : `Facility #${id}`;
            },

            // Method untuk mendapatkan deskripsi fasilitas
            getFacilityDescription(id) {
                const facility = this.facilityData.find(f => f.id.toString() === id.toString());
                return facility ? facility.description : '';
            },

            // Method untuk menghapus fasilitas
            removeFacility(facilityId) {
                this.roomData.facilities = this.roomData.facilities.filter(id => id !== facilityId);
            },

            // Method untuk mengecek apakah fasilitas dipilih
            isFacilitySelected(facilityId) {
                return this.roomData.facilities.includes(facilityId.toString());
            },

            // Validasi step fasilitas
            validateFacilitiesStep() {
                // Fasilitas bersifat opsional, jadi selalu return true
                return true;
            },

            setEditThumbnail(index, type) {
                this.roomData.thumbnailIndex = index;
                this.roomData.thumbnailType = type;
            },

            getThumbnailUrl() {
                if (this.roomData.thumbnailType === 'existing') {
                    return this.roomData.existingImages[this.roomData.thumbnailIndex].url;
                } else if (this.roomData.thumbnailType === 'new') {
                    return this.editImages[this.roomData.thumbnailIndex].url;
                }
                return '';
            },

            getImageNumber(index, type) {
                if (type === 'existing') {
                    return index + 1;
                } else {
                    return this.roomData.existingImages.filter(img => !img.markedForDeletion)
                        .length + index + 1;
                }
            },

            handleEditDragOver(event) {
                event.preventDefault();
                this.isDragging = true;
                event.currentTarget.classList.add('border-blue-500', 'bg-blue-50');
            },

            handleEditDragLeave(event) {
                event.preventDefault();
                this.isDragging = false;
                event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');
            },

            handleEditDrop(event) {
                event.preventDefault();
                this.isDragging = false;
                event.currentTarget.classList.remove('border-blue-500', 'bg-blue-50');

                if (!this.editCanUploadMore) {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: `Maksimal hanya ${this.editMaxImages} foto yang dapat diupload.`,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    return;
                }

                const files = Array.from(event.dataTransfer.files);
                this.processEditFiles(files);
            },

            handleEditFileSelect(event) {
                const files = Array.from(event.target.files);
                const wasEmpty = this.editImages.length === 0 &&
                    this.roomData.existingImages.filter(img => !img.markedForDeletion).length === 0;
                this.processEditFiles(files);

                if (wasEmpty && (this.editImages.length > 0 ||
                        this.roomData.existingImages.filter(img => !img.markedForDeletion).length >
                        0)) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'info',
                        title: 'Foto pertama akan menjadi thumbnail kamar',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }
            },

            processEditFiles(files) {
                const imageFiles = files.filter(file => file.type.startsWith('image/'));
                const existingImagesCount = this.roomData.existingImages.filter(img => !img
                    .markedForDeletion).length;
                const availableSlots = this.editMaxImages - (existingImagesCount + this.editImages
                    .length);

                if (availableSlots <= 0) {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: `Maksimal hanya ${this.editMaxImages} foto yang dapat diupload.`,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                    return;
                }

                const filesToProcess = imageFiles.slice(0, availableSlots);

                if (imageFiles.length > availableSlots) {
                    Swal.fire({
                        toast: true,
                        icon: 'warning',
                        title: `Hanya ${availableSlots} foto yang dapat ditambahkan.`,
                        text: `Sisa slot: ${availableSlots}`,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }

                filesToProcess.forEach(file => {
                    if (file.size <= 5 * 1024 * 1024) { // 5MB limit
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.editImages.push({
                                file: file,
                                url: e.target.result,
                                name: file.name,
                                isNew: true
                            });

                            // If this is the first image being uploaded and no thumbnail exists, set it as thumbnail
                            if (this.editImages.length === 1 && this.thumbnailIndex ===
                                null &&
                                this.roomData.existingImages.filter(img => !img
                                    .markedForDeletion).length === 0) {
                                this.setThumbnail(0);
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: `File ${file.name} terlalu besar. Maksimal 5MB.`,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                });

                // Clear the file input
                if (event.target) {
                    event.target.value = '';
                }
            },


            get editRemainingSlots() {
                const existingImagesCount = this.roomData.existingImages.filter(img => !img
                    .markedForDeletion).length;
                return this.editMaxImages - (existingImagesCount + this.editImages.length);
            },

            get editCanUploadMore() {
                const existingImagesCount = this.roomData.existingImages.filter(img => !img
                    .markedForDeletion).length;
                return (existingImagesCount + this.editImages.length) < this.editMaxImages;
            },

            get editUploadProgress() {
                const totalCurrentImages = this.roomData.existingImages.filter(img => !img
                    .markedForDeletion).length + this.editImages.length;
                const percentage = Math.min(100, (totalCurrentImages / this.editMaxImages) *
                    100);

                return {
                    percentage,
                    status: totalCurrentImages < this.editMinImages ? 'danger' :
                        totalCurrentImages >= this.editMinImages && totalCurrentImages < this
                        .editMaxImages ? 'warning' : 'success'
                };
            },

            get editImageUploadStatus() {
                const totalCurrentImages = this.roomData.existingImages.filter(img => !img
                    .markedForDeletion).length + this.editImages.length;

                if (totalCurrentImages < this.editMinImages) {
                    return {
                        class: 'text-red-600',
                        message: `Minimal ${this.editMinImages} foto diperlukan (${totalCurrentImages}/${this.editMinImages})`
                    };
                } else if (totalCurrentImages >= this.editMinImages && totalCurrentImages < this
                    .editMaxImages) {
                    return {
                        class: 'text-yellow-600',
                        message: `${totalCurrentImages}/${this.editMaxImages} foto (dapat menambah ${this.editMaxImages - totalCurrentImages} lagi)`
                    };
                } else {
                    return {
                        class: 'text-green-600',
                        message: `${totalCurrentImages}/${this.editMaxImages} foto (maksimal tercapai)`
                    };
                }
            },

            openModal(data) {
                this.originalRoomData = JSON.parse(JSON.stringify(data));

                data.daily_price = data.daily_price !== null ? formatRupiah(data.daily_price) : '';
                data.monthly_price = data.monthly_price !== null ? formatRupiah(data
                    .monthly_price) : '';

                // Process facilities data
                let facilities = [];
                if (Array.isArray(data.facilities)) {
                    facilities = data.facilities.map(id => id.toString());
                } else if (data.facilities) {
                    if (typeof data.facilities === 'string') {
                        try {
                            const parsed = JSON.parse(data.facilities);
                            facilities = Array.isArray(parsed) ? parsed.map(id => id.toString()) :
                            [];
                        } catch (e) {
                            facilities = [data.facilities.toString()];
                        }
                    }
                }

                // Process existing images - filter valid image URLs
                const validExistingImages = Array.isArray(data.existingImages) ?
                    data.existingImages.filter(img => img && (img.url.startsWith('http') || img.url
                        .startsWith('/storage'))) : [];

                this.roomData = {
                    ...this.roomData,
                    ...data,
                    facilities: facilities,
                    existingImages: validExistingImages,
                };

                this.editModalOpen = true;
                this.editStep = 1;
                this.editImages = [];

                this.priceTypes = [];

                const rawDailyPrice = data.daily_price !== null ? parseFloat(this.originalRoomData
                    .daily_price) : 0;
                if (rawDailyPrice > 0) {
                    this.priceTypes.push('daily');
                    this.dailyPrice = rawDailyPrice;
                }

                const rawMonthlyPrice = data.monthly_price !== null ? parseFloat(this
                    .originalRoomData.monthly_price) : 0;
                if (rawMonthlyPrice > 0) {
                    this.priceTypes.push('monthly');
                    this.monthlyPrice = rawMonthlyPrice;
                }

                // Initialize thumbnail - find existing thumbnail or set to first image
                const existingThumbnailIndex = this.roomData.existingImages.findIndex(img => img
                    .is_thumbnail);
                if (existingThumbnailIndex !== -1) {
                    this.thumbnailIndex = this.getImageIndex('existing', existingThumbnailIndex);
                } else if (this.roomData.existingImages.length > 0) {
                    this.thumbnailIndex = 0;
                    this.roomData.existingImages[0].is_thumbnail = true;
                } else {
                    this.thumbnailIndex = null;
                }
            },

            updateCapacity() {
                switch (this.roomData.bed) {
                    case "Single":
                        this.roomData.capacity = 1;
                        break;
                    case "Twin":
                    case "Double":
                    case "Queen":
                    case "King":
                        this.roomData.capacity = 2;
                        break;
                    default:
                        this.roomData.capacity = '';
                }
            },

            getImageIndex(type, index) {
                if (type === 'existing') {
                    // For existing images, count only non-deleted ones up to this index
                    let actualIndex = 0;
                    for (let i = 0; i < index; i++) {
                        if (!this.roomData.existingImages[i].markedForDeletion) {
                            actualIndex++;
                        }
                    }
                    return actualIndex;
                } else {
                    // For new images, add after all non-deleted existing images
                    const existingCount = this.roomData.existingImages.filter(img => !img
                        .markedForDeletion).length;
                    return existingCount + index;
                }
            },

            // Get display index (sequential numbering for UI)
            getDisplayIndex(type, index) {
                if (type === 'existing') {
                    // Count display position among non-deleted existing images
                    let displayIndex = 0;
                    for (let i = 0; i < index; i++) {
                        if (!this.roomData.existingImages[i].markedForDeletion) {
                            displayIndex++;
                        }
                    }
                    return displayIndex;
                } else {
                    // For new images, continue numbering after existing images
                    const existingCount = this.roomData.existingImages.filter(img => !img
                        .markedForDeletion).length;
                    return existingCount + index;
                }
            },

            // Get all images in correct order for processing
            getAllImages() {
                const existing = this.roomData.existingImages.filter(img => !img.markedForDeletion);
                return [...existing, ...this.editImages];
            },

            // Get current thumbnail with proper index handling
            getCurrentThumbnail() {
                if (this.thumbnailIndex === null) {
                    // Try to find the existing thumbnail if none is selected
                    const allImages = this.getAllImages();
                    const thumbnailIndex = allImages.findIndex(img => img.is_thumbnail);
                    if (thumbnailIndex !== -1) {
                        this.thumbnailIndex = thumbnailIndex;
                        return allImages[thumbnailIndex];
                    }
                    return null;
                }

                const allImages = this.getAllImages();
                return allImages[this.thumbnailIndex] || null;
            },

            // Set thumbnail with proper index validation
            setThumbnail(index) {
                const allImages = this.getAllImages();
                if (index >= 0 && index < allImages.length) {
                    this.thumbnailIndex = index;

                    // Update is_thumbnail flag for existing images
                    this.roomData.existingImages.forEach((img, i) => {
                        const globalIndex = this.getImageIndex('existing', i);
                        img.is_thumbnail = (globalIndex === index);
                    });
                }
            },

            get currentThumbnail() {
                if (this.thumbnailType === 'existing' && this.thumbnailIndex !== null) {
                    return this.roomData.existingImages[this.thumbnailIndex];
                } else if (this.thumbnailType === 'new' && this.thumbnailIndex !== null) {
                    return this.editImages[this.thumbnailIndex];
                }
                return null;
            },

            setThumbnail(index, type) {
                this.thumbnailIndex = index;
                this.thumbnailType = type;

                // If setting an existing image as thumbnail, mark it as thumbnail
                if (type === 'existing') {
                    this.roomData.existingImages.forEach((img, i) => {
                        img.is_thumbnail = (i === index);
                    });
                }
            },

            removeEditExistingImage(index) {
                const currentThumbnailIndex = this.thumbnailIndex;
                const imageGlobalIndex = this.getImageIndex('existing', index);

                // Mark image for deletion
                this.roomData.existingImages[index].markedForDeletion = true;

                // Update thumbnail index if the deleted image was the thumbnail
                if (currentThumbnailIndex === imageGlobalIndex) {
                    this.updateThumbnailAfterDeletion();
                }
            },

            updateThumbnailAfterDeletion() {
                const allImages = this.getAllImages();
                if (allImages.length === 0) {
                    this.thumbnailIndex = null;
                } else {
                    // Set thumbnail to first available image
                    this.thumbnailIndex = 0;

                    // Update is_thumbnail flag for the new thumbnail
                    this.roomData.existingImages.forEach((img, i) => {
                        const globalIndex = this.getImageIndex('existing', i);
                        img.is_thumbnail = (globalIndex === 0);
                    });
                }
            },

            removeEditImage(index) {
                const currentThumbnailIndex = this.thumbnailIndex;
                const imageGlobalIndex = this.getImageIndex('new', index);

                // Remove the image
                this.editImages.splice(index, 1);

                // Update thumbnail index if the deleted image was the thumbnail
                if (currentThumbnailIndex === imageGlobalIndex) {
                    this.updateThumbnailAfterDeletion();
                }
            },
            validateEditStep(step) {
                if (step === 1) {
                    if (!this.roomData.property_id) {
                        alert('Properti harus dipilih');
                        return false;
                    }
                    if (!this.roomData.name || !this.roomData.name.toString().trim()) {
                        alert('Nama kamar harus diisi');
                        return false;
                    }
                    if (!this.roomData.number || !this.roomData.number.toString().trim()) {
                        alert('Nomor kamar harus diisi');
                        return false;
                    }
                    if (!this.roomData.size) {
                        alert('Ukuran kamar harus diisi');
                        return false;
                    }
                    if (!this.roomData.bed) {
                        alert('Jenis kasur harus dipilih');
                        return false;
                    }
                    if (!this.roomData.capacity) {
                        alert('Kapasitas kamar harus diisi');
                        return false;
                    }
                    if (!this.roomData.description || !this.roomData.description.toString()
                        .trim()) {
                        alert('Deskripsi kamar harus diisi');
                        return false;
                    }
                } else if (step === 2) {
                    if (this.priceTypes.includes('daily') && !this.roomData.daily_price) {
                        alert('Harga harian harus diisi');
                        return false;
                    }
                    if (this.priceTypes.includes('monthly') && !this.roomData.monthly_price) {
                        alert('Harga bulanan harus diisi');
                        return false;
                    }
                    if (this.priceTypes.length === 0) {
                        alert('Pilih minimal satu jenis harga');
                        return false;
                    }
                } else if (step === 4) {
                    const totalImages = this.editImages.length + this.roomData.existingImages
                        .filter(img => !img.markedForDeletion).length;
                    if (totalImages < this.editMinImages) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'warning',
                            title: `Harap unggah minimal ${this.editMinImages} foto kamar (Saat ini: ${totalImages})`,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        return false;
                    }
                }
                return true;
            },

            closeModal() {
                this.editModalOpen = false;
                this.editStep = 1;
                this.editImages = [];
            },

            async submitEditForm() {
                if (!this.validateEditStep(4) || this.isSubmitting) return;
                this.isSubmitting = true;

                const submitBtn = document.querySelector(
                    `#roomFormEdit-${this.roomData.id} button[type="submit"]`);
                const originalText = submitBtn?.innerHTML;

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                `;
                }

                try {
                    const formData = new FormData();

                    // Add all room data
                    for (const [key, value] of Object.entries(this.roomData)) {
                        if (key === 'existingImages') continue;

                        if (key === 'facilities') {
                            // Handle facilities array - convert to JSON string
                            formData.append(key, JSON.stringify(value));
                        } else if (Array.isArray(value)) {
                            value.forEach(item => formData.append(`${key}[]`, item));
                        } else {
                            formData.append(key, value);
                        }
                    }

                    // Append new images
                    this.editImages.forEach((image, index) => {
                        formData.append(`room_images[${index}]`, image.file);
                    });

                    // Append images to delete
                    this.roomData.existingImages
                        .filter(img => img.markedForDeletion)
                        .forEach(img => {
                            formData.append('delete_images[]', img.id);
                        });

                    formData.append('thumbnail_index', this.thumbnailIndex);

                    // Add price values
                    formData.append('daily_price', this.priceTypes.includes('daily') ? this
                        .dailyPrice : 0);
                    formData.append('monthly_price', this.priceTypes.includes('monthly') ? this
                        .monthlyPrice : 0);

                    // Add CSRF token
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                        .content);
                    formData.append('_method', 'PUT');

                    const response = await fetch(document.getElementById(
                        `roomFormEdit-${this.roomData.id}`).action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    let data;
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        data = await response.json();
                    } else {
                        const text = await response.text();
                        throw new Error('Server returned non-JSON response: ' + text.substring(
                            0, 100));
                    }

                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal memperbarui kamar');
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Kamar berhasil diperbarui!',
                        showConfirmButton: false,
                        timer: 1000,
                        timerProgressBar: true,
                        didClose: () => {
                            this.closeModal();
                            window.location.reload();
                        }
                    });
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: `Error: ${error.message}`,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                } finally {
                    this.isSubmitting = false;
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }
            },

            formatPriceInput(event, field) {
                let value = event.target.value.replace(/[^\d]/g, '');

                const numericValue = value ? parseInt(value) : 0;
                if (field === 'daily_price') {
                    this.dailyPrice = numericValue;
                } else {
                    this.monthlyPrice = numericValue;
                }

                this.roomData[field] = formatRupiah(value);

                const formattedValue = this.roomData[field];
                const originalCursorPos = event.target.selectionStart;
                const diff = formattedValue.length - event.target.value.length;

                this.$nextTick(() => {
                    event.target.setSelectionRange(originalCursorPos + diff,
                        originalCursorPos + diff);
                });
            },
        }));
    });

    function formatRupiah(amount) {
        if (amount === null || amount === undefined || amount === '') return '0';
        const num = typeof amount === 'string' ?
            Number(amount.replace(/,/g, '').replace(/\s/g, '')) :
            Number(amount);

        const roundedNum = Math.round(num);

        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(roundedNum);
    }
</script>
