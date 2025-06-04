<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti</h1>
            <div class="mt-4 md:mt-0">
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

                                    <!-- Step 3 -->
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
                                                :class="step >= 3 ? 'text-blue-600' : 'text-gray-500'">Foto & Fasilitas
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal content -->
                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                <form id="propertyForm" method="POST" action="{{ route('properties.store') }}"
                                    enctype="multipart/form-data">
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
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                    <template
                                                        x-for="type in ['Kos', 'Rumah', 'Apartment', 'Villa', 'Hotel']">
                                                        <div class="relative">
                                                            <input :id="'type-' + type" name="property_type"
                                                                type="radio" :value="type"
                                                                class="sr-only peer" required>
                                                            <label :for="'type-' + type"
                                                                class="flex items-center justify-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                <span x-text="type"></span>
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
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Pinpoint Lokasi <span class="text-red-500">*</span>
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
                                                    <select id="province" name="province" required
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                                        <option value="">Pilih Provinsi</option>
                                                        <option>DKI Jakarta</option>
                                                        <option>Jawa Barat</option>
                                                        <option>Jawa Tengah</option>
                                                        <option>Jawa Timur</option>
                                                        <option>Bali</option>
                                                    </select>
                                                </div>

                                                <div>
                                                    <label for="city"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Kota/Kabupaten <span class="text-red-500">*</span>
                                                    </label>
                                                    <select id="city" name="city" required
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                                        <option value="">Pilih Kota</option>
                                                    </select>
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

                                                <div>
                                                    <label for="distance"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Jarak Terdekat dari Fasilitas Umum
                                                    </label>
                                                    <input type="text" id="distance" name="distance" required
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Contoh: 500m dari stasiun">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 3 - Photos and Facilities -->
                                    <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                        <div class="space-y-6">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                    Foto Properti <span class="text-red-500">*</span>
                                                    <span class="text-sm font-normal text-gray-500">(Minimal 3
                                                        foto)</span>
                                                </label>
                                                <div
                                                    class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200">
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
                                                                    class="sr-only" required>
                                                            </label>
                                                            <p class="pl-1">atau drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                                    </div>
                                                </div>
                                                <div id="imagePreview" class="mt-4 grid grid-cols-3 gap-4 hidden">
                                                </div>
                                            </div>

                                            <div class="space-y-6">
                                                <div>
                                                    <h3
                                                        class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z">
                                                            </path>
                                                        </svg>
                                                        Fasilitas
                                                    </h3>
                                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                        <template
                                                            x-for="(item, index) in ['High-speed WiFi', '24/7 Security', 'Shared Kitchen', 'Laundry Service', 'Parking Area', 'Common Area']">
                                                            <div class="relative">
                                                                <input :id="'amenity-' + index" name="facilities[]"
                                                                    type="checkbox" :value="item"
                                                                    class="sr-only peer">
                                                                <label :for="'amenity-' + index"
                                                                    class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                    <span x-text="item"></span>
                                                                </label>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <div>
                                                    <h3
                                                        class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                            </path>
                                                        </svg>
                                                        Rules
                                                    </h3>
                                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                        <template
                                                            x-for="(item, index) in ['No Smoking', 'No Pets', 'ID Card Required', 'Deposit Required']">
                                                            <div class="relative">
                                                                <input :id="'rule-' + index" name="rules[]"
                                                                    type="checkbox" :value="item"
                                                                    class="sr-only peer">
                                                                <label :for="'rule-' + index"
                                                                    class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-600 transition-all duration-200">
                                                                    <span x-text="item"></span>
                                                                </label>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="mt-6 flex justify-between">
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
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="button" @click="modalOpenDetail = false"
                                                class="px-6 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                Tutup
                                            </button>
                                            <button type="button" x-show="step < 3"
                                                @click="validateStep(step) && step++"
                                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                Selanjutnya
                                                <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                            <button type="submit" x-show="step === 3"
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
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Provinsi</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal Penambahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
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
                                            @php
                                                $images = json_decode($property->image);
                                                $firstImage = $images[0] ?? null;
                                            @endphp

                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="data:image/jpeg;base64,{{ $firstImage }}"
                                                alt="{{ $property->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $property->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $property->province }}</div>
                                    <div class="text-sm text-gray-500">{{ $property->city }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($property->created_at)->format('Y M d') }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($property->created_at)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
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
                                        {{-- <div x-data="modalView()" class="relative group">
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
                                                                    image: "data:image/jpeg;base64,{{ $firstImage }}",
                                                                    location: @json($property->location),
                                                                    distance: @json($property->distance),
                                                                    features: {!! $features !!},
                                                                    attributes: {!! $attributes !!}
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
                                            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                                                x-show="modalOpenDetail"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-out duration-100"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak>
                                            </div>

                                            <!-- Modal dialog -->
                                            <div id="property-detail-modal"
                                                class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4"
                                                role="dialog" aria-modal="true" x-show="modalOpenDetail"
                                                x-transition:enter="transition ease-in-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in-out duration-200"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95" x-cloak>

                                                <div class="bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-2xl max-h-[90vh] flex flex-col"
                                                    @click.outside="modalOpenDetail = false"
                                                    @keydown.escape.window="modalOpenDetail = false">

                                                    <!-- Modal header -->
                                                    <div
                                                        class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                                        <div class="text-left">
                                                            <h3 class="text-xl font-bold text-gray-900"
                                                                x-text="selectedProperty.name"></h3>
                                                            <p class="text-gray-500"
                                                                x-text="selectedProperty.city + ', ' + selectedProperty.province">
                                                            </p>
                                                        </div>
                                                        <button type="button"
                                                            class="text-gray-400 hover:text-gray-500 transition-colors duration-200"
                                                            @click="modalOpenDetail = false">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Modal content -->
                                                    <div class="overflow-y-auto flex-1">
                                                        <div class="relative">
                                                            <img :src="selectedProperty.image" alt="Property Image"
                                                                class="w-full h-64 object-cover object-center">
                                                            <div
                                                                class="absolute bottom-4 left-4 bg-white/90 px-3 py-1 rounded-full">
                                                                <span
                                                                    :class="selectedProperty && selectedProperty
                                                                        .status === 'Active' ?
                                                                        'text-green-600 font-medium' :
                                                                        'text-red-600 font-medium'"
                                                                    class="text-sm flex items-center">
                                                                    <span class="w-2 h-2 rounded-full mr-1 block"
                                                                        :class="selectedProperty && selectedProperty
                                                                            .status === 'Active' ?
                                                                            'bg-green-500' :
                                                                            'bg-red-500'"></span>
                                                                    <span
                                                                        x-text="selectedProperty && selectedProperty.status ? selectedProperty.status : ''"></span>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="p-6 space-y-4">
                                                            <div class="text-center">
                                                                <p class="text-gray-700 whitespace-pre-line"
                                                                    x-text="selectedProperty.description"></p>
                                                            </div>

                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div class="space-y-2">
                                                                    <div>
                                                                        <p
                                                                            class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                                            Added</p>
                                                                        <p class="text-gray-700"
                                                                            x-text="selectedProperty.created_at"></p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                                            Last Updated</p>
                                                                        <p class="text-gray-700"
                                                                            x-text="selectedProperty.updated_at"></p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                                            Distance</p>
                                                                        <p class="text-gray-700"
                                                                            x-text="selectedProperty.distance || 'N/A'">
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="space-y-2">
                                                                    <div>
                                                                        <p
                                                                            class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                                            Added By</p>
                                                                        <p class="text-gray-700"
                                                                            x-text="selectedProperty.creator"></p>
                                                                    </div>
                                                                    <div>
                                                                        <p
                                                                            class="text-xs font-medium text-gray-400 uppercase tracking-wider">
                                                                            Location</p>
                                                                        <p class="text-gray-700"
                                                                            x-text="selectedProperty.location || selectedProperty.city + ', ' + selectedProperty.province">
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Features Section -->
                                                            <div
                                                                x-show="selectedProperty.features && selectedProperty.features.length > 0">
                                                                <h4
                                                                    class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">
                                                                    Features</h4>
                                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                                    <template
                                                                        x-for="feature in selectedProperty.features">
                                                                        <div class="flex items-center space-x-2">
                                                                            <!-- Icons for each feature -->
                                                                            <template
                                                                                x-if="feature === 'High-speed WiFi'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-blue-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === '24/7 Security'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-green-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === 'Shared Kitchen'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-orange-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M3 3h18v4H3V3zm0 6h18v12H3V9zm5 4h2v2H8v-2zm4 0h2v2h-2v-2z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === 'Laundry Service'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-purple-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2zm5 14a5 5 0 100-10 5 5 0 000 10zm4-10h.01M15 8h.01" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="feature === 'Parking Area'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-yellow-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="feature === 'Common Area'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-indigo-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                                                </svg>
                                                                            </template>

                                                                            <span x-text="feature"
                                                                                class="text-gray-700 text-sm"></span>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>

                                                            <!-- Attributes Section -->
                                                            <div
                                                                x-show="selectedProperty.attributes && selectedProperty.attributes.length > 0">
                                                                <h4
                                                                    class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">
                                                                    Attributes</h4>
                                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                                    <template
                                                                        x-for="attribute in selectedProperty.attributes">
                                                                        <div class="flex items-center space-x-2">
                                                                            <!-- Icons for each attribute -->
                                                                            <template
                                                                                x-if="attribute === 'No Smoking'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-red-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="attribute === 'No Pets'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-red-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M15 9a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="attribute === 'ID Card Required'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-blue-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="attribute === 'Deposit Required'">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-5 w-5 text-yellow-500"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                            </template>

                                                                            <span x-text="attribute"
                                                                                class="text-gray-700 text-sm"></span>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div
                                                        class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex justify-end">
                                                        <button @click="modalOpenDetail = false"
                                                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors duration-200 font-medium">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <div x-data="modalView()" class="relative group">
                                            @php
                                                $features = json_encode(
                                                    $property->features,
                                                    JSON_HEX_APOS | JSON_HEX_QUOT,
                                                );
                                                $attributes = json_encode(
                                                    $property->attributes,
                                                    JSON_HEX_APOS | JSON_HEX_QUOT,
                                                );
                                                $amenities = json_encode(
                                                    $property->amenities,
                                                    JSON_HEX_APOS | JSON_HEX_QUOT,
                                                );
                                                $rules = json_encode(
                                                    $property->rules,
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
                                                                image: "data:image/jpeg;base64,{{ $firstImage }}",
                                                                location: @json($property->location),
                                                                distance: @json($property->distance),
                                                                features: {!! $features !!},
                                                                attributes: {!! $attributes !!},
                                                                amenities: {!! $amenities !!},
                                                                rules: {!! $rules !!}
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
                                                        <!-- Property image and status -->
                                                        <div class="relative">
                                                            <img :src="selectedProperty.image" alt="Property Image"
                                                                class="w-full h-72 object-cover object-center">
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
                                                        </div>

                                                        <div class="p-6 space-y-8">
                                                            <!-- Description -->
                                                            <div class="text-center">
                                                                <p class="text-gray-700 text-lg leading-relaxed whitespace-pre-line"
                                                                    x-text="selectedProperty.description"></p>
                                                            </div>

                                                            <!-- Property Info Grid -->
                                                            <div
                                                                class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-6 rounded-xl">
                                                                <div class="space-y-4">
                                                                    <div class="flex items-start space-x-3">
                                                                        <svg class="w-5 h-5 text-blue-500 mt-0.5"
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
                                                                    <div class="flex items-start space-x-3">
                                                                        <svg class="w-5 h-5 text-green-500 mt-0.5"
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
                                                                    <div class="flex items-start space-x-3">
                                                                        <svg class="w-5 h-5 text-purple-500 mt-0.5"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                                        </svg>
                                                                        <div>
                                                                            <p
                                                                                class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                                Distance</p>
                                                                            <p class="text-gray-800 font-medium"
                                                                                x-text="selectedProperty.distance || 'N/A'">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="space-y-4 flex flex-col items-center">
                                                                    <!-- Added By -->
                                                                    <div class="flex items-start space-x-3">
                                                                        <svg class="w-5 h-5 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                        </svg>
                                                                        <div>
                                                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Added By</p>
                                                                            <p class="text-gray-800 font-medium text-center" x-text="selectedProperty.creator"></p>
                                                                        </div>
                                                                    </div>
                                                                
                                                                    <!-- Location -->
                                                                    <div class="flex items-start space-x-3">
                                                                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                        </svg>
                                                                        <div>
                                                                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Location</p>
                                                                            <p class="text-gray-800 font-medium text-center"
                                                                               x-text="selectedProperty.location ? selectedProperty.location : (selectedProperty.city + ', ' + selectedProperty.province)">
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                            </div>

                                                            <!-- Amenities Section -->
                                                            <div x-show="selectedProperty.amenities && selectedProperty.amenities.length > 0"
                                                                class="space-y-4">
                                                                <div class="flex items-center space-x-2 mb-4">
                                                                    <svg class="w-6 h-6 text-blue-500" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                                    </svg>
                                                                    <h4 class="text-lg font-bold text-gray-900">
                                                                        Amenities</h4>
                                                                </div>
                                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                                    <template
                                                                        x-for="amenity in selectedProperty.amenities">
                                                                        <div
                                                                            class="flex items-center space-x-3 bg-blue-50 p-3 rounded-lg border border-blue-100">
                                                                            <!-- Amenity Icons -->
                                                                            <template
                                                                                x-if="amenity === 'High-speed WiFi'">
                                                                                <svg class="h-5 w-5 text-blue-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="amenity === 'Parking'">
                                                                                <svg class="h-5 w-5 text-yellow-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="amenity === 'Swimming Pool'">
                                                                                <svg class="h-5 w-5 text-cyan-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="amenity === 'Gym'">
                                                                                <svg class="h-5 w-5 text-red-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="amenity === 'Restaurant'">
                                                                                <svg class="h-5 w-5 text-orange-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                                                </svg>
                                                                            </template>
                                                                            <span x-text="amenity"
                                                                                class="text-gray-800 font-medium text-sm"></span>
                                                                        </div>
                                                                    </template>
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
                                                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
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
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0v16a2 2 0 01-2 2H9a2 2 0 01-2-2V4m10 0H7m5 4v8m0-8a2 2 0 00-2 2v6a2 2 0 002 2 2 2 0 002-2V8a2 2 0 00-2-2z" />
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
                                                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H19M17 17a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 100 4 2 2 0 000-4z" />
                                                                                </svg>
                                                                            </template>
                                                                            <span x-text="feature"
                                                                                class="text-gray-800 font-medium text-sm"></span>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>

                                                            <!-- Rules Section -->
                                                            <div x-show="selectedProperty.rules && selectedProperty.rules.length > 0"
                                                                class="space-y-4">
                                                                <div class="flex items-center space-x-2 mb-4">
                                                                    <svg class="w-6 h-6 text-red-500" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                    </svg>
                                                                    <h4 class="text-lg font-bold text-gray-900">House
                                                                        Rules</h4>
                                                                </div>
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                    <template x-for="rule in selectedProperty.rules">
                                                                        <div
                                                                            class="flex items-center space-x-3 bg-red-50 p-3 rounded-lg border border-red-100">
                                                                            <!-- Rule Icons -->
                                                                            <template x-if="rule === 'No Smoking'">
                                                                                <svg class="h-5 w-5 text-red-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template x-if="rule === 'No Pets'">
                                                                                <svg class="h-5 w-5 text-red-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="rule.includes('Check-in') || rule.includes('Check-out')">
                                                                                <svg class="h-5 w-5 text-orange-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="rule === 'ID Card Required'">
                                                                                <svg class="h-5 w-5 text-blue-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                                                </svg>
                                                                            </template>
                                                                            <template
                                                                                x-if="rule === 'Deposit Required'">
                                                                                <svg class="h-5 w-5 text-yellow-600"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                            </template>
                                                                            <span x-text="rule"
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
                                                            {{-- <button
                                                                class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-all duration-200 font-medium hover:shadow-md">
                                                                Contact Owner
                                                            </button> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit -->
                                        <div x-data="modal({{ $property }})" class="relative group">
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
                                                                    image: "data:image/jpeg;base64,{{ $firstImage }}",
                                                                    location: @json($property->location),
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
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-out duration-100"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak>
                                            </div>

                                            <!-- Modal dialog -->
                                            <div id="property-edit-modal"
                                                class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4"
                                                role="dialog" aria-modal="true" x-show="modalOpenDetail"
                                                x-transition:enter="transition ease-in-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in-out duration-200"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95" x-cloak>

                                                <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full flex flex-col text-left"
                                                    @click.outside="modalOpenDetail = false"
                                                    @keydown.escape.window="modalOpenDetail = false">
                                                    <!-- Modal header -->
                                                    <div
                                                        class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                                        <div class="flex justify-between items-center mb-4">
                                                            <div>
                                                                <div class="font-bold text-xl text-gray-800">Edit
                                                                    Properti</div>
                                                                <p class="text-gray-600 text-sm"
                                                                    x-text="selectedProperty.name"></p>
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
                                                                        Informasi Dasar</p>
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

                                                            <!-- Step 3 -->
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

                                                            <!-- Step 4 -->
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
                                                                        Coming Soon</p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <!-- Modal content -->
                                                    <div class="p-6 overflow-y-auto">
                                                        <form id="propertyForm_edit" method="POST"
                                                            action="{{ route('properties.update', $property->idrec) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')

                                                            <!-- Step 1 - Basic Information -->
                                                            <div x-show="step === 1">
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <label for="property_name_edit"
                                                                            class="block text-sm font-medium text-gray-700">Nama
                                                                            Properti<span
                                                                                class="text-red-500">*</span></label>
                                                                        <input type="text" id="property_name_edit"
                                                                            name="property_name" required
                                                                            value="{{ old('property_name', $property->name ?? '') }}"
                                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                                    </div>

                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-semibold text-gray-700 mb-3">
                                                                            Jenis Properti <span
                                                                                class="text-red-500">*</span>
                                                                        </label>
                                                                        <div
                                                                            class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                                            @foreach (['Kos', 'Rumah', 'Apartment', 'Villa', 'Hotel'] as $type)
                                                                                <div class="relative">
                                                                                    <input
                                                                                        id="type_edit_{{ $type }}"
                                                                                        name="property_type"
                                                                                        type="radio"
                                                                                        value="{{ $type }}"
                                                                                        class="sr-only peer" required
                                                                                        {{ old('property_type', $property->tags ?? '') == $type ? 'checked' : '' }}>
                                                                                    <label
                                                                                        for="type_edit_{{ $type }}"
                                                                                        class="flex items-center justify-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                                        {{ $type }}
                                                                                    </label>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>

                                                                    <div>
                                                                        <label for="description_edit"
                                                                            class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                                                        <textarea id="description_edit" name="description" rows="4" required
                                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description', $property->description ?? '') }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Step 2 - Location Details -->
                                                            <div x-show="step === 2" x-cloak>
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <label for="full_address_edit"
                                                                            class="block text-sm font-medium text-gray-700">Alamat
                                                                            Lengkap<span
                                                                                class="text-red-500">*</span></label>
                                                                        <textarea id="full_address_edit" name="full_address" rows="2" required
                                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('full_address', $property->address ?? '') }}</textarea>
                                                                    </div>

                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-gray-700">Pinpoint
                                                                            Lokasi<span
                                                                                class="text-red-500">*</span></label>
                                                                        <div id="map_edit"
                                                                            class="mt-1 h-64 bg-gray-200 rounded-md">
                                                                        </div>
                                                                        <div id="coordinates_edit"
                                                                            class="mt-2 text-sm text-gray-500"></div>
                                                                        <input type="hidden" id="latitude_edit"
                                                                            name="latitude"
                                                                            value="{{ old('latitude', $property->latitude ?? '') }}">
                                                                        <input type="hidden" id="longitude_edit"
                                                                            name="longitude"
                                                                            value="{{ old('longitude', $property->longitude ?? '') }}">
                                                                    </div>

                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label for="province_edit"
                                                                                class="block text-sm font-medium text-gray-700">Provinsi<span
                                                                                    class="text-red-500">*</span></label>
                                                                            <select id="province_edit" name="province"
                                                                                required
                                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                                                <option value="">Pilih
                                                                                    Provinsi<span
                                                                                        class="text-red-500">*</span>
                                                                                </option>
                                                                                @foreach (['DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Bali'] as $prov)
                                                                                    <option
                                                                                        value="{{ $prov }}"
                                                                                        {{ old('province', $property->province ?? '') == $prov ? 'selected' : '' }}>
                                                                                        {{ $prov }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div>
                                                                            <label for="city_edit"
                                                                                class="block text-sm font-medium text-gray-700">Kota/Kabupaten<span
                                                                                    class="text-red-500">*</span></label>
                                                                            <select id="city_edit" name="city"
                                                                                required
                                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                                                <option value="">Pilih Kota<span
                                                                                        class="text-red-500">*</span>
                                                                                </option>
                                                                                @if (isset($property) && $property->city)
                                                                                    <option
                                                                                        value="{{ $property->city }}"
                                                                                        selected>
                                                                                        {{ $property->city }}
                                                                                    </option>
                                                                                @endif
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label for="district_edit"
                                                                                class="block text-sm font-medium text-gray-700">Kecamatan<span
                                                                                    class="text-red-500">*</span></label>
                                                                            <input type="text" id="district_edit"
                                                                                name="district" required
                                                                                value="{{ old('district', $property->subdistrict ?? '') }}"
                                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                                        </div>

                                                                        <div>
                                                                            <label for="village_edit"
                                                                                class="block text-sm font-medium text-gray-700">Kelurahan<span
                                                                                    class="text-red-500">*</span></label>
                                                                            <input type="text" id="village_edit"
                                                                                name="village" required
                                                                                value="{{ old('village', $property->village ?? '') }}"
                                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                                        </div>
                                                                    </div>

                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div>
                                                                            <label for="postal_code_edit"
                                                                                class="block text-sm font-medium text-gray-700">Kode
                                                                                Pos</label>
                                                                            <input type="text"
                                                                                id="postal_code_edit"
                                                                                name="postal_code"
                                                                                value="{{ old('postal_code', $property->postal_code ?? '') }}"
                                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                                        </div>

                                                                        <div>
                                                                            <label for="distance_edit"
                                                                                class="block text-sm font-medium text-gray-700">Jarak
                                                                                Terdekat dari Fasilitas Umum</label>
                                                                            <input type="text" id="distance_edit"
                                                                                name="distance" required
                                                                                value="{{ old('distance', $property->distance ?? '') }}"
                                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Step 3 - Photos and Facilities -->
                                                            <div x-show="step === 3" x-cloak>
                                                                <div class="space-y-4">
                                                                    <div>
                                                                        <label
                                                                            class="block text-sm font-medium text-gray-700">Foto
                                                                            Properti<span class="text-red-500">*</span>
                                                                            (Minimal 3 foto)
                                                                        </label>
                                                                        <div
                                                                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                                            <div class="space-y-1 text-center">
                                                                                <div
                                                                                    class="flex text-sm text-gray-600">
                                                                                    <label for="property_images_edit"
                                                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                                                                        <span>Upload foto</span>
                                                                                        <input
                                                                                            id="property_images_edit"
                                                                                            name="property_images[]"
                                                                                            type="file" multiple
                                                                                            accept="image/*"
                                                                                            class="sr-only"
                                                                                            {{ !isset($property) ? 'required' : '' }}>
                                                                                    </label>
                                                                                    <p class="pl-1">atau drag and
                                                                                        drop</p>
                                                                                </div>
                                                                                <p class="text-xs text-gray-500">PNG,
                                                                                    JPG, JPEG up to 5MB</p>
                                                                            </div>
                                                                        </div>
                                                                        <div id="imagePreview_edit"
                                                                            class="mt-2 grid grid-cols-3 gap-2">
                                                                            @if (isset($property) && $property->image)
                                                                                @foreach (json_decode($property->image) as $key => $image)
                                                                                    <div class="relative">
                                                                                        <img src="data:image/jpeg;base64,{{ $image }}"
                                                                                            class="w-full h-32 object-cover rounded-md">
                                                                                        <input type="hidden"
                                                                                            name="existing_images[]"
                                                                                            value="{{ $key }}">
                                                                                        <button type="button"
                                                                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 text-xs"
                                                                                            onclick="removeImage(this)">
                                                                                            ×
                                                                                        </button>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="space-y-6">
                                                                        {{-- Fasilitas --}}
                                                                        <div>
                                                                            <h3
                                                                                class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                                <svg class="w-5 h-5 mr-2 text-blue-600"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                                                </svg>
                                                                                Fasilitas
                                                                            </h3>
                                                                            <div
                                                                                class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                                                @php
                                                                                    $existingFeatures = is_array(
                                                                                        $property->features ?? null,
                                                                                    )
                                                                                        ? $property->features
                                                                                        : [];
                                                                                @endphp
                                                                                @foreach (['High-speed WiFi', '24/7 Security', 'Shared Kitchen', 'Laundry Service', 'Parking Area', 'Common Area'] as $item)
                                                                                    <div class="relative">
                                                                                        <input
                                                                                            id="feature_edit_{{ $loop->index }}"
                                                                                            name="features[]"
                                                                                            type="checkbox"
                                                                                            value="{{ $item }}"
                                                                                            class="sr-only peer"
                                                                                            {{ (is_array(old('features')) && in_array($item, old('features'))) || in_array($item, $existingFeatures) ? 'checked' : '' }}>
                                                                                        <label
                                                                                            for="feature_edit_{{ $loop->index }}"
                                                                                            class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                                            {{ $item }}
                                                                                        </label>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>

                                                                        {{-- Rules --}}
                                                                        <div>
                                                                            <h3
                                                                                class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                                <svg class="w-5 h-5 mr-2 text-red-600"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                                                </svg>
                                                                                Rules
                                                                            </h3>
                                                                            <div
                                                                                class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                                                @php
                                                                                    $existingAttributes = is_array(
                                                                                        $property->attributes ?? null,
                                                                                    )
                                                                                        ? $property->attributes
                                                                                        : [];
                                                                                @endphp
                                                                                @foreach (['No Smoking', 'No Pets', 'ID Card Required', 'Deposit Required'] as $item)
                                                                                    <div class="relative">
                                                                                        <input
                                                                                            id="rule_edit_{{ $loop->index }}"
                                                                                            name="attributes[]"
                                                                                            type="checkbox"
                                                                                            value="{{ $item }}"
                                                                                            class="sr-only peer"
                                                                                            {{ (is_array(old('attributes')) && in_array($item, old('attributes'))) || in_array($item, $existingAttributes) ? 'checked' : '' }}>
                                                                                        <label
                                                                                            for="rule_edit_{{ $loop->index }}"
                                                                                            class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-600 transition-all duration-200">
                                                                                            {{ $item }}
                                                                                        </label>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Form Actions -->
                                                            <div class="mt-6 flex justify-between">
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
                                                                </div>
                                                                <div class="flex space-x-3">
                                                                    <button type="button"
                                                                        @click="modalOpenDetail = false"
                                                                        class="px-6 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                                        Tutup
                                                                    </button>
                                                                    <button type="button" x-show="step < 3"
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
                                                                    <button type="submit" x-show="step === 3"
                                                                        class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                                                        <svg class="w-4 h-4 inline mr-2"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="M5 13l4 4L19 7">
                                                                            </path>
                                                                        </svg>
                                                                        {{ isset($property) ? 'Update' : 'Simpan' }}
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
                selectedProperty: {},
                modalOpenDetail: false,
                openModal(property) {
                    this.selectedProperty = property;
                    this.modalOpenDetail = true;
                    // Prevent body scroll when modal is open
                    document.body.style.overflow = 'hidden';
                },
                closeModal() {
                    this.modalOpenDetail = false;
                    // Restore body scroll when modal is closed
                    document.body.style.overflow = 'auto';
                },
                init() {
                    // Watch for modal state changes
                    this.$watch('modalOpenDetail', (value) => {
                        if (!value) {
                            document.body.style.overflow = 'auto';
                        }
                    });
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modalProperty', () => ({
                selectedProperty: {},
                modalOpenDetail: false,
                openModal(property) {
                    this.selectedProperty = property;
                    this.modalOpenDetail = true;
                },
                step: 1,

                init() {
                    const provinces = [{
                            name: 'DKI Jakarta',
                            cities: ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat',
                                'Jakarta Timur', 'Jakarta Utara'
                            ]
                        },
                        {
                            name: 'Jawa Barat',
                            cities: ['Bandung', 'Bogor', 'Depok', 'Bekasi']
                        },
                        {
                            name: 'Jawa Tengah',
                            cities: ['Semarang', 'Solo', 'Salatiga']
                        },
                        {
                            name: 'Jawa Timur',
                            cities: ['Surabaya', 'Malang', 'Sidoarjo']
                        },
                        {
                            name: 'Bali',
                            cities: ['Denpasar', 'Badung', 'Gianyar']
                        }
                    ];

                    const provinceSelect = document.getElementById('province');
                    const citySelect = document.getElementById('city');

                    provinceSelect.addEventListener('change', function() {
                        citySelect.innerHTML = '<option value="">Pilih Kota</option>';
                        const selected = provinces.find(p => p.name === this.value);
                        if (selected) {
                            selected.cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city;
                                option.textContent = city;
                                citySelect.appendChild(option);
                            });
                            citySelect.disabled = false;
                        } else {
                            citySelect.disabled = true;
                        }
                    });

                    // Image preview
                    document.getElementById('property_images').addEventListener('change', function(e) {
                        const preview = document.getElementById('imagePreview');
                        preview.innerHTML = '';

                        if (this.files.length > 0) {
                            preview.classList.remove('hidden');
                            const fileCount = document.createElement('div');
                            fileCount.className = 'col-span-3 text-sm text-gray-500';
                            fileCount.textContent = `${this.files.length} file dipilih`;
                            preview.appendChild(fileCount);

                            for (let i = 0; i < this.files.length; i++) {
                                const file = this.files[i];
                                if (!file.type.match('image.*')) continue;

                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.className = 'h-24 w-full object-cover rounded';
                                    preview.appendChild(img);
                                }
                                reader.readAsDataURL(file);
                            }
                        } else {
                            preview.classList.add('hidden');
                        }
                    });

                    // Initialize map when step 2 is shown
                    this.$watch('step', (value) => {
                        if (value === 2 && typeof L === 'undefined') {
                            this.loadLeaflet().then(() => this.initMap());
                        } else if (value === 2 && typeof L !== 'undefined' && !this.map) {
                            this.initMap();
                        }
                    });
                },

                loadLeaflet() {
                    return new Promise((resolve, reject) => {
                        if (typeof L !== 'undefined') {
                            resolve();
                            return;
                        }

                        const script = document.createElement('script');
                        script.src = 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js';
                        script.integrity =
                            'sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==';
                        script.crossOrigin = '';
                        script.onload = resolve;
                        script.onerror = reject;

                        const css = document.createElement('link');
                        css.rel = 'stylesheet';
                        css.href = 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css';
                        css.integrity =
                            'sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==';
                        css.crossOrigin = '';

                        document.head.appendChild(css);
                        document.head.appendChild(script);
                    });
                },

                initMap() {
                    this.map = L.map('map').setView([-6.2088, 106.8456], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(this.map);

                    this.geocoder = L.Control.Geocoder.nominatim();
                    this.marker = L.marker([-6.2088, 106.8456], {
                        draggable: true
                    }).addTo(this.map);

                    this.marker.on('moveend', () => this.updateAddressFromMarker());
                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.updateAddressFromMarker();
                    });

                    this.updateAddressFromMarker();
                },

                updateAddressFromMarker() {
                    const latlng = this.marker.getLatLng();
                    document.getElementById('coordinates').innerHTML =
                        `Latitude: ${latlng.lat.toFixed(6)}, Longitude: ${latlng.lng.toFixed(6)}`;
                    document.getElementById('latitude').value = latlng.lat;
                    document.getElementById('longitude').value = latlng.lng;

                    this.geocoder.reverse(latlng, this.map.options.crs.scale(this.map.getZoom()), (
                        results) => {
                        if (results && results.length > 0) {
                            document.getElementById('full_address').value = results[0].name ||
                                results[0].html || '';
                        }
                    });
                },

                validateStep(step) {
                    let isValid = true;

                    if (step === 1) {
                        const requiredFields = [
                            'property_name'
                        ];

                        requiredFields.forEach(field => {
                            const el = document.getElementById(field);
                            if (!el.value) {
                                el.classList.add('border-red-500');
                                isValid = false;
                            } else {
                                el.classList.remove('border-red-500');
                            }
                        });

                        if (!document.querySelector('input[name="property_type"]:checked')) {
                            alert('Pilih jenis properti');
                            isValid = false;
                        }
                    } else if (step === 2) {
                        const requiredFields = [
                            'full_address', 'province', 'city', 'district', 'village'
                        ];

                        requiredFields.forEach(field => {
                            const el = document.getElementById(field);
                            if (!el.value) {
                                el.classList.add('border-red-500');
                                isValid = false;
                            } else {
                                el.classList.remove('border-red-500');
                            }
                        });

                        if (!this.marker || !this.marker.getLatLng()) {
                            alert('Pinpoint lokasi wajib dipilih');
                            isValid = false;
                        }
                    } else if (step === 3) {
                        const fileInput = document.getElementById('property_images');
                        if (fileInput.files.length < 3) {
                            alert('Upload minimal 3 foto properti');
                            isValid = false;
                        }
                    }

                    return isValid;
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', (property) => ({
                // Modal state
                modalOpenDetail: false,
                step: 1,
                totalSteps: 3,
                selectedProperty: {},
                map: null,
                marker: null,
                geocoder: null,
                mapLoaded: false,
                mapInitialized: false,

                // Initialization
                init() {
                    this.setupEventListeners();
                },

                // Open modal with property data
                openModal(propertyData) {
                    this.selectedProperty = {
                        ...propertyData
                    };
                    this.modalOpenDetail = true;
                    this.step = 1;

                    // Set form action URL if editing existing property
                    if (propertyData.id) {
                        const form = document.getElementById('propertyForm_edit');
                        if (form) {
                            form.action = `/properties/m-properties/update/${propertyData.id}`;
                        }
                    }


                    // Load initial data immediately
                    this.$nextTick(() => {
                        this.loadInitialData();
                    });
                },

                // Close modal
                closeModal() {
                    this.modalOpenDetail = false;
                    this.resetForm();
                },

                // Reset form data
                resetForm() {
                    this.selectedProperty = {};
                    this.step = 1;
                    this.mapInitialized = false;
                    if (this.map) {
                        try {
                            this.map.remove();
                        } catch (e) {
                            console.warn('Error removing map:', e);
                        }
                        this.map = null;
                        this.marker = null;
                    }
                },

                // Navigation between steps
                nextStep() {
                    if (this.validateStep(this.step)) {
                        this.step++;

                        // Load step-specific resources
                        if (this.step === 2) {
                            this.$nextTick(() => {
                                this.loadMapResources();
                            });
                        }
                    }
                },

                prevStep() {
                    if (this.step > 1) {
                        this.step--;
                    }
                },

                // Step validation
                validateStep(step) {
                    let isValid = true;
                    const requiredFields = this.getRequiredFieldsForStep(step);

                    requiredFields.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field && !field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                            // Remove error styling after user starts typing
                            field.addEventListener('input', () => {
                                field.classList.remove('border-red-500');
                            }, {
                                once: true
                            });
                        }
                    });

                    // Special validation for step 3 (images)
                    if (step === 3) {
                        const imageInput = document.getElementById('property_images_edit');
                        const existingImages = document.querySelectorAll(
                            'input[name="existing_images[]"]');

                        if (imageInput && imageInput.files.length === 0 && existingImages.length < 3) {
                            isValid = false;
                            this.showError('Minimal 3 foto diperlukan');
                        }
                    }

                    if (!isValid) {
                        this.showError('Mohon lengkapi semua field yang diperlukan');
                    }

                    return isValid;
                },

                // Get required fields for each step
                getRequiredFieldsForStep(step) {
                    switch (step) {
                        case 1:
                            return ['property_name_edit', 'description_edit'];
                        case 2:
                            return ['full_address_edit', 'province_edit', 'city_edit', 'district_edit',
                                'village_edit'
                            ];
                        case 3:
                            return []; // Images are handled separately
                        default:
                            return [];
                    }
                },

                // Show error message
                showError(message) {
                    // Create a simple toast notification
                    const toast = document.createElement('div');
                    toast.className =
                        'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-opacity duration-300';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.style.opacity = '0';
                        setTimeout(() => {
                            if (document.body.contains(toast)) {
                                document.body.removeChild(toast);
                            }
                        }, 300);
                    }, 3000);
                },

                // Load initial data when modal opens
                loadInitialData() {
                    // Pre-fill form fields with existing data
                    if (this.selectedProperty.name) {
                        const nameField = document.getElementById('property_name_edit');
                        if (nameField) nameField.value = this.selectedProperty.name;
                    }

                    if (this.selectedProperty.description) {
                        const descField = document.getElementById('description_edit');
                        if (descField) descField.value = this.selectedProperty.description;
                    }

                    // Set other fields as needed
                    this.setFormFieldValue('full_address_edit', this.selectedProperty.address);
                    this.setFormFieldValue('province_edit', this.selectedProperty.province);
                    this.setFormFieldValue('city_edit', this.selectedProperty.city);
                    this.setFormFieldValue('district_edit', this.selectedProperty.district);
                    this.setFormFieldValue('village_edit', this.selectedProperty.village);
                    this.setFormFieldValue('distance_edit', this.selectedProperty.distance);

                    // Set radio buttons for property type
                    if (this.selectedProperty.type) {
                        const typeRadio = document.querySelector(
                            `input[name="property_type_edit"][value="${this.selectedProperty.type}"]`
                        );
                        if (typeRadio) typeRadio.checked = true;
                    }

                    // Set checkboxes for features and attributes
                    this.setCheckboxes('features_edit[]', this.selectedProperty.features);
                    this.setCheckboxes('attributes_edit[]', this.selectedProperty.attributes);
                },

                // Helper to set form field values
                setFormFieldValue(fieldId, value) {
                    if (value) {
                        const field = document.getElementById(fieldId);
                        if (field) field.value = value;
                    }
                },

                // Helper to set checkbox values
                setCheckboxes(name, values) {
                    if (Array.isArray(values)) {
                        values.forEach(value => {
                            const checkbox = document.querySelector(
                                `input[name="${name}"][value="${value}"]`);
                            if (checkbox) checkbox.checked = true;
                        });
                    }
                },

                // Setup event listeners
                setupEventListeners() {
                    // Image upload handling
                    this.$nextTick(() => {
                        const imageInput = document.getElementById('property_images_edit');
                        if (imageInput) {
                            imageInput.addEventListener('change', (e) => this.handleImageUpload(
                                e));
                        }

                        // Province change handler
                        const provinceSelect = document.getElementById('province_edit');
                        if (provinceSelect) {
                            provinceSelect.addEventListener('change', (e) => this
                                .handleProvinceChange(e));
                        }
                    });
                },

                // Handle image upload
                handleImageUpload(event) {
                    const files = event.target.files;
                    const previewContainer = document.getElementById('imagePreview_edit');

                    if (!previewContainer) return;

                    // Create previews for new images
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const div = document.createElement('div');
                            div.className = 'relative';
                            div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-md">
                        <button type="button" class="absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs" onclick="this.parentElement.remove()">
                            &times;
                        </button>
                    `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    });
                },

                // Handle province change (populate cities)
                handleProvinceChange(event) {
                    const province = event.target.value;
                    const citySelect = document.getElementById('city_edit');

                    if (!citySelect) return;

                    // Clear existing options
                    citySelect.innerHTML = '<option value="">Pilih Kota</option>';

                    // City data mapping
                    const cityData = {
                        'DKI Jakarta': ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat',
                            'Jakarta Selatan', 'Jakarta Timur'
                        ],
                        'Jawa Barat': ['Bandung', 'Bekasi', 'Depok', 'Bogor', 'Cimahi'],
                        'Jawa Tengah': ['Semarang', 'Solo', 'Yogyakarta', 'Magelang', 'Salatiga'],
                        'Jawa Timur': ['Surabaya', 'Malang', 'Kediri', 'Blitar', 'Madiun'],
                        'Bali': ['Denpasar', 'Ubud', 'Sanur', 'Kuta', 'Canggu']
                    };

                    const cities = cityData[province] || [];
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city;
                        option.textContent = city;
                        citySelect.appendChild(option);
                    });
                },

                // Load map resources with better error handling
                async loadMapResources() {
                    const mapElement = document.getElementById('map_edit');
                    if (!mapElement) {
                        console.error('Map element not found');
                        return;
                    }

                    if (this.mapInitialized) {
                        return;
                    }

                    try {
                        // Show loading indicator
                        mapElement.innerHTML =
                            '<div class="flex items-center justify-center h-full text-gray-500">Loading map...</div>';

                        await this.loadLeaflet();
                        await this.initMap();
                        this.mapInitialized = true;
                    } catch (error) {
                        console.error('Error loading map:', error);
                        mapElement.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-red-500 p-4 text-center">
                        <p class="mb-2">Gagal memuat peta</p>
                        <button onclick="window.location.reload()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Muat Ulang Halaman
                        </button>
                    </div>
                `;
                    }
                },

                // Load Leaflet.js with better error handling
                loadLeaflet() {
                    return new Promise((resolve, reject) => {
                        // Check if Leaflet is already loaded
                        if (typeof L !== 'undefined') {
                            resolve();
                            return;
                        }

                        // Check if already loading
                        if (window.leafletLoading) {
                            window.leafletLoading.then(resolve).catch(reject);
                            return;
                        }

                        console.log('Loading Leaflet...');

                        window.leafletLoading = new Promise((res, rej) => {
                            // Load CSS first
                            if (!document.querySelector('link[href*="leaflet.css"]')) {
                                const css = document.createElement('link');
                                css.rel = 'stylesheet';
                                css.href =
                                    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                                css.integrity =
                                    'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
                                css.crossOrigin = '';
                                document.head.appendChild(css);
                            }

                            // Then load JS
                            if (!document.querySelector('script[src*="leaflet.js"]')) {
                                const script = document.createElement('script');
                                script.src =
                                    'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                                script.integrity =
                                    'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                                script.crossOrigin = '';

                                script.onload = () => {
                                    console.log('Leaflet loaded successfully');
                                    // Wait a bit for everything to initialize
                                    setTimeout(() => {
                                        this.loadGeocoderPlugin().then(res)
                                            .catch(rej);
                                    }, 100);
                                };

                                script.onerror = (error) => {
                                    console.error('Failed to load Leaflet:', error);
                                    rej(new Error(
                                        'Failed to load Leaflet library'));
                                };

                                document.head.appendChild(script);
                            } else {
                                // Script already exists, check if L is available
                                const checkLeaflet = () => {
                                    if (typeof L !== 'undefined') {
                                        this.loadGeocoderPlugin().then(res).catch(
                                            rej);
                                    } else {
                                        setTimeout(checkLeaflet, 100);
                                    }
                                };
                                checkLeaflet();
                            }
                        });

                        window.leafletLoading.then(resolve).catch(reject);
                    });
                },

                // Load geocoder plugin
                loadGeocoderPlugin() {
                    return new Promise((resolve, reject) => {
                        if (typeof L !== 'undefined' && L.Control && L.Control.Geocoder) {
                            resolve();
                            return;
                        }

                        try {
                            // Load geocoder CSS
                            if (!document.querySelector('link[href*="Control.Geocoder.css"]')) {
                                const css = document.createElement('link');
                                css.rel = 'stylesheet';
                                css.href =
                                    'https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css';
                                css.crossOrigin = '';
                                document.head.appendChild(css);
                            }

                            // Load geocoder JS
                            if (!document.querySelector('script[src*="Control.Geocoder.js"]')) {
                                const script = document.createElement('script');
                                script.src =
                                    'https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js';
                                script.crossOrigin = '';
                                script.onload = () => {
                                    console.log('Geocoder loaded successfully');
                                    resolve();
                                };
                                script.onerror = (error) => {
                                    console.warn(
                                        'Failed to load geocoder, continuing without it:',
                                        error);
                                    resolve(); // Continue without geocoder
                                };
                                document.head.appendChild(script);
                            } else {
                                resolve();
                            }
                        } catch (error) {
                            console.warn('Error loading geocoder:', error);
                            resolve(); // Continue without geocoder
                        }
                    });
                },

                // Initialize map with better error handling
                async initMap() {
                    const mapElement = document.getElementById('map_edit');
                    if (!mapElement) {
                        throw new Error('Map element not found');
                    }

                    if (typeof L === 'undefined') {
                        throw new Error('Leaflet library not loaded');
                    }

                    try {
                        // Clear the loading message
                        mapElement.innerHTML = '';

                        // Default coordinates (Jakarta)
                        let lat = -6.2088;
                        let lng = 106.8456;

                        // Use existing coordinates if available
                        if (this.selectedProperty.latitude && this.selectedProperty.longitude) {
                            lat = parseFloat(this.selectedProperty.latitude);
                            lng = parseFloat(this.selectedProperty.longitude);
                        }

                        console.log('Initializing map at:', lat, lng);

                        // Initialize map
                        this.map = L.map('map_edit', {
                            center: [lat, lng],
                            zoom: 13,
                            zoomControl: true,
                            scrollWheelZoom: true
                        });

                        // Add tile layer with fallback
                        const tileLayer = L.tileLayer(
                            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                                maxZoom: 19,
                                errorTileUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjU2IiBoZWlnaHQ9IjI1NiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PC9zdmc+'
                            });

                        tileLayer.addTo(this.map);

                        // Create marker
                        this.marker = L.marker([lat, lng], {
                            draggable: true
                        }).addTo(this.map);

                        // Event listeners
                        this.marker.on('moveend', () => this.updateCoordinates());
                        this.map.on('click', (e) => {
                            this.marker.setLatLng(e.latlng);
                            this.updateCoordinates();
                        });

                        // Add geocoder control if available
                        if (typeof L.Control !== 'undefined' && L.Control.Geocoder) {
                            try {
                                this.geocoder = L.Control.Geocoder.nominatim();
                                const geocoderControl = L.Control.geocoder({
                                    defaultMarkGeocode: false,
                                    placeholder: 'Cari lokasi...',
                                    errorMessage: 'Lokasi tidak ditemukan'
                                }).on('markgeocode', (e) => {
                                    const latlng = e.geocode.center;
                                    this.marker.setLatLng(latlng);
                                    this.map.setView(latlng, 15);
                                    this.updateCoordinates();
                                }).addTo(this.map);
                            } catch (geocoderError) {
                                console.warn('Failed to add geocoder control:', geocoderError);
                            }
                        }

                        // Add current location button
                        this.addCurrentLocationControl();

                        // Update coordinates
                        this.updateCoordinates();

                        // Ensure proper map rendering
                        setTimeout(() => {
                            if (this.map) {
                                this.map.invalidateSize();
                                console.log('Map initialized successfully');
                            }
                        }, 200);

                    } catch (error) {
                        console.error('Error initializing map:', error);
                        mapElement.innerHTML = `
                    <div class="flex items-center justify-center h-full text-red-500">
                        Error loading map: ${error.message}
                    </div>
                `;
                        throw error;
                    }
                },

                // Add current location control
                addCurrentLocationControl() {
                    const locationControl = L.control({
                        position: 'topright'
                    });

                    locationControl.onAdd = (map) => {
                        const div = L.DomUtil.create('div', 'current-location-control');
                        div.innerHTML = `
                    <button type="button" title="Gunakan lokasi saat ini" style="
                        background: white;
                        border: 2px solid rgba(0,0,0,0.2);
                        border-radius: 4px;
                        width: 34px;
                        height: 34px;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 16px;
                    ">📍</button>
                `;

                        L.DomEvent.on(div, 'click', (e) => {
                            L.DomEvent.stopPropagation(e);
                            this.getCurrentLocation();
                        });

                        return div;
                    };

                    locationControl.addTo(this.map);
                },

                // Get current location
                getCurrentLocation() {
                    if (!navigator.geolocation) {
                        this.showError('Geolocation tidak didukung browser ini');
                        return;
                    }

                    const button = document.querySelector('.current-location-control button');
                    if (button) {
                        button.innerHTML = '⏳';
                        button.disabled = true;
                    }

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            this.marker.setLatLng([lat, lng]);
                            this.map.setView([lat, lng], 15);
                            this.updateCoordinates();

                            if (button) {
                                button.innerHTML = '📍';
                                button.disabled = false;
                            }
                        },
                        (error) => {
                            let message = 'Gagal mendapatkan lokasi';
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    message = 'Akses lokasi ditolak';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    message = 'Lokasi tidak tersedia';
                                    break;
                                case error.TIMEOUT:
                                    message = 'Timeout mendapatkan lokasi';
                                    break;
                            }
                            this.showError(message);

                            if (button) {
                                button.innerHTML = '📍';
                                button.disabled = false;
                            }
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 60000
                        }
                    );
                },

                // Update coordinates
                updateCoordinates() {
                    if (!this.marker) return;

                    const latlng = this.marker.getLatLng();

                    // Update hidden inputs
                    const latInput = document.getElementById('latitude_edit');
                    const lngInput = document.getElementById('longitude_edit');
                    if (latInput) latInput.value = latlng.lat.toFixed(6);
                    if (lngInput) lngInput.value = latlng.lng.toFixed(6);

                    // Update display
                    const coordinatesElement = document.getElementById('coordinates_edit');
                    if (coordinatesElement) {
                        coordinatesElement.innerHTML =
                            `Latitude: ${latlng.lat.toFixed(6)}, Longitude: ${latlng.lng.toFixed(6)}`;
                    }
                },

                // Submit form
                submitForm() {
                    if (this.validateStep(this.step)) {
                        return true;
                    }
                    return false;
                }
            }));
        });

        // Helper function to remove images (called from template)
        function removeImage(button) {
            button.parentElement.remove();
        }


        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('propertyModal');
            modal.classList.add('hidden');
        }

        // Function to change main image when clicking thumbnails
        function changeMainImage(thumbnail) {
            const mainImage = document.getElementById('modalPropertyImage');
            mainImage.src = thumbnail.src;
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
