<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti</h1>
            <div class="mt-4 md:mt-0">
                {{-- New Input Property --}}
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

                                                <!-- Info about thumbnail -->
                                                <div
                                                    class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-r-lg">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-blue-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="text-sm text-blue-700">
                                                                <span class="font-semibold">Perhatian:</span> Foto
                                                                pertama yang Anda upload akan menjadi <span
                                                                    class="font-bold">thumbnail utama</span> iklan
                                                                properti ini. Pastikan foto pertama adalah yang terbaik!
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

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
                                                <div x-show="images.length > 0" class="mt-2 grid grid-cols-5 gap-1"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100">
                                                    <template x-for="(image, index) in images" :key="index">
                                                        <div class="relative group">
                                                            <!-- Image Container - Made smaller -->
                                                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-400 transition-colors duration-200"
                                                                :class="{ 'border-2 border-blue-600': index === 0 }">
                                                                <img :src="image.url"
                                                                    :alt="`Preview ${index + 1}`"
                                                                    class="w-full h-full object-cover">
                                                            </div>

                                                            <!-- Remove Button - Made smaller -->
                                                            <button @click="removeImage(index, $event)"
                                                                class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[8px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                <svg class="w-2 h-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>

                                                            <!-- Image Number Badge - Made smaller -->
                                                            <div
                                                                class="absolute bottom-1 left-1 bg-blue-600 text-white text-[8px] px-1 py-0.5 rounded-full font-medium">
                                                                <span x-text="index + 1"></span>
                                                            </div>

                                                            <!-- Thumbnail indicator for first image -->
                                                            <div x-show="index === 0" class="absolute top-1 right-1">
                                                                <span
                                                                    class="bg-yellow-500 text-white text-[8px] px-1 py-0.5 rounded-full font-medium">Thumbnail</span>
                                                            </div>

                                                            <!-- File Name - Made smaller and hidden by default, shows on hover -->
                                                            <div
                                                                class="mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                                                <p class="text-[8px] text-gray-600 truncate"
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
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        Foto pertama (ditandai dengan label "Thumbnail") akan menjadi
                                                        gambar utama properti Anda.
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
                            <select name="per_page" id="perPageSelect"
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

            <div class="overflow-x-auto" id="propertyTableContainer">
                @include('pages.Properties.m-Properties.partials.property_table', [
                    'properties' => $properties,
                    'per_page' => request('per_page', 8),
                ])
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 rounded p-4" id="paginationContainer">
                {{ $properties->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalView', () => ({
                selectedProperty: {
                    currentImageIndex: 0,
                    images: [],
                    features: []
                },
                modalOpenDetail: false,
                isLoading: false,
                touchStartX: 0,
                touchEndX: 0,

                openModal(property) {
                    this.isLoading = true;
                    this.modalOpenDetail = true;
                    this.disableBodyScroll();

                    // Use nextTick to ensure DOM is ready before setting properties
                    this.$nextTick(() => {
                        this.selectedProperty = {
                            ...property,
                            currentImageIndex: 0,
                            images: Array.isArray(property.images) ? property.images.filter(
                                img => img) : [],
                            features: Array.isArray(property.features) ? property.features :
                                []
                        };
                        this.isLoading = false;
                    });
                },

                closeModal() {
                    this.modalOpenDetail = false;
                    this.enableBodyScroll();
                    // Reset for next opening
                    setTimeout(() => {
                        this.selectedProperty = {
                            currentImageIndex: 0,
                            images: [],
                            features: []
                        };
                    }, 300); // Match this with your CSS transition duration
                },

                nextImage() {
                    if (this.hasMultipleImages) {
                        this.selectedProperty.currentImageIndex =
                            (this.selectedProperty.currentImageIndex + 1) % this.selectedProperty.images
                            .length;
                    }
                },

                prevImage() {
                    if (this.hasMultipleImages) {
                        this.selectedProperty.currentImageIndex =
                            (this.selectedProperty.currentImageIndex - 1 + this.selectedProperty.images
                                .length) %
                            this.selectedProperty.images.length;
                    }
                },

                goToImage(index) {
                    if (this.hasMultipleImages && index >= 0 && index < this.selectedProperty.images
                        .length) {
                        this.selectedProperty.currentImageIndex = index;
                    }
                },

                // Getters for computed properties
                get hasMultipleImages() {
                    return this.selectedProperty.images?.length > 1;
                },

                get currentImage() {
                    return this.selectedProperty.images[this.selectedProperty.currentImageIndex];
                },

                // Touch event handlers for mobile swipe
                handleTouchStart(e) {
                    this.touchStartX = e.changedTouches[0].screenX;
                },

                handleTouchEnd(e) {
                    this.touchEndX = e.changedTouches[0].screenX;
                    this.handleSwipe();
                },

                handleSwipe() {
                    const threshold = 50;
                    const diff = this.touchStartX - this.touchEndX;

                    if (diff > threshold) {
                        this.nextImage(); // Swipe left
                    } else if (diff < -threshold) {
                        this.prevImage(); // Swipe right
                    }
                },

                disableBodyScroll() {
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = this.scrollbarWidth + 'px';
                },

                enableBodyScroll() {
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                },

                get scrollbarWidth() {
                    return window.innerWidth - document.documentElement.clientWidth;
                },

                init() {
                    // Keyboard event listener
                    const handleKeyDown = (e) => {
                        if (!this.modalOpenDetail) return;

                        switch (e.key) {
                            case 'Escape':
                                this.closeModal();
                                break;
                            case 'ArrowRight':
                                this.nextImage();
                                break;
                            case 'ArrowLeft':
                                this.prevImage();
                                break;
                        }
                    };

                    document.addEventListener('keydown', handleKeyDown);

                    // Cleanup event listener when component is removed
                    this.$el.addEventListener('alpine:initialized', () => {
                        this.$el.addEventListener('alpine:destroying', () => {
                            document.removeEventListener('keydown', handleKeyDown);
                        });
                    });
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modalProperty', () => ({
                selectedProperty: {},
                modalOpenDetail: false,
                images: [],
                maxImages: 10,
                minImages: 3,
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

                // Enhanced photo upload methods
                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    const wasEmpty = this.images.length === 0;
                    this.processFiles(files);

                    if (wasEmpty && this.images.length > 0) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'info',
                            title: 'Foto pertama akan menjadi thumbnail properti',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });
                    }
                },

                handleDrop(event) {
                    event.preventDefault();
                    const files = Array.from(event.dataTransfer.files);
                    this.processFiles(files);
                },

                processFiles(files) {
                    const imageFiles = files.filter(file => file.type.startsWith('image/'));
                    const availableSlots = this.maxImages - this.images.length;

                    if (availableSlots <= 0) {
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: `Maksimal hanya ${this.maxImages} foto yang dapat diupload.`,
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

                removeImage(index, event) {
                    if (event) event.preventDefault(); // mencegah form submit
                    this.images.splice(index, 1);
                },

                get canUploadMore() {
                    return this.images.length < this.maxImages;
                },

                get remainingSlots() {
                    return this.maxImages - this.images.length;
                },

                get imageUploadStatus() {
                    const current = this.images.length;
                    if (current < this.minImages) {
                        return `Minimal ${this.minImages} foto diperlukan (${current}/${this.minImages})`;
                    } else if (current >= this.minImages && current < this.maxImages) {
                        return `${current}/${this.maxImages} foto (dapat menambah ${this.remainingSlots} lagi)`;
                    } else {
                        return `${current}/${this.maxImages} foto (maksimal tercapai)`;
                    }
                },

                get isImageRequirementMet() {
                    return this.images.length >= this.minImages;
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
                        // Updated validation for new image requirements
                        if (this.images.length < this.minImages) {
                            alert(
                                `Minimal ${this.minImages} foto properti harus diupload. Saat ini: ${this.images.length} foto.`
                            );
                            isValid = false;
                        } else if (this.images.length > this.maxImages) {
                            alert(
                                `Maksimal ${this.maxImages} foto properti dapat diupload. Saat ini: ${this.images.length} foto.`
                            );
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

                // Enhanced submit form with better image handling
                submitForm() {
                    if (!this.validateStep(4)) return;

                    // Store original button state
                    const submitBtn = document.querySelector('#propertyForm button[type="submit"]');
                    const originalBtnContent = submitBtn?.innerHTML;
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Menyimpan...
                                            `;
                    }

                    const form = document.getElementById('propertyForm');
                    const formData = new FormData(form);

                    // Clear any existing file inputs
                    formData.delete('property_images[]');

                    // Add each selected image
                    this.images.forEach((image, index) => {
                        formData.append('property_images[]', image.file);
                    });

                    // Add image count for backend validation
                    formData.append('image_count', this.images.length);

                    // Submit the form
                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            // First check if response is JSON
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                const text = await response.text();
                                throw new Error(
                                    `Expected JSON but got: ${text.substring(0, 100)}...`);
                            }

                            const data = await response.json();

                            if (!response.ok) {
                                // Handle server-side validation errors
                                let errorMsg = data.message || 'Submission failed';
                                if (data.errors) {
                                    errorMsg = Object.values(data.errors).join('\n');
                                }
                                throw new Error(errorMsg);
                            }

                            return data;
                        })
                        .then(data => {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: `Property berhasil disimpan dengan ${this.images.length} foto!`,
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                                didClose: () => {
                                    window.location.href =
                                        '{{ route('properties.index') }}';
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'Failed to submit form',
                            });
                        })
                        .finally(() => {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnContent;
                            }
                        });
                },

                // Helper method to get upload progress info
                getUploadInfo() {
                    return {
                        current: this.images.length,
                        min: this.minImages,
                        max: this.maxImages,
                        remaining: this.remainingSlots,
                        canUpload: this.canUploadMore,
                        isValid: this.isImageRequirementMet
                    };
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modalPropertyEdit', (property) => ({
                editModalOpen: false,
                editStep: 1,
                editMinImages: 3,
                editMaxImages: 10,
                editImages: [],
                map: null,
                marker: null,
                searchQuery: '',
                searchResults: [],
                isSearching: false,
                isSubmitting: false,
                originalPropertyData: {},
                propertyData: {
                    name: property.name || '',
                    tags: property.tags || 'House',
                    description: property.description || '',
                    address: property.address || '',
                    latitude: property.latitude || null,
                    longitude: property.longitude || null,
                    province: property.province || '',
                    city: property.city || '',
                    subdistrict: property.subdistrict || '',
                    village: property.village || '',
                    postal_code: property.postal_code || '',
                    features: property.features || [],
                    existingImages: property.existingImages || []
                },

                init() {
                    this.originalPropertyData = JSON.parse(JSON.stringify(this.propertyData));

                    this.$watch('editStep', (value) => {
                        if (value === 2) {
                            this.$nextTick(() => {
                                this.initMap();
                            });
                        }
                    });
                },

                get editRemainingSlots() {
                    return this.editMaxImages - (this.propertyData.existingImages.filter(img => !img
                        .markedForDeletion).length + this.editImages.length);
                },

                get editCanUploadMore() {
                    return (this.propertyData.existingImages.filter(img => !img.markedForDeletion)
                        .length + this.editImages.length) < this.editMaxImages;
                },

                get editUploadProgress() {
                    const totalCurrentImages = this.propertyData.existingImages.filter(img => !img
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
                    const totalCurrentImages = this.propertyData.existingImages.filter(img => !img
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

                get hasMinimumImages() {
                    const totalCurrentImages = this.editImages.length +
                        this.propertyData.existingImages.filter(img => !img.markedForDeletion)
                        .length;
                    return totalCurrentImages >= this.editMinImages;
                },

                openModal(data) {
                    this.propertyData = {
                        ...this.propertyData,
                        ...data
                    };
                    this.editModalOpen = true;
                    this.editStep = 1;
                    this.editImages = [];
                    this.searchResults = [];
                    this.searchQuery = '';

                    this.$nextTick(() => {
                        if (this.editStep === 2) {
                            this.initMap();
                        }
                    });
                },

                async initMap() {
                    try {
                        // Load Leaflet if not already loaded
                        if (typeof L === 'undefined') {
                            await this.loadLeaflet();
                        }

                        const mapId = `map_edit_${property.idrec}`;
                        const mapElement = document.getElementById(mapId);

                        if (!mapElement) {
                            console.error('Map element not found');
                            return;
                        }

                        // Ensure the map element has proper dimensions
                        if (mapElement.offsetHeight === 0) {
                            mapElement.style.height = '400px';
                        }

                        // Default to Jakarta coordinates if no coordinates are set
                        const defaultLat = -6.1754;
                        const defaultLng = 106.8272;

                        // Use property coordinates if available, otherwise use default
                        const initialLat = this.propertyData.latitude || defaultLat;
                        const initialLng = this.propertyData.longitude || defaultLng;

                        // Initialize map
                        this.map = L.map(mapId, {
                            preferCanvas: true,
                            zoomControl: true
                        }).setView([initialLat, initialLng], 15);

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
                        if (this.propertyData.latitude && this.propertyData.longitude) {
                            this.placeMarker({
                                lat: this.propertyData.latitude,
                                lng: this.propertyData.longitude
                            });
                        }

                        console.log('Map initialized successfully');
                    } catch (error) {
                        console.error('Error initializing map:', error);
                    }
                },

                async loadLeaflet() {
                    return new Promise((resolve) => {
                        if (typeof L !== 'undefined') {
                            resolve();
                            return;
                        }

                        // Load Leaflet CSS
                        const css = document.createElement('link');
                        css.rel = 'stylesheet';
                        css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                        css.integrity =
                            'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
                        css.crossOrigin = '';
                        document.head.appendChild(css);

                        // Load Leaflet JS
                        const js = document.createElement('script');
                        js.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                        js.integrity =
                            'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
                        js.crossOrigin = '';
                        js.onload = () => {
                            setTimeout(resolve, 50);
                        };
                        document.head.appendChild(js);
                    });
                },

                placeMarker(latlng) {
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }

                    this.marker = L.marker(latlng, {
                        draggable: true
                    }).addTo(this.map);

                    // Update coordinates display
                    const coordsElement = document.getElementById(`coordinates_edit_${property.idrec}`);
                    if (coordsElement) {
                        coordsElement.innerHTML =
                            `Koordinat: ${latlng.lat.toFixed(6)}, ${latlng.lng.toFixed(6)}`;
                    }

                    // Update property data
                    this.propertyData.latitude = latlng.lat;
                    this.propertyData.longitude = latlng.lng;

                    // Update marker position on drag
                    this.marker.on('dragend', (e) => {
                        const newLatLng = e.target.getLatLng();
                        this.reverseGeocode(newLatLng.lat, newLatLng.lng);

                        // Update coordinates display
                        if (coordsElement) {
                            coordsElement.innerHTML =
                                `Koordinat: ${newLatLng.lat.toFixed(6)}, ${newLatLng.lng.toFixed(6)}`;
                        }

                        // Update property data
                        this.propertyData.latitude = newLatLng.lat;
                        this.propertyData.longitude = newLatLng.lng;
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
                            // Also update the propertyData
                            if (id === `full_address_edit_${property.idrec}`) this.propertyData
                                .address = value || '';
                            if (id === `province_edit_${property.idrec}`) this.propertyData
                                .province = value || '';
                            if (id === `city_edit_${property.idrec}`) this.propertyData.city =
                                value || '';
                            if (id === `district_edit_${property.idrec}`) this.propertyData
                                .subdistrict = value || '';
                            if (id === `village_edit_${property.idrec}`) this.propertyData.village =
                                value || '';
                            if (id === `postal_code_edit_${property.idrec}`) this.propertyData
                                .postal_code = value || '';
                        }
                    };

                    // Update form fields based on reverse geocoding results
                    updateField(`full_address_edit_${property.idrec}`,
                        address.road || address.hamlet || address.village ||
                        address.town || address.city || '');
                    updateField(`province_edit_${property.idrec}`, address.state || address.region ||
                        '');
                    updateField(`city_edit_${property.idrec}`, address.city || address.town || address
                        .county || '');
                    updateField(`district_edit_${property.idrec}`, address.suburb || address
                        .city_district || '');
                    updateField(`village_edit_${property.idrec}`,
                        address.village || address.hamlet || address.neighbourhood || '');
                    updateField(`postal_code_edit_${property.idrec}`, address.postcode || '');
                },

                resizeMap() {
                    if (this.map) {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 100);
                    }
                },

                handleEditFileSelect(event) {
                    const files = Array.from(event.target.files);
                    this.processFiles(files);
                },               

                processFiles(files) {
                    const imageFiles = files.filter(file => file.type.startsWith('image/'));
                    const availableSlots = this.editMaxImages - this.editImages.length;

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

                removeEditImage(index) {
                    this.editImages.splice(index, 1);
                },

                removeEditExistingImage(index) {
                    this.propertyData.existingImages[index].markedForDeletion = true;
                },

                nextStep() {
                    if (this.validateEditStep()) {
                        this.editStep++;
                        if (this.editStep === 2) {
                            this.$nextTick(() => {
                                this.resizeMap();
                            });
                        }
                    }
                },

                prevStep() {
                    this.editStep--;
                    if (this.editStep === 2) {
                        this.$nextTick(() => {
                            this.resizeMap();
                        });
                    }
                },

                validateEditStep() {
                    if (this.editStep === 1) {
                        if (!this.propertyData.name.trim()) {
                            alert('Nama properti harus diisi');
                            return false;
                        }
                        if (!this.propertyData.description.trim()) {
                            alert('Deskripsi properti harus diisi');
                            return false;
                        }
                    } else if (this.editStep === 2) {
                        if (!this.propertyData.address.trim()) {
                            alert('Alamat lengkap harus diisi');
                            return false;
                        }

                        if (!this.propertyData.province.trim() || !this.propertyData.city.trim() ||
                            !this.propertyData.subdistrict.trim() || !this.propertyData.village.trim()
                        ) {
                            alert('Semua detail lokasi harus diisi');
                            return false;
                        }

                        if (!this.propertyData.latitude || !this.propertyData.longitude) {
                            alert('Pinpoint lokasi wajib dipilih');
                            return false;
                        }
                    } else if (this.editStep === 4) {
                        const totalImages = this.editImages.length +
                            this.propertyData.existingImages.filter(img => !img.markedForDeletion)
                            .length;

                        if (totalImages < this.editMinImages) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: `Harap unggah minimal ${this.editMinImages} foto properti (Saat ini: ${totalImages})`,
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
                    this.searchResults = [];

                    // Clean up map
                    if (this.map) {
                        this.map.remove();
                        this.map = null;
                        this.marker = null;
                    }
                },

                async submitEditForm() {
                    if (!this.validateEditStep() || this.isSubmitting) return;
                    this.isSubmitting = true;

                    // Store the submit button reference and original text
                    const submitBtn = document.querySelector(
                        `#propertyFormEdit-${property.idrec} button[type="submit"]`);
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
                        // Create FormData for the submission
                        const formData = new FormData();

                        // Add all property data
                        for (const [key, value] of Object.entries(this.propertyData)) {
                            if (key === 'existingImages') continue; // Handled separately

                            if (Array.isArray(value)) {
                                value.forEach(item => formData.append(`${key}[]`, item));
                            } else {
                                formData.append(key, value);
                            }
                        }

                        // Append new images
                        this.editImages.forEach((image, index) => {
                            formData.append(`property_images[${index}]`, image.file);
                        });

                        // Append images to delete
                        this.propertyData.existingImages
                            .filter(img => img.markedForDeletion)
                            .forEach(img => {
                                formData.append('delete_images[]', img.id);
                            });

                        // Add CSRF token
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .content);
                        formData.append('_method', 'PUT');

                        const response = await fetch(document.getElementById(
                            `propertyFormEdit-${property.idrec}`).action, {
                            method: 'POST', // Laravel handles PUT via POST with _method
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
                            throw new Error(data.message || 'Gagal memperbarui properti');
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Properti berhasil diperbarui!',
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
                }
            }));
        });

        // Fungsi untuk mengambil data dengan AJAX
        function fetchData() {
            const formData = new FormData(document.getElementById('searchForm'));
            const params = new URLSearchParams(formData).toString();

            fetch(`/properties/m-properties/filter?${params}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('propertyTableContainer').innerHTML = data.html;
                    document.getElementById('paginationContainer').innerHTML = data.pagination;
                })
                .catch(error => console.error('Error:', error));
        }

        // Event listeners untuk filter dan pencarian
        document.getElementById('searchInput').addEventListener('input', debounce(fetchData, 500));
        document.getElementById('statusFilter').addEventListener('change', fetchData);
        document.getElementById('perPageSelect').addEventListener('change', fetchData);

        // Fungsi debounce untuk menunda eksekusi
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    func.apply(context, args);
                }, wait);
            };
        }

        // Fungsi toggle status
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
                    if (!response.ok) throw new Error('Gagal update status');
                    return response.json();
                })
                .then(() => {
                    // ✅ Refresh tabel
                    fetch(`/properties/m-properties/table?per_page=8`)
                        .then(res => res.text())
                        .then(html => {
                            document.getElementById('propertyTableContainer').innerHTML = html;
                        });

                    // ✅ Notifikasi
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
