<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti</h1>
            <div class="mt-4 md:mt-0">
                {{-- New Input --}}
                <div x-data="modalProperty()">
                    <!-- Trigger Button -->
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center"
                        type="button" @click.prevent="modalOpenDetail = true;" aria-controls="feedback-modal1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Properti
                    </button>

                    <!-- Modal backdrop -->
                    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                        x-show="modalOpenDetail" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-out duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>

                    <!-- Modal dialog -->
                    <div id="feedback-modal1"
                        class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                        role="dialog" aria-modal="true" x-show="modalOpenDetail"
                        x-transition:enter="transition ease-in-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in-out duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak>

                        <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full flex flex-col text-left"
                            @click.outside="modalOpenDetail = false" @keydown.escape.window="modalOpenDetail = false">

                            <!-- Modal header with step indicator -->
                            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="font-bold text-xl text-gray-800">Tambahkan Properti</div>
                                    <button type="button"
                                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                        @click="modalOpenDetail = false">
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
                                            :class="step >= 1 ? 'bg-blue-600 border-blue-600 text-white' :
                                                'border-gray-300 text-gray-500'">
                                            <span class="text-sm font-semibold" x-show="step < 1">1</span>
                                            <svg x-show="step >= 1" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <p class="font-medium transition-colors duration-300"
                                                :class="step >= 1 ? 'text-blue-600' : 'text-gray-500'">Informasi Dasar
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Connector -->
                                    <div class="w-16 h-0.5 transition-colors duration-300"
                                        :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-300'"></div>

                                    <!-- Step 2 -->
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                            :class="step >= 2 ? 'bg-blue-600 border-blue-600 text-white' :
                                                'border-gray-300 text-gray-500'">
                                            <span class="text-sm font-semibold" x-show="step < 2">2</span>
                                            <svg x-show="step >= 2" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <p class="font-medium transition-colors duration-300"
                                                :class="step >= 2 ? 'text-blue-600' : 'text-gray-500'">Detail Lokasi</p>
                                        </div>
                                    </div>

                                    <!-- Connector -->
                                    <div class="w-16 h-0.5 transition-colors duration-300"
                                        :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-300'"></div>

                                    <!-- Step 3 (Fasilitas) -->
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                            :class="step >= 3 ? 'bg-blue-600 border-blue-600 text-white' :
                                                'border-gray-300 text-gray-500'">
                                            <span class="text-sm font-semibold" x-show="step < 3">3</span>
                                            <svg x-show="step >= 3" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <p class="font-medium transition-colors duration-300"
                                                :class="step >= 3 ? 'text-blue-600' : 'text-gray-500'">Fasilitas</p>
                                        </div>
                                    </div>

                                    <!-- Connector -->
                                    <div class="w-16 h-0.5 transition-colors duration-300"
                                        :class="step >= 4 ? 'bg-blue-600' : 'bg-gray-300'"></div>

                                    <!-- Step 4 (Foto) -->
                                    <div class="flex items-center">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                            :class="step >= 4 ? 'bg-blue-600 border-blue-600 text-white' :
                                                'border-gray-300 text-gray-500'">
                                            <span class="text-sm font-semibold" x-show="step < 4">4</span>
                                            <svg x-show="step >= 4" class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <p class="font-medium transition-colors duration-300"
                                                :class="step >= 4 ? 'text-blue-600' : 'text-gray-500'">Foto</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Modal content -->
                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                <form id="propertyForm" method="POST" action="{{ route('properties.store') }}"
                                    enctype="multipart/form-data" @submit.prevent="submitForm">
                                    @csrf
                                    <!-- Step 1 - Basic Information -->
                                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0">
                                        <div class="space-y-6">
                                            <div>
                                                <label for="property_name"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nama Properti <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" id="property_name" name="property_name"
                                                    required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan nama properti">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                    Jenis Properti <span class="text-red-500">*</span>
                                                </label>
                                                <div class="grid grid-cols-2 gap-4" x-data="{
                                                    types: [
                                                        { label: 'Rumah', value: 'House' },
                                                        { label: 'Apartment', value: 'Apartment' },
                                                        { label: 'Villa', value: 'Villa' },
                                                        { label: 'Hotel', value: 'Hotel' }
                                                    ]
                                                }">
                                                    <template x-for="type in types" :key="type.value">
                                                        <div class="relative">
                                                            <input :id="'type-' + type.value" name="property_type"
                                                                type="radio" :value="type.value"
                                                                class="sr-only peer" required>
                                                            <label :for="'type-' + type.value"
                                                                class="flex items-center justify-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                <span x-text="type.label"></span>
                                                            </label>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <div>
                                                <label for="description"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Deskripsi <span class="text-red-500">*</span>
                                                </label>
                                                <textarea id="description" name="description" rows="4" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Deskripsikan properti Anda..."></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2 - Location Details -->
                                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                        <div class="space-y-6">
                                            <div>
                                                <label for="full_address"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Alamat Lengkap <span class="text-red-500">*</span>
                                                </label>
                                                <textarea id="full_address" name="full_address" rows="3" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan alamat lengkap properti"></textarea>
                                            </div>

                                            <div>
                                                <label
                                                    class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                                    Pinpoint Lokasi <span class="text-red-500 ml-1">*</span>
                                                    <span class="text-gray-500 text-sm font-normal ml-2">(Klik untuk
                                                        menandai langsung pada peta)</span>
                                                </label>
                                                <div id="map"
                                                    class="h-64 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                                    <div class="text-gray-500 text-center">
                                                        <svg class="w-12 h-12 mx-auto mb-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                            </path>
                                                        </svg>
                                                        <p>Klik untuk menentukan lokasi</p>
                                                    </div>
                                                </div>
                                                <div id="coordinates" class="mt-2 text-sm text-gray-500"></div>
                                                <input type="hidden" id="latitude" name="latitude">
                                                <input type="hidden" id="longitude" name="longitude">
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="province"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Provinsi <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" id="province" name="province" required
                                                        placeholder="Masukkan Provinsi"
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                                </div>

                                                <div>
                                                    <label for="city"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Kota/Kabupaten <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" id="city" name="city" required
                                                        placeholder="Masukkan Kota atau Kabupaten"
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="district"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Kecamatan <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" id="district" name="district" required
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Masukkan kecamatan">
                                                </div>

                                                <div>
                                                    <label for="village"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Kelurahan <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" id="village" name="village" required
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Masukkan kelurahan">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="postal_code"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Kode Pos
                                                    </label>
                                                    <input type="text" id="postal_code" name="postal_code"
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Masukkan kode pos">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 3 - Facilities -->
                                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                        <div class="space-y-6">
                                            <!-- Combined Facilities (Amenities + Features) -->
                                            <div x-data="{ facilities: ['High-speed WiFi', 'Parking', 'Swimming Pool', 'Gym', 'Restaurant', '24/7 Security', 'Concierge', 'Laundry Service', 'Room Service'] }">
                                                <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                    </svg>
                                                    Fasilitas Properti
                                                </h3>
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                    <template x-for="(item, index) in facilities"
                                                        :key="index">
                                                        <div class="relative">
                                                            <input :id="'facility-' + index" name="facilities[]"
                                                                type="checkbox" :value="item"
                                                                class="sr-only peer">
                                                            <label :for="'facility-' + index"
                                                                class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                <span x-text="item"></span>
                                                            </label>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4 - Photos -->
                                    <div x-show="step === 4" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                        <div class="space-y-6">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                    Foto Properti <span class="text-red-500">*</span>
                                                    <span class="text-sm font-normal text-gray-500">
                                                        (Wajib 3 foto - <span x-text="remainingSlots"></span> foto
                                                        lagi)
                                                    </span>
                                                </label>

                                                <!-- Upload Area -->
                                                <div x-show="canUploadMore" @drop="handleDrop($event)"
                                                    @dragover.prevent @dragenter.prevent
                                                    class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200 cursor-pointer"
                                                    :class="{ 'border-blue-400 bg-blue-50': canUploadMore }">
                                                    <div class="space-y-2">
                                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                        <div class="flex text-sm text-gray-600 justify-center">
                                                            <label for="property_images"
                                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                                <span>Upload foto</span>
                                                                <input id="property_images" name="property_images[]"
                                                                    type="file" multiple accept="image/*"
                                                                    @change="handleFileSelect($event)"
                                                                    class="sr-only">
                                                            </label>
                                                            <p class="pl-1">atau drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                                        <p class="text-xs text-blue-600"
                                                            x-text="`Dapat upload ${remainingSlots} foto lagi`"></p>
                                                    </div>
                                                </div>

                                                <!-- Full Upload Message -->
                                                <div x-show="!canUploadMore"
                                                    class="border-2 border-green-300 rounded-lg p-8 text-center bg-green-50">
                                                    <div class="space-y-2">
                                                        <svg class="w-12 h-12 mx-auto text-green-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        <p class="text-sm text-green-600 font-medium">3 foto telah
                                                            diupload!</p>
                                                        <p class="text-xs text-green-500">Semua slot foto telah terisi
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Image Preview Grid -->
                                                <div x-show="images.length > 0" class="mt-6 grid grid-cols-3 gap-4"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100">
                                                    <template x-for="(image, index) in images" :key="index">
                                                        <div class="relative group">
                                                            <!-- Image Container -->
                                                            <div
                                                                class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200 hover:border-blue-400 transition-colors duration-200">
                                                                <img :src="image.url"
                                                                    :alt="`Preview ${index + 1}`"
                                                                    class="w-full h-full object-cover">
                                                            </div>

                                                            <!-- Remove Button -->
                                                            <button @click="removeImage(index)"
                                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>

                                                            <!-- Image Number Badge -->
                                                            <div
                                                                class="absolute bottom-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                                                <span x-text="index + 1"></span>
                                                            </div>

                                                            <!-- File Name -->
                                                            <div class="mt-2">
                                                                <p class="text-xs text-gray-600 truncate"
                                                                    x-text="image.name"></p>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Progress Indicator -->
                                                <div class="mt-4">
                                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                                        <span>Progress Upload</span>
                                                        <span x-text="`${images.length}/${maxImages} foto`"></span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                            :style="`width: ${(images.length / maxImages) * 100}%`">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Validation Message -->
                                                <div x-show="images.length < 3" class="mt-3">
                                                    <p class="text-sm text-red-600">
                                                        <span class="font-medium">Perhatian:</span>
                                                        Anda harus mengupload tepat 3 foto untuk melanjutkan.
                                                    </p>
                                                </div>

                                                <div x-show="images.length === 3" class="mt-3">
                                                    <p class="text-sm text-green-600">
                                                        <span class="font-medium">Sempurna!</span>
                                                        Semua foto telah diupload.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="mt-6 flex justify-end">
                                        <div>
                                            <button type="button" x-show="step > 1" @click="step--"
                                                class="px-6 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                                Sebelumnya
                                            </button>
                                            <button type="button" x-show="step < 4"
                                                @click="validateStep(step) && step++"
                                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                Selanjutnya
                                                <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                            <button type="submit" x-show="step === 4"
                                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Search and Filter Section -->
            <div class="p-4 border-b border-gray-200">
                <form id="searchForm" method="GET" action="">
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
                                <input type="text" name="search" id="searchInput"
                                    value="{{ request('search') }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Cari properti...">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Status:</span>
                            <select name="status" id="statusFilter"
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Semua</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive
                                </option>
                            </select>
                        </div>

                        <!-- Items per Page -->
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 mr-2">Per halaman:</span>
                            <select name="per_page" id="perPageSelect" onchange="this.form.submit()"
                                class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10
                                </option>
                                <option value="15" {{ request('per_page', 5) == 15 ? 'selected' : '' }}>15
                                </option>
                                <option value="20" {{ request('per_page', 5) == 20 ? 'selected' : '' }}>20
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
                                Nama</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Provinsi</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Penambahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Perubahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ditambahkan Oleh</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($properties as $property)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img src="data:image/jpeg;base64,{{ $property->image }}"
                                                alt="Property Image" class="w-full h-full object-cover rounded" />
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $property->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                    <div class="text-sm font-medium text-gray-900">{{ $property->province }}</div>
                                    <div class="text-sm text-gray-500">{{ $property->city }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($property->created_at)->format('Y M d') }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($property->created_at)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                    @if ($property->updated_at)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($property->updated_at)->format('Y M d') }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($property->updated_at)->format('H:i') }}</div>
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $property->creator->username ?? 'Unknown' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" value="" class="sr-only peer"
                                            data-id="{{ $property->idrec }}" {{ $property->status ? 'checked' : '' }}
                                            onchange="toggleStatus(this)">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-gray-900">
                                            {{ $property->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center items-left space-x-3">
                                        <!-- View -->
                                        <div x-data="modalView()" class="relative group">
                                            @php
                                                $features = json_encode(
                                                    $property->features,
                                                    JSON_HEX_APOS | JSON_HEX_QUOT,
                                                );
                                            @endphp
                                            <button
                                                class="text-blue-500 hover:text-blue-700 transition-colors duration-200"
                                                type="button"
                                                @click.prevent='openModal({
                                                                name: @json($property->name),
                                                                city: @json($property->city),
                                                                province: @json($property->province),
                                                                description: @json($property->description),
                                                                created_at: "{{ \Carbon\Carbon::parse($property->created_at)->format('Y-m-d H:i') }}",
                                                                updated_at: "{{ $property->updated_at ? \Carbon\Carbon::parse($property->updated_at)->format('Y-m-d H:i') : '-' }}",
                                                                creator: "{{ $property->creator->username ?? 'Unknown' }}",
                                                                status: "{{ $property->status ? 'Active' : 'Inactive' }}",
                                                                images: [
                                                                    "data:image/jpeg;base64,{{ $property->image }}",
                                                                    "data:image/jpeg;base64,{{ $property->image2 }}",
                                                                    "data:image/jpeg;base64,{{ $property->image3 }}"
                                                                ],
                                                                location: @json($property->location),
                                                                distance: @json($property->distance),
                                                                features: {!! $features !!},                                                                                                                                                                                              
                                                            })'
                                                aria-controls="property-detail-modal" title="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <!-- Modal backdrop -->
                                            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 transition-opacity"
                                                x-show="modalOpenDetail"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-out duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak>
                                            </div>

                                            <!-- Modal dialog -->
                                            <div id="property-detail-modal"
                                                class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4"
                                                role="dialog" aria-modal="true" x-show="modalOpenDetail"
                                                x-transition:enter="transition ease-in-out duration-300"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in-out duration-200"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95" x-cloak>

                                                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl max-h-[95vh] flex flex-col"
                                                    @click.outside="modalOpenDetail = false"
                                                    @keydown.escape.window="modalOpenDetail = false">

                                                    <!-- Modal header -->
                                                    <div
                                                        class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                                                        <div class="text-left">
                                                            <h3 class="text-2xl font-bold text-gray-900 mb-1"
                                                                x-text="selectedProperty.name"></h3>
                                                            <p class="text-gray-600 flex items-center">
                                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                </svg>
                                                                <span
                                                                    x-text="selectedProperty.city + ', ' + selectedProperty.province"></span>
                                                            </p>
                                                        </div>
                                                        <button type="button"
                                                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-2 hover:bg-white rounded-full"
                                                            @click="modalOpenDetail = false">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Modal content -->
                                                    <div class="overflow-y-auto flex-1">
                                                        <!-- Property image slider -->
                                                        <div class="relative h-72 overflow-hidden bg-gray-200">
                                                            <!-- Images -->
                                                            <div class="flex h-full transition-transform duration-300 ease-in-out"
                                                                :style="'transform: translateX(-' + (selectedProperty
                                                                    .currentImageIndex * 100) + '%)'">
                                                                <template
                                                                    x-for="(image, index) in selectedProperty.images"
                                                                    :key="index">
                                                                    <img :src="image" alt="Property Image"
                                                                        class="w-full h-full object-cover object-center flex-shrink-0">
                                                                </template>
                                                            </div>

                                                            <!-- Navigation arrows -->
                                                            <button x-show="selectedProperty.images.length > 1"
                                                                @click="selectedProperty.currentImageIndex = (selectedProperty.currentImageIndex - 1 + selectedProperty.images.length) % selectedProperty.images.length"
                                                                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 shadow-md">
                                                                <svg class="w-6 h-6 text-gray-800" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 19l-7-7 7-7" />
                                                                </svg>
                                                            </button>
                                                            <button x-show="selectedProperty.images.length > 1"
                                                                @click="selectedProperty.currentImageIndex = (selectedProperty.currentImageIndex + 1) % selectedProperty.images.length"
                                                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 shadow-md">
                                                                <svg class="w-6 h-6 text-gray-800" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 5l7 7-7 7" />
                                                                </svg>
                                                            </button>

                                                            <!-- Status badge -->
                                                            <div
                                                                class="absolute top-4 right-4 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-full shadow-lg">
                                                                <span
                                                                    :class="selectedProperty && selectedProperty
                                                                        .status === 'Active' ?
                                                                        'text-green-600 font-semibold' :
                                                                        'text-red-600 font-semibold'"
                                                                    class="text-sm flex items-center">
                                                                    <span class="w-2.5 h-2.5 rounded-full mr-2 block"
                                                                        :class="selectedProperty && selectedProperty
                                                                            .status === 'Active' ? 'bg-green-500' :
                                                                            'bg-red-500'"></span>
                                                                    <span
                                                                        x-text="selectedProperty && selectedProperty.status ? selectedProperty.status : ''"></span>
                                                                </span>
                                                            </div>

                                                            <!-- Image indicators -->
                                                            <div x-show="selectedProperty.images.length > 1"
                                                                class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                                                                <template
                                                                    x-for="(image, index) in selectedProperty.images"
                                                                    :key="index">
                                                                    <button
                                                                        @click="selectedProperty.currentImageIndex = index"
                                                                        class="w-3 h-3 rounded-full transition-all"
                                                                        :class="selectedProperty.currentImageIndex === index ?
                                                                            'bg-white w-6' : 'bg-white/50'"></button>
                                                                </template>
                                                            </div>
                                                        </div>
                                                        <div class="p-6 space-y-8">
                                                            <!-- Description -->
                                                            <div class="text-center">
                                                                <p class="text-gray-700 text-lg leading-relaxed whitespace-pre-line"
                                                                    x-text="selectedProperty.description"></p>
                                                            </div>

                                                            <!-- Property Info Grid -->
                                                            <div
                                                                class="grid grid-cols-4 grid-rows-1 gap-4 bg-gray-50 p-6 rounded-xl items-center justify-items-center">
                                                                <div
                                                                    class="flex flex-col items-center justify-center text-center">
                                                                    <div class="flex items-center space-x-3">
                                                                        <svg class="w-5 h-5 text-indigo-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                        </svg>
                                                                        <div>
                                                                            <p
                                                                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                                Added By</p>
                                                                            <p class="text-gray-800 font-medium"
                                                                                x-text="selectedProperty.creator"></p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="flex flex-col items-center justify-center text-center">
                                                                    <div class="flex items-center space-x-3">
                                                                        <svg class="w-5 h-5 text-red-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        </svg>
                                                                        <div>
                                                                            <p
                                                                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                                Location</p>
                                                                            <a class="text-gray-800 font-medium underline hover:text-blue-600"
                                                                                :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(selectedProperty.location ? selectedProperty.location : selectedProperty.city + ', ' + selectedProperty.province)}`"
                                                                                target="_blank">
                                                                                Click here for Maps
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="flex flex-col items-center justify-center text-center">
                                                                    <div class="flex items-center space-x-3">
                                                                        <svg class="w-5 h-5 text-blue-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        <div>
                                                                            <p
                                                                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                                Added</p>
                                                                            <p class="text-gray-800 font-medium"
                                                                                x-text="selectedProperty.created_at">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="flex flex-col items-center justify-center text-center">
                                                                    <div class="flex items-center space-x-3">
                                                                        <svg class="w-5 h-5 text-green-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                        </svg>
                                                                        <div>
                                                                            <p
                                                                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                                Last Updated</p>
                                                                            <p class="text-gray-800 font-medium"
                                                                                x-text="selectedProperty.updated_at">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Features Section -->
                                                            <div x-show="selectedProperty.features && selectedProperty.features.length > 0"
                                                                class="space-y-4">
                                                                <div class="flex items-center space-x-2 mb-4">
                                                                    <svg class="w-6 h-6 text-green-500" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <h4 class="text-lg font-bold text-gray-900">
                                                                        Features</h4>
                                                                </div>
                                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                                    <template
                                                                        x-for="feature in selectedProperty.features">
                                                                        <div
                                                                            class="flex items-center space-x-3 bg-green-50 p-3 rounded-lg border border-green-100">
                                                                            <!-- Feature Icons -->
                                                                            <template
                                                                                x-if="feature === '24/7 Security'">
                                                                                <svg class="h-5 w-5 text-green-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="feature === 'Concierge'">
                                                                                <svg class="h-5 w-5 text-purple-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === 'Laundry Service'">
                                                                                <svg class="h-5 w-5 text-indigo-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <rect x="4" y="4" width="16"
                                                                                        height="16" rx="2"
                                                                                        stroke-width="1.5" />
                                                                                    <rect x="5" y="5" width="14"
                                                                                        height="14" rx="1"
                                                                                        stroke-width="1.5" />
                                                                                    <circle cx="12"
                                                                                        cy="12" r="4"
                                                                                        stroke-width="1.5" />
                                                                                    <circle cx="12"
                                                                                        cy="12" r="3"
                                                                                        stroke-width="1"
                                                                                        stroke-dasharray="1.5,1" />
                                                                                    <circle cx="15"
                                                                                        cy="12" r="0.5"
                                                                                        fill="currentColor" />
                                                                                    <circle cx="18"
                                                                                        cy="7" r="0.8"
                                                                                        fill="currentColor" />
                                                                                    <circle cx="18"
                                                                                        cy="9.5" r="0.8"
                                                                                        fill="currentColor" />
                                                                                    <rect x="6" y="6" width="3"
                                                                                        height="1.5" rx="0.5"
                                                                                        stroke-width="0.5" />
                                                                                    <rect x="6" y="9" width="3"
                                                                                        height="1.5" rx="0.5"
                                                                                        stroke-width="0.5" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === 'Room Service'">
                                                                                <svg class="h-5 w-5 text-pink-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0M8 5v4m4-4v4m4-4v4" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === 'High-speed WiFi'">
                                                                                <svg class="h-5 w-5 text-blue-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="feature === 'Parking'">
                                                                                <svg class="h-5 w-5 text-yellow-600"
                                                                                    viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <rect x="3" y="3" width="18"
                                                                                        height="18" rx="2"
                                                                                        stroke-width="2"
                                                                                        fill="none" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2.5"
                                                                                        d="M9 7h4a3 3 0 1 1 0 6H9v5"
                                                                                        fill="none" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-width="2.5" d="M9 7v11"
                                                                                        fill="none" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === 'Swimming Pool'">
                                                                                <svg class="h-5 w-5 text-cyan-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M2 16c2 2 5 2 7 0s5-2 7 0 5 2 7 0M2 12c2 2 5 2 7 0s5-2 7 0 5 2 7 0M2 8c2 2 5 2 7 0s5-2 7 0 5 2 7 0" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="1.5"
                                                                                        d="M8 4c1 1 2 1 3 0M16 4c1 1 2 1 3 0" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="feature === 'Gym'">
                                                                                <svg class="h-5 w-5 text-red-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="3" d="M8 12h8" />
                                                                                    <circle cx="6"
                                                                                        cy="12" r="3"
                                                                                        stroke-width="2" />
                                                                                    <circle cx="6"
                                                                                        cy="12" r="1.5"
                                                                                        stroke-width="1"
                                                                                        fill="currentColor" />
                                                                                    <circle cx="18"
                                                                                        cy="12" r="3"
                                                                                        stroke-width="2" />
                                                                                    <circle cx="18"
                                                                                        cy="12" r="1.5"
                                                                                        stroke-width="1"
                                                                                        fill="currentColor" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-width="2"
                                                                                        d="M4 12h2M18 12h2" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="feature === 'Restaurant'">
                                                                                <svg class="h-5 w-5 text-orange-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M5 10h14a2 2 0 010 4H5a2 2 0 010-4z" />
                                                                                    <rect x="6" y="12" width="12"
                                                                                        height="2" rx="1"
                                                                                        stroke-width="1.5" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-width="1.2"
                                                                                        d="M8 14.5h8M8 15.5h8M8 16.5h8" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M5 18h14a2 2 0 010 4H5a2 2 0 010-4z" />
                                                                                </svg>
                                                                            </template>
                                                                            <span x-text="feature"
                                                                                class="text-gray-800 font-medium text-sm"></span>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div
                                                        class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                                                        <div class="text-sm text-gray-500">
                                                            <span>Press ESC or click outside to close</span>
                                                        </div>
                                                        <div class="flex space-x-3">
                                                            <button @click="modalOpenDetail = false"
                                                                class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-all duration-200 font-medium hover:shadow-md">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit -->
                                        <div x-data="modalPropertyEdit({{ $property }})" class="relative group">
                                            @php
                                                $features = json_encode(
                                                    $property->features,
                                                    JSON_HEX_APOS | JSON_HEX_QUOT,
                                                );
                                                $attributes = json_encode(
                                                    $property->attributes,
                                                    JSON_HEX_APOS | JSON_HEX_QUOT,
                                                );
                                            @endphp

                                            <button class="text-amber-600 hover:text-amber-900" type="button"
                                                @click.prevent='openModal({
                                                                    name: @json($property->name),
                                                                    city: @json($property->city),
                                                                    province: @json($property->province),
                                                                    description: @json($property->description),
                                                                    created_at: "{{ \Carbon\Carbon::parse($property->created_at)->format('Y-m-d H:i') }}",
                                                                    updated_at: "{{ $property->updated_at ? \Carbon\Carbon::parse($property->updated_at)->format('Y-m-d H:i') : '-' }}",
                                                                    creator: "{{ $property->creator->username ?? 'Unknown' }}",
                                                                    status: "{{ $property->status ? 'Active' : 'Inactive' }}",
                                                                    location: @json($property->location),
                                                                    images: [
                                                                                "data:image/jpeg;base64,{{ $property->image }}",
                                                                                "data:image/jpeg;base64,{{ $property->image2 }}",
                                                                                "data:image/jpeg;base64,{{ $property->image3 }}"
                                                                            ],
                                                                    distance: @json($property->distance),
                                                                    features: {!! $features !!},
                                                                    attributes: {!! $attributes !!}
                                                                })'
                                                aria-controls="property-edit-modal" title="Edit Property">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </button>

                                            <!-- Modal backdrop -->
                                            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                                                x-show="modalOpenDetail"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-out duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>

                                            <!-- Modal dialog -->
                                            <div id="property-edit-modal"
                                                class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                                role="dialog" aria-modal="true" x-show="modalOpenDetail"
                                                x-transition:enter="transition ease-in-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave="transition ease-in-out duration-200"
                                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak>

                                                <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full flex flex-col text-left"
                                                    @click.outside="modalOpenDetail = false"
                                                    @keydown.escape.window="modalOpenDetail = false">

                                                    <!-- Modal header with step indicator -->
                                                    <div
                                                        class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <div class="font-bold text-xl text-gray-800">Edit Properti
                                                            </div>
                                                            <button type="button"
                                                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                                @click="modalOpenDetail = false">
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
                                                                    :class="step >= 1 ?
                                                                        'bg-blue-600 border-blue-600 text-white' :
                                                                        'border-gray-300 text-gray-500'">
                                                                    <span class="text-sm font-semibold"
                                                                        x-show="step < 1">1</span>
                                                                    <svg x-show="step >= 1" class="w-5 h-5"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </div>
                                                                <div class="ml-3 text-sm">
                                                                    <p class="font-medium transition-colors duration-300"
                                                                        :class="step >= 1 ? 'text-blue-600' : 'text-gray-500'">
                                                                        Informasi Dasar
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <!-- Connector -->
                                                            <div class="w-16 h-0.5 transition-colors duration-300"
                                                                :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-300'">
                                                            </div>

                                                            <!-- Step 2 -->
                                                            <div class="flex items-center">
                                                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                                    :class="step >= 2 ?
                                                                        'bg-blue-600 border-blue-600 text-white' :
                                                                        'border-gray-300 text-gray-500'">
                                                                    <span class="text-sm font-semibold"
                                                                        x-show="step < 2">2</span>
                                                                    <svg x-show="step >= 2" class="w-5 h-5"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </div>
                                                                <div class="ml-3 text-sm">
                                                                    <p class="font-medium transition-colors duration-300"
                                                                        :class="step >= 2 ? 'text-blue-600' : 'text-gray-500'">
                                                                        Detail Lokasi</p>
                                                                </div>
                                                            </div>

                                                            <!-- Connector -->
                                                            <div class="w-16 h-0.5 transition-colors duration-300"
                                                                :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-300'">
                                                            </div>

                                                            <!-- Step 3 (Fasilitas) -->
                                                            <div class="flex items-center">
                                                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                                    :class="step >= 3 ?
                                                                        'bg-blue-600 border-blue-600 text-white' :
                                                                        'border-gray-300 text-gray-500'">
                                                                    <span class="text-sm font-semibold"
                                                                        x-show="step < 3">3</span>
                                                                    <svg x-show="step >= 3" class="w-5 h-5"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </div>
                                                                <div class="ml-3 text-sm">
                                                                    <p class="font-medium transition-colors duration-300"
                                                                        :class="step >= 3 ? 'text-blue-600' : 'text-gray-500'">
                                                                        Fasilitas</p>
                                                                </div>
                                                            </div>

                                                            <!-- Connector -->
                                                            <div class="w-16 h-0.5 transition-colors duration-300"
                                                                :class="step >= 4 ? 'bg-blue-600' : 'bg-gray-300'">
                                                            </div>

                                                            <!-- Step 4 (Foto) -->
                                                            <div class="flex items-center">
                                                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                                    :class="step >= 4 ?
                                                                        'bg-blue-600 border-blue-600 text-white' :
                                                                        'border-gray-300 text-gray-500'">
                                                                    <span class="text-sm font-semibold"
                                                                        x-show="step < 4">4</span>
                                                                    <svg x-show="step >= 4" class="w-5 h-5"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </div>
                                                                <div class="ml-3 text-sm">
                                                                    <p class="font-medium transition-colors duration-300"
                                                                        :class="step >= 4 ? 'text-blue-600' : 'text-gray-500'">
                                                                        Foto</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal content -->
                                                    <div class="flex-1 overflow-y-auto px-6 py-6">
                                                        <form id="propertyFormEdit" method="POST"
                                                            action="{{ route('properties.update', $property->idrec) }}"
                                                            enctype="multipart/form-data"
                                                            @submit.prevent="submitForm">
                                                            @csrf
                                                            @method('PUT')

                                                            <!-- Step 1 - Basic Information -->
                                                            <div x-show="step === 1"
                                                                x-transition:enter="transition ease-out duration-300"
                                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                                x-transition:enter-end="opacity-100 translate-x-0">
                                                                <div class="space-y-6">
                                                                    <div>
                                                                        <label for="property_name_edit"
                                                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                                                            Nama Properti <span
                                                                                class="text-red-500">*</span>
                                                                        </label>
                                                                        <input type="text" id="property_name_edit"
                                                                            name="property_name" required
                                                                            x-model="propertyData.name"
                                                                            class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                            placeholder="Masukkan nama properti">
                                                                    </div>

                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-semibold text-gray-700 mb-3">
                                                                            Jenis Properti <span
                                                                                class="text-red-500">*</span>
                                                                        </label>
                                                                        <div class="grid grid-cols-2 gap-4"
                                                                            x-data="{
                                                                                types: [
                                                                                    { label: 'Rumah', value: 'House' },
                                                                                    { label: 'Apartment', value: 'Apartment' },
                                                                                    { label: 'Villa', value: 'Villa' },
                                                                                    { label: 'Hotel', value: 'Hotel' }
                                                                                ],
                                                                                selectedType: propertyData.type
                                                                            }">
                                                                            <template x-for="type in types"
                                                                                :key="type.value">
                                                                                <div class="relative">
                                                                                    <input
                                                                                        :id="'type-edit-' + type.value"
                                                                                        name="property_type"
                                                                                        type="radio"
                                                                                        :value="type.value"
                                                                                        class="sr-only peer" required
                                                                                        x-model="selectedType"
                                                                                        @change="propertyData.type = type.value">
                                                                                    <label
                                                                                        :for="'type-edit-' + type.value"
                                                                                        class="flex items-center justify-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                                        <span
                                                                                            x-text="type.label"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                    </div>

                                                                    <div>
                                                                        <label for="description_edit"
                                                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                                                            Deskripsi <span
                                                                                class="text-red-500">*</span>
                                                                        </label>
                                                                        <textarea id="description_edit" name="description" rows="4" required x-model="propertyData.description"
                                                                            class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                            placeholder="Deskripsikan properti Anda..."></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Step 2 - Location Details -->
                                                            <div x-show="step === 2"
                                                                x-transition:enter="transition ease-out duration-300"
                                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                                x-transition:enter-end="opacity-100 translate-x-0"
                                                                x-cloak>
                                                                <div class="space-y-6">
                                                                    <div>
                                                                        <label for="full_address_edit"
                                                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                                                            Alamat Lengkap <span
                                                                                class="text-red-500">*</span>
                                                                        </label>
                                                                        <textarea id="full_address_edit" name="full_address" rows="3" required x-model="propertyData.address"
                                                                            class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                            placeholder="Masukkan alamat lengkap properti"></textarea>
                                                                    </div>

                                                                    <div>
                                                                        <label
                                                                            class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                                                            Pinpoint Lokasi <span
                                                                                class="text-red-500 ml-1">*</span>
                                                                            <span
                                                                                class="text-gray-500 text-sm font-normal ml-2">(Klik
                                                                                untuk
                                                                                menandai langsung pada peta)</span>
                                                                        </label>
                                                                        <div id="map_edit"
                                                                            class="h-64 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                                                            <div class="text-gray-500 text-center">
                                                                                <svg class="w-12 h-12 mx-auto mb-2"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                                    </path>
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                                                    </path>
                                                                                </svg>
                                                                                <p>Klik untuk menentukan lokasi</p>
                                                                            </div>
                                                                        </div>
                                                                        <div id="coordinates_edit"
                                                                            class="mt-2 text-sm text-gray-500"></div>
                                                                        <input type="hidden" id="latitude_edit"
                                                                            name="latitude"
                                                                            x-model="propertyData.latitude">
                                                                        <input type="hidden" id="longitude_edit"
                                                                            name="longitude"
                                                                            x-model="propertyData.longitude">
                                                                    </div>

                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label for="province_edit"
                                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                                Provinsi <span
                                                                                    class="text-red-500">*</span>
                                                                            </label>
                                                                            <input type="text" id="province_edit"
                                                                                name="province" required
                                                                                x-model="propertyData.province"
                                                                                placeholder="Masukkan Provinsi"
                                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                                                        </div>

                                                                        <div>
                                                                            <label for="city_edit"
                                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                                Kota/Kabupaten <span
                                                                                    class="text-red-500">*</span>
                                                                            </label>
                                                                            <input type="text" id="city_edit"
                                                                                name="city" required
                                                                                x-model="propertyData.city"
                                                                                placeholder="Masukkan Kota atau Kabupaten"
                                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label for="district_edit"
                                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                                Kecamatan <span
                                                                                    class="text-red-500">*</span>
                                                                            </label>
                                                                            <input type="text" id="district_edit"
                                                                                name="district" required
                                                                                x-model="propertyData.subdistrict"
                                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                                placeholder="Masukkan kecamatan">
                                                                        </div>

                                                                        <div>
                                                                            <label for="village_edit"
                                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                                Kelurahan <span
                                                                                    class="text-red-500">*</span>
                                                                            </label>
                                                                            <input type="text" id="village_edit"
                                                                                name="village" required
                                                                                x-model="propertyData.village"
                                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                                placeholder="Masukkan kelurahan">
                                                                        </div>
                                                                    </div>

                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label for="postal_code_edit"
                                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                                Kode Pos
                                                                            </label>
                                                                            <input type="text"
                                                                                id="postal_code_edit"
                                                                                name="postal_code"
                                                                                x-model="propertyData.postal_code"
                                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                                placeholder="Masukkan kode pos">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Step 3 - Facilities -->
                                                            <div x-show="step === 3"
                                                                x-transition:enter="transition ease-out duration-300"
                                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                                x-transition:enter-end="opacity-100 translate-x-0"
                                                                x-cloak>
                                                                <div class="space-y-6">
                                                                    <!-- Combined Facilities (Amenities + Features) -->
                                                                    <div x-data="{ facilities: ['High-speed WiFi', 'Parking', 'Swimming Pool', 'Gym', 'Restaurant', '24/7 Security', 'Concierge', 'Laundry Service', 'Room Service'] }">
                                                                        <h3
                                                                            class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                            <svg class="w-5 h-5 mr-2 text-blue-600"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                                            </svg>
                                                                            Fasilitas Properti
                                                                        </h3>
                                                                        <div
                                                                            class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                                            <template
                                                                                x-for="(item, index) in facilities"
                                                                                :key="index">
                                                                                <div class="relative">
                                                                                    <input
                                                                                        :id="'facility-edit-' + index"
                                                                                        name="facilities[]"
                                                                                        type="checkbox"
                                                                                        :value="item"
                                                                                        class="sr-only peer"
                                                                                        x-model="propertyData.features"
                                                                                        :checked="propertyData.features.includes(
                                                                                            item)">
                                                                                    <label
                                                                                        :for="'facility-edit-' + index"
                                                                                        class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                                        <span x-text="item"></span>
                                                                                    </label>
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Step 4 - Photos -->
                                                            <div x-show="step === 4"
                                                                x-transition:enter="transition ease-out duration-300"
                                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                                x-transition:enter-end="opacity-100 translate-x-0"
                                                                x-cloak>
                                                                <div class="space-y-6">
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-semibold text-gray-700 mb-3">
                                                                            Foto Properti <span
                                                                                class="text-red-500">*</span>
                                                                            <span
                                                                                class="text-sm font-normal text-gray-500">
                                                                                (Wajib 3 foto - <span
                                                                                    x-text="remainingSlots"></span>
                                                                                foto
                                                                                lagi)
                                                                            </span>
                                                                        </label>

                                                                        <!-- Upload Area -->
                                                                        <div x-show="canUploadMore"
                                                                            @drop="handleDrop($event)"
                                                                            @dragover.prevent @dragenter.prevent
                                                                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200 cursor-pointer mt-4"
                                                                            :class="{ 'border-blue-400 bg-blue-50': canUploadMore }">
                                                                            <div class="space-y-2">
                                                                                <svg class="w-12 h-12 mx-auto text-gray-400"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                    </path>
                                                                                </svg>
                                                                                <div
                                                                                    class="flex text-sm text-gray-600 justify-center">
                                                                                    <label for="property_images_edit"
                                                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                                                        <span>Upload foto</span>
                                                                                        <input
                                                                                            id="property_images_edit"
                                                                                            name="property_images[]"
                                                                                            type="file" multiple
                                                                                            accept="image/*"
                                                                                            @change="handleFileSelect($event)"
                                                                                            class="sr-only">
                                                                                    </label>
                                                                                    <p class="pl-1">atau drag and
                                                                                        drop</p>
                                                                                </div>
                                                                                <p class="text-xs text-gray-500">PNG,
                                                                                    JPG, JPEG up to 5MB</p>
                                                                                <p class="text-xs text-blue-600"
                                                                                    x-text="`Dapat upload ${remainingSlots} foto lagi`">
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Full Upload Message -->
                                                                        <div x-show="!canUploadMore"
                                                                            class="border-2 border-green-300 rounded-lg p-8 text-center bg-green-50 mt-4">
                                                                            <div class="space-y-2">
                                                                                <svg class="w-12 h-12 mx-auto text-green-500"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M5 13l4 4L19 7"></path>
                                                                                </svg>
                                                                                <p
                                                                                    class="text-sm text-green-600 font-medium">
                                                                                    3 foto telah diupload!</p>
                                                                                <p class="text-xs text-green-500">
                                                                                    Semua slot foto telah terisi</p>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Image Preview Grid -->
                                                                        <div x-show="images.length > 0"
                                                                            class="mt-6 grid grid-cols-3 gap-4"
                                                                            x-transition:enter="transition ease-out duration-300"
                                                                            x-transition:enter-start="opacity-0 scale-95"
                                                                            x-transition:enter-end="opacity-100 scale-100">
                                                                            <template x-for="(image, index) in images"
                                                                                :key="'new-' + index">
                                                                                <div class="relative group">
                                                                                    <!-- Image Container -->
                                                                                    <div
                                                                                        class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 border-gray-200 hover:border-blue-400 transition-colors duration-200">
                                                                                        <img :src="image.url"
                                                                                            :alt="`New Image ${index + 1}`"
                                                                                            class="w-full h-full object-cover">
                                                                                    </div>

                                                                                    <!-- Remove Button -->
                                                                                    <button
                                                                                        @click="removeImage(index)"
                                                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                                        <svg class="w-3 h-3"
                                                                                            fill="none"
                                                                                            stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M6 18L18 6M6 6l12 12">
                                                                                            </path>
                                                                                        </svg>
                                                                                    </button>

                                                                                    <!-- Image Number Badge -->
                                                                                    <div
                                                                                        class="absolute bottom-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-medium">
                                                                                        <span
                                                                                            x-text="propertyData.existingImages.length + index + 1"></span>
                                                                                    </div>

                                                                                    <!-- File Name -->
                                                                                    <div class="mt-2">
                                                                                        <p class="text-xs text-gray-600 truncate"
                                                                                            x-text="image.name"></p>
                                                                                    </div>
                                                                                </div>
                                                                            </template>
                                                                        </div>

                                                                        <!-- Progress Indicator -->
                                                                        <div class="mt-4">
                                                                            <div
                                                                                class="flex justify-between text-sm text-gray-600 mb-2">
                                                                                <span>Progress Upload</span>
                                                                                <span
                                                                                    x-text="`${images.length + propertyData.existingImages.length}/${maxImages} foto`"></span>
                                                                            </div>
                                                                            <div
                                                                                class="w-full bg-gray-200 rounded-full h-2">
                                                                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                                                    :style="`width: ${((images.length + propertyData.existingImages.length) / maxImages) * 100}%`">
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Validation Message -->
                                                                        <div x-show="images.length + propertyData.existingImages.length < 3"
                                                                            class="mt-3">
                                                                            <p class="text-sm text-red-600">
                                                                                <span
                                                                                    class="font-medium">Perhatian:</span>
                                                                                Anda harus mengupload tepat 3 foto untuk
                                                                                melanjutkan.
                                                                            </p>
                                                                        </div>

                                                                        <div x-show="images.length + propertyData.existingImages.length === 3"
                                                                            class="mt-3">
                                                                            <p class="text-sm text-green-600">
                                                                                <span
                                                                                    class="font-medium">Sempurna!</span>
                                                                                Semua foto telah diupload.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Form Actions -->
                                                            <div class="mt-6 flex justify-end">
                                                                <div>
                                                                    <button type="button" x-show="step > 1"
                                                                        @click="step--"
                                                                        class="px-6 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                                        <svg class="w-4 h-4 inline mr-2"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="M15 19l-7-7 7-7">
                                                                            </path>
                                                                        </svg>
                                                                        Sebelumnya
                                                                    </button>
                                                                    <button type="button" x-show="step < 4"
                                                                        @click="validateStep(step) && step++"
                                                                        class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                                        Selanjutnya
                                                                        <svg class="w-4 h-4 inline ml-2"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="M9 5l7 7-7 7">
                                                                            </path>
                                                                        </svg>
                                                                    </button>
                                                                    <button type="submit" x-show="step === 4"
                                                                        class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                                                        <svg class="w-4 h-4 inline mr-2"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="M5 13l4 4L19 7">
                                                                            </path>
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
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data properti ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 rounded p-4">
                {{ $properties->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalView', () => ({
                selectedProperty: {
                    currentImageIndex: 0,
                    images: []
                },
                modalOpenDetail: false,

                openModal(property) {
                    // Initialize property data with default values
                    this.selectedProperty = {
                        ...property,
                        currentImageIndex: 0,
                        images: property.images || []
                    };
                    this.modalOpenDetail = true;
                    this.disableBodyScroll();
                },

                closeModal() {
                    this.modalOpenDetail = false;
                    this.enableBodyScroll();
                },

                nextImage() {
                    if (this.selectedProperty.images.length > 0) {
                        this.selectedProperty.currentImageIndex =
                            (this.selectedProperty.currentImageIndex + 1) % this.selectedProperty.images
                            .length;
                    }
                },

                prevImage() {
                    if (this.selectedProperty.images.length > 0) {
                        this.selectedProperty.currentImageIndex =
                            (this.selectedProperty.currentImageIndex - 1 + this.selectedProperty.images
                                .length) %
                            this.selectedProperty.images.length;
                    }
                },

                goToImage(index) {
                    if (index >= 0 && index < this.selectedProperty.images.length) {
                        this.selectedProperty.currentImageIndex = index;
                    }
                },

                disableBodyScroll() {
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = this.getScrollbarWidth() + 'px';
                },

                enableBodyScroll() {
                    document.body.style.overflow = 'auto';
                    document.body.style.paddingRight = '0';
                },

                getScrollbarWidth() {
                    return window.innerWidth - document.documentElement.clientWidth;
                },

                init() {
                    // Handle escape key press
                    document.addEventListener('keydown', (e) => {
                        if (this.modalOpenDetail && e.key === 'Escape') {
                            this.closeModal();
                        }

                        // Optional: Add keyboard navigation for images
                        if (this.modalOpenDetail && this.selectedProperty.images.length > 1) {
                            if (e.key === 'ArrowRight') {
                                this.nextImage();
                            } else if (e.key === 'ArrowLeft') {
                                this.prevImage();
                            }
                        }
                    });

                    // Watch for modal state changes
                    this.$watch('modalOpenDetail', (value) => {
                        if (!value) {
                            this.enableBodyScroll();
                        }
                    });
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modalProperty', () => ({
                selectedProperty: {},
                modalOpenDetail: false,
                images: [],
                maxImages: 3,
                map: null,
                marker: null,
                searchQuery: '',
                searchResults: [],
                isSearching: false,

                openModal(property) {
                    this.selectedProperty = property;
                    this.modalOpenDetail = true;
                },
                step: 1,

                init() {
                    const provinceSelect = document.getElementById('province');
                    const citySelect = document.getElementById('city');

                    this.$watch('step', (value) => {
                        if (value === 2 && typeof L === 'undefined') {
                            this.loadLeaflet().then(() => {
                                // Add a small delay to ensure DOM is ready
                                setTimeout(() => {
                                    this.initMap();
                                }, 100);
                            });
                        } else if (value === 2 && typeof L !== 'undefined' && !this.map) {
                            // Add a small delay to ensure DOM is ready
                            setTimeout(() => {
                                this.initMap();
                            }, 100);
                        }
                    });
                },

                loadLeaflet() {
                    return new Promise((resolve) => {
                        if (typeof L !== 'undefined') {
                            resolve();
                            return;
                        }

                        // Load Leaflet CSS
                        const css = document.createElement('link');
                        css.rel = 'stylesheet';
                        css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                        css.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
                        css.crossOrigin = '';
                        document.head.appendChild(css);

                        // Load Leaflet JS
                        const js = document.createElement('script');
                        js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                        js.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                        js.crossOrigin = '';
                        js.onload = () => {
                            // Ensure DOM is fully loaded before resolving
                            setTimeout(resolve, 50);
                        };
                        document.head.appendChild(js);
                    });
                },

                initMap() {
                    try {
                        const mapElement = document.getElementById('map');
                        if (!mapElement) {
                            console.error('Map element not found');
                            return;
                        }

                        // Ensure the map element has proper dimensions
                        if (mapElement.offsetHeight === 0) {
                            mapElement.style.height = '400px';
                        }

                        // Default to Jakarta coordinates if no marker set
                        const defaultLat = -6.2088;
                        const defaultLng = 106.8456;

                        // Initialize map
                        this.map = L.map('map', {
                            preferCanvas: true,
                            zoomControl: true
                        }).setView([defaultLat, defaultLng], 13);

                        // Add tile layer with error handling
                        const tileLayer = L.tileLayer(
                            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                                maxZoom: 19,
                                minZoom: 1
                            });

                        tileLayer.on('tileerror', (e) => {
                            console.warn('Tile loading error:', e);
                        });

                        tileLayer.addTo(this.map);

                        // Force map to invalidate size after initialization
                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                            }
                        }, 200);

                        // Add click event to the map
                        this.map.on('click', (e) => {
                            this.placeMarker(e.latlng);
                            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
                        });

                        // Initialize marker if coordinates exist
                        const latInput = document.getElementById('latitude');
                        const lngInput = document.getElementById('longitude');

                        if (latInput && lngInput && latInput.value && lngInput.value) {
                            const lat = parseFloat(latInput.value);
                            const lng = parseFloat(lngInput.value);
                            if (!isNaN(lat) && !isNaN(lng)) {
                                this.placeMarker({
                                    lat,
                                    lng
                                });
                                this.map.setView([lat, lng], 15);
                            }
                        }

                        console.log('Map initialized successfully');
                    } catch (error) {
                        console.error('Error initializing map:', error);
                    }
                },

                placeMarker(latlng) {
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }

                    this.marker = L.marker(latlng, {
                        draggable: true
                    }).addTo(this.map);

                    // Update coordinates display
                    const coordsElement = document.getElementById('coordinates');
                    if (coordsElement) {
                        coordsElement.textContent =
                            `Latitude: ${latlng.lat.toFixed(6)}, Longitude: ${latlng.lng.toFixed(6)}`;
                    }

                    // Update hidden inputs
                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    if (latInput) latInput.value = latlng.lat;
                    if (lngInput) lngInput.value = latlng.lng;

                    // Update marker position on drag
                    this.marker.on('dragend', (e) => {
                        const newLatLng = e.target.getLatLng();
                        this.reverseGeocode(newLatLng.lat, newLatLng.lng);

                        // Update coordinates and inputs
                        if (coordsElement) {
                            coordsElement.textContent =
                                `Latitude: ${newLatLng.lat.toFixed(6)}, Longitude: ${newLatLng.lng.toFixed(6)}`;
                        }
                        if (latInput) latInput.value = newLatLng.lat;
                        if (lngInput) lngInput.value = newLatLng.lng;
                    });
                },

                async searchLocation() {
                    if (!this.searchQuery.trim()) return;

                    this.isSearching = true;
                    this.searchResults = [];

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=5&countrycodes=id`
                        );

                        if (!response.ok) throw new Error('Search failed');

                        const results = await response.json();
                        this.searchResults = results;
                    } catch (error) {
                        console.error('Search error:', error);
                        alert('Gagal melakukan pencarian lokasi');
                    } finally {
                        this.isSearching = false;
                    }
                },

                selectSearchResult(result) {
                    const lat = parseFloat(result.lat);
                    const lng = parseFloat(result.lon);

                    if (!isNaN(lat) && !isNaN(lng)) {
                        this.placeMarker({
                            lat,
                            lng
                        });
                        this.map.setView([lat, lng], 15);
                        this.reverseGeocode(lat, lng);
                        this.searchResults = [];
                        this.searchQuery = result.display_name;
                    }
                },

                async reverseGeocode(lat, lng) {
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`
                        );

                        if (!response.ok) throw new Error('Reverse geocoding failed');

                        const data = await response.json();
                        if (data.address) {
                            this.updateAddressFields(data.address);
                        }
                    } catch (error) {
                        console.error('Reverse geocoding error:', error);
                    }
                },

                updateAddressFields(address) {
                    // Helper function to safely update form fields
                    const updateField = (id, value) => {
                        const element = document.getElementById(id);
                        if (element) {
                            element.value = value || '';
                        }
                    };

                    // Update form fields based on reverse geocoding results
                    updateField('full_address',
                        address.road || address.hamlet || address.village ||
                        address.town || address.city || '');
                    updateField('province', address.state || address.region || '');
                    updateField('city', address.city || address.town || address.county || '');
                    updateField('district', address.suburb || address.city_district || '');
                    updateField('village',
                        address.village || address.hamlet || address.neighbourhood || '');
                    updateField('postal_code', address.postcode || '');
                },

                // Force map resize when step changes or container becomes visible
                resizeMap() {
                    if (this.map) {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 100);
                    }
                },

                // Photo upload methods (unchanged)
                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    this.processFiles(files);
                },

                handleDrop(event) {
                    event.preventDefault();
                    const files = Array.from(event.dataTransfer.files);
                    this.processFiles(files);
                },

                processFiles(files) {
                    const imageFiles = files.filter(file => file.type.startsWith('image/'));
                    const availableSlots = this.maxImages - this.images.length;
                    const filesToProcess = imageFiles.slice(0, availableSlots);

                    filesToProcess.forEach(file => {
                        if (file.size <= 5 * 1024 * 1024) { // 5MB limit
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.images.push({
                                    file: file,
                                    url: e.target.result,
                                    name: file.name
                                });
                            };
                            reader.readAsDataURL(file);
                        } else {
                            alert(`File ${file.name} terlalu besar. Maksimal 5MB.`);
                        }
                    });

                    // Clear the file input to allow re-selection
                    if (event.target) {
                        event.target.value = '';
                    }
                },

                removeImage(index) {
                    this.images.splice(index, 1);
                },

                get canUploadMore() {
                    return this.images.length < this.maxImages;
                },

                get remainingSlots() {
                    return this.maxImages - this.images.length;
                },

                validateStep(step) {
                    let isValid = true;

                    if (step === 1) {
                        const requiredFields = ['property_name'];

                        requiredFields.forEach(field => {
                            const el = document.getElementById(field);
                            if (el) {
                                if (!el.value) {
                                    el.classList.add('border-red-500');
                                    isValid = false;
                                } else {
                                    el.classList.remove('border-red-500');
                                }
                            }
                        });

                        if (!document.querySelector('input[name="property_type"]:checked')) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Pilih jenis properti terlebih dahulu!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            isValid = false;
                        }

                    } else if (step === 2) {
                        const requiredFields = ['full_address', 'province', 'city', 'district',
                            'village'
                        ];

                        requiredFields.forEach(field => {
                            const el = document.getElementById(field);
                            if (el) {
                                if (!el.value) {
                                    el.classList.add('border-red-500');
                                    isValid = false;
                                } else {
                                    el.classList.remove('border-red-500');
                                }
                            }
                        });

                        if (!this.marker || !this.marker.getLatLng()) {
                            alert('Pinpoint lokasi wajib dipilih');
                            isValid = false;
                        }
                    } else if (step === 3) {
                        // No validation needed for step 3 (facilities) as they're optional
                    } else if (step === 4) {
                        if (this.images.length !== 3) {
                            alert('Wajib upload tepat 3 foto properti');
                            isValid = false;
                        }
                    }

                    return isValid;
                },

                getImageFiles() {
                    return this.images.map(img => img.file);
                },

                resetImages() {
                    this.images = [];
                },

                submitForm() {
                    if (this.validateStep(4)) {
                        const form = document.getElementById('propertyForm');
                        const formData = new FormData(form);

                        // Clear any existing file inputs
                        formData.delete('property_images[]');

                        // Add each selected image
                        this.images.forEach((image, index) => {
                            formData.append('property_images[]', image.file);
                        });

                        // Submit the form
                        fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) throw new Error('Network response was not ok');
                                return response.json();
                            })
                            .then(data => {
                                // Handle success - replace with your actual route
                                // window.location.href = '{{ route('properties.index') }}';
                                alert('Property berhasil disimpan!');
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error submitting form');
                            });
                    }
                },
            }));
        });

        function modalPropertyEdit(property) {
            return {
                modalOpenDetail: false,
                step: 1,
                maxImages: 3,
                images: [],
                propertyData: {
                    name: property.name || '',
                    type: property.tags || 'House',
                    description: property.description || '',
                    address: property.address || '',
                    latitude: property.latitude || '',
                    longitude: property.longitude || '',
                    province: property.province || '',
                    city: property.city || '',
                    subdistrict: property.subdistrict || '',
                    village: property.village || '',
                    postal_code: property.postal_code || '',
                    features: property.features || [],
                    attributes: property.attributes || [],
                    // Add existing images from property data
                    existingImages: [
                        property.image ? {
                            url: property.image
                        } : null,
                        property.image2 ? {
                            url: property.image2
                        } : null,
                        property.image3 ? {
                            url: property.image3
                        } : null
                    ].filter(img => img !== null)
                },

                get remainingSlots() {
                    return this.maxImages - (this.images.length + this.propertyData.existingImages.length);
                },

                get canUploadMore() {
                    return (this.images.length + this.propertyData.existingImages.length) < this.maxImages;
                },

                openModal(data) {
                    this.propertyData = {
                        ...this.propertyData,
                        ...data,
                        // Preserve existing images when modal is reopened
                        existingImages: this.propertyData.existingImages
                    };
                    this.modalOpenDetail = true;
                    this.step = 1;
                    this.images = [];

                    // Initialize map after modal is opened
                    this.$nextTick(() => {
                        this.initMap();
                    });
                },

                initMap() {
                    // Check if map container exists
                    const mapContainer = document.getElementById('map_edit');
                    if (!mapContainer) return;

                    // Clear any existing map instance
                    if (mapContainer._leaflet_map) {
                        mapContainer._leaflet_map.remove();
                    }

                    // Initialize map with existing coordinates if available
                    if (this.propertyData.latitude && this.propertyData.longitude) {
                        const map = L.map('map_edit').setView(
                            [this.propertyData.latitude, this.propertyData.longitude],
                            15
                        );

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        const marker = L.marker(
                            [this.propertyData.latitude, this.propertyData.longitude]
                        ).addTo(map);

                        document.getElementById('coordinates_edit').textContent =
                            `Koordinat: ${this.propertyData.latitude}, ${this.propertyData.longitude}`;

                        // Add click event to update marker position
                        map.on('click', (e) => {
                            if (marker) {
                                map.removeLayer(marker);
                            }

                            const newMarker = L.marker(e.latlng).addTo(map);
                            this.propertyData.latitude = e.latlng.lat;
                            this.propertyData.longitude = e.latlng.lng;

                            document.getElementById('coordinates_edit').textContent =
                                `Koordinat: ${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}`;
                        });
                    } else {
                        // Initialize empty map with default Jakarta coordinates
                        const map = L.map('map_edit').setView([-6.1754, 106.8272], 12);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        // Add click event to add marker
                        map.on('click', (e) => {
                            const existingMarkers = document.getElementsByClassName('leaflet-marker-icon');
                            Array.from(existingMarkers).forEach(marker => marker.remove());

                            const marker = L.marker(e.latlng).addTo(map);
                            this.propertyData.latitude = e.latlng.lat;
                            this.propertyData.longitude = e.latlng.lng;

                            document.getElementById('coordinates_edit').textContent =
                                `Koordinat: ${e.latlng.lat.toFixed(6)}, ${e.latlng.lng.toFixed(6)}`;
                        });
                    }
                },

                handleFileSelect(event) {
                    const files = event.target.files;
                    this.handleFiles(files);
                    // Reset the input to allow selecting the same file again
                    event.target.value = '';
                },

                handleDrop(event) {
                    event.preventDefault();
                    const files = event.dataTransfer.files;
                    this.handleFiles(files);
                },

                handleFiles(files) {
                    if (!files || files.length === 0) return;

                    const availableSlots = this.maxImages - (this.images.length + this.propertyData.existingImages.length);
                    if (availableSlots <= 0) {
                        alert(`Anda hanya dapat mengupload maksimal ${this.maxImages} foto`);
                        return;
                    }

                    for (let i = 0; i < files.length; i++) {
                        if ((this.images.length + this.propertyData.existingImages.length) >= this.maxImages) break;

                        const file = files[i];
                        if (!file.type.match('image.*')) continue;
                        if (file.size > 5 * 1024 * 1024) { // 5MB limit
                            alert('File terlalu besar. Maksimal 5MB per gambar.');
                            continue;
                        }

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.images.push({
                                id: Date.now() + i,
                                file: file,
                                url: e.target.result,
                                name: file.name,
                                isNew: true // Mark as new upload
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                },

                removeImage(index, isExisting = false) {
                    if (isExisting) {
                        // For existing images, we'll just mark them for deletion
                        this.propertyData.existingImages[index].markedForDeletion = true;
                    } else {
                        // For new uploads, simply remove from array
                        this.images.splice(index, 1);
                    }
                },

                nextStep() {
                    if (this.validateStep()) {
                        this.step++;
                    }
                },

                prevStep() {
                    this.step--;
                },

                validateStep() {
                    if (this.step === 1) {
                        if (!this.propertyData.name.trim()) {
                            alert('Nama properti harus diisi');
                            return false;
                        }
                        if (!this.propertyData.description.trim()) {
                            alert('Deskripsi properti harus diisi');
                            return false;
                        }
                    } else if (this.step === 2) {
                        if (!this.propertyData.address.trim()) {
                            alert('Alamat lengkap harus diisi');
                            return false;
                        }

                        if (!this.propertyData.province.trim() || !this.propertyData.city.trim() ||
                            !this.propertyData.subdistrict.trim() || !this.propertyData.village.trim()) {
                            alert('Semua detail lokasi harus diisi');
                            return false;
                        }
                    } else if (this.step === 4) {
                        if (this.images.length < this.maxImages) {
                            alert(`Harap unggah ${this.maxImages} foto properti`);
                            return false;
                        }
                    }
                    return true;
                },

                closeModal() {
                    this.modalOpenDetail = false;
                    this.step = 1;
                    this.images = [];

                    // Clean up map
                    const mapContainer = document.getElementById('map_edit');
                    if (mapContainer && mapContainer._leaflet_map) {
                        mapContainer._leaflet_map.remove();
                    }
                },

                async submitForm() {
                    if (!this.validateStep()) return;

                    try {
                        // Create FormData for the submission
                        const formData = new FormData(document.getElementById('propertyFormEdit'));

                        // Append the new images
                        this.images.forEach((image, index) => {
                            formData.append(`images[${index}]`, image.file);
                        });

                        // Append which existing images to delete
                        this.propertyData.existingImages.forEach((image, index) => {
                            if (image.markedForDeletion) {
                                formData.append(`delete_images[]`, index);
                            }
                        });

                        // Show loading state
                        const submitBtn = document.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Memproses...
                                            `;

                        // Submit the form
                        const response = await fetch(document.getElementById('propertyFormEdit').action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            alert('Properti berhasil diperbarui!');
                            this.closeModal();
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Gagal memperbarui properti');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert(`Error: ${error.message}`);
                    } finally {
                        const submitBtn = document.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    }
                }
            };
        }

        // Listen for filter changes and submit form
        document.getElementById('statusFilter').addEventListener('change', function() {
            document.getElementById('searchForm').submit();
        });

        // Optional: Debounce search input to prevent too many requests
        let searchTimer;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500);
        });

        function toggleStatus(checkbox) {
            const propertyId = checkbox.getAttribute('data-id');
            const newStatus = checkbox.checked ? 1 : 0;

            fetch(`/properties/m-properties/${propertyId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const statusText = checkbox.nextElementSibling.nextElementSibling;
                    statusText.textContent = newStatus ? 'Active' : 'Inactive';

                    Toastify({
                        text: "Status properti berhasil diperbarui",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#4CAF50"
                        },
                        stopOnFocus: true
                    }).showToast();
                })
                .catch(error => {
                    console.error('Error:', error);
                    checkbox.checked = !checkbox.checked;
                    alert('Gagal memperbarui status properti');
                });
        }
    </script>
</x-app-layout>
