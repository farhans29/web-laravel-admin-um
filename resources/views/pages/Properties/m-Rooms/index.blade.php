<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Kamar</h1>
            <!-- New Input Room -->
            <div x-data="modalRoom()">
                <!-- Trigger Button -->
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center"
                    type="button" @click.prevent="modalOpen = true;" aria-controls="room-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Kamar
                </button>

                <!-- Modal -->
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity" x-show="modalOpen"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
                    x-cloak></div>

                <div id="room-modal"
                    class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                    role="dialog" aria-modal="true" x-show="modalOpen"
                    x-transition:enter="transition ease-in-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in-out duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak>

                    <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full flex flex-col text-left"
                        @click.outside="modalOpen = false" @keydown.escape.window="modalOpen = false">

                        <!-- Modal header with step indicator -->
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <div class="flex justify-between items-center mb-4">
                                <div class="font-bold text-xl text-gray-800">Tambahkan Kamar</div>
                                <button type="button"
                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                    @click="modalOpen = false">
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
                                            :class="step >= 1 ? 'text-blue-600' : 'text-gray-500'">Informasi Dasar</p>
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
                                            :class="step >= 2 ? 'text-blue-600' : 'text-gray-500'">Harga</p>
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
                                            :class="step >= 3 ? 'text-blue-600' : 'text-gray-500'">Fasilitas</p>
                                    </div>
                                </div>

                                <!-- Connector -->
                                <div class="w-16 h-0.5 transition-colors duration-300"
                                    :class="step >= 4 ? 'bg-blue-600' : 'bg-gray-300'"></div>

                                <!-- Step 4 -->
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
                            <form id="roomForm" method="POST" action="{{ route('rooms.store') }}"
                                enctype="multipart/form-data" @submit.prevent="submitForm">
                                @csrf

                                <!-- Step 1 - Basic Information -->
                                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-x-4"
                                    x-transition:enter-end="opacity-100 translate-x-0">
                                    <div class="space-y-6">
                                        <!-- Property Selector -->
                                        <div>
                                            <label for="property_id"
                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                Properti <span class="text-red-500">*</span>
                                            </label>
                                            <select id="property_id" name="property_id" required
                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                                <option value="" disabled selected>Pilih Properti</option>
                                                @foreach ($properties as $property)
                                                    <option value="{{ $property->idrec }}">{{ $property->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="room_no"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nomor Kamar <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" id="room_no" name="room_no" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan nomor kamar">
                                            </div>

                                            <div>
                                                <label for="room_name"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nama Kamar <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" id="room_name" name="room_name" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan nama kamar">
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label for="room_size"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Ukuran Kamar (m²) <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" id="room_size" name="room_size" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan ukuran kamar">
                                            </div>

                                            <div>
                                                <label for="room_bed"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Jenis Kasur <span class="text-red-500">*</span>
                                                </label>
                                                <select id="room_bed" name="room_bed" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                                    <option value="">Pilih Jenis Kasur</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Double">Double</option>
                                                    <option value="King">King</option>
                                                    <option value="Queen">Queen</option>
                                                    <option value="Twin">Twin</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="room_capacity"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Kapasitas (Pax) <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" id="room_capacity" name="room_capacity"
                                                    required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan kapasitas kamar">
                                            </div>
                                        </div>

                                        <div>
                                            <label for="description_id"
                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                Deskripsi Kamar <span class="text-red-500">*</span>
                                            </label>
                                            <textarea id="description_id" name="description_id" rows="4" required
                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                placeholder="Deskripsikan kamar Anda..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2 - Pricing -->
                                <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-x-4"
                                    x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                    <div class="space-y-6">
                                        <div class="mb-4">
                                            <h3 class="text-md font-semibold text-gray-700 mb-2">Jenis Harga</h3>
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
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500">Rp</span>
                                                </div>
                                                <input type="text" x-ref="dailyPriceInput"
                                                    class="w-full pl-10 border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan harga harian">
                                                <input type="hidden" name="daily_price" x-model="dailyPrice">
                                            </div>
                                        </div>

                                        <div x-show="priceTypes.includes('monthly')" x-transition class="mt-4">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                Harga Bulanan <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500">Rp</span>
                                                </div>
                                                <input type="text" x-ref="monthlyPriceInput"
                                                    class="w-full pl-10 border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Masukkan harga bulanan">
                                                <input type="hidden" name="monthly_price" x-model="monthlyPrice">
                                            </div>
                                        </div>

                                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mt-4">
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
                                                        Anda bisa memilih salah satu atau kedua jenis harga. Pastikan
                                                        mengisi harga yang sesuai dengan jenis yang dipilih.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3 - Facilities -->
                                <div x-show="step === 3" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-x-4"
                                    x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                    <div class="space-y-6">
                                        <div>
                                            <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                </svg>
                                                Fasilitas Kamar
                                            </h3>
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                <template x-for="(facility, index) in facilities"
                                                    :key="index">
                                                    <div class="relative">
                                                        <input :id="'facility-' + index" name="facilities[]"
                                                            type="checkbox" :value="facility.value"
                                                            class="sr-only peer">
                                                        <label :for="'facility-' + index"
                                                            class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                            <span x-text="facility.label"></span>
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
                                                Foto Kamar <span class="text-red-500">*</span>
                                                <span class="text-sm font-normal text-gray-500">
                                                    (Wajib 3 foto - <span x-text="remainingSlots"></span> foto lagi)
                                                </span>
                                            </label>

                                            <!-- Info about thumbnail -->
                                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-r-lg">
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
                                                            <span class="font-semibold">Perhatian:</span> Foto pertama
                                                            yang Anda upload akan menjadi <span
                                                                class="font-bold">thumbnail utama</span> kamar ini.
                                                            Pastikan foto pertama adalah yang terbaik!
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Upload Area -->
                                            <div x-show="canUploadMore" @drop="handleDrop($event)" @dragover.prevent
                                                @dragenter.prevent
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
                                                        <label for="room_images"
                                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                            <span>Upload foto</span>
                                                            <input id="room_images" name="room_images[]"
                                                                type="file" multiple accept="image/*"
                                                                @change="handleFileSelect($event)" class="sr-only">
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
                                                    <p class="text-xs text-green-500">Semua slot foto telah terisi</p>
                                                </div>
                                            </div>

                                            <!-- Image Preview Grid -->
                                            <div x-show="images.length > 0" class="mt-2 grid grid-cols-5 gap-1"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100">
                                                <template x-for="(image, index) in images" :key="index">
                                                    <div class="relative group">
                                                        <!-- Image Container -->
                                                        <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-400 transition-colors duration-200"
                                                            :class="{ 'border-2 border-blue-600': index === 0 }">
                                                            <img :src="image.url" :alt="`Preview ${index + 1}`"
                                                                class="w-full h-full object-cover">
                                                        </div>

                                                        <!-- Remove Button -->
                                                        <button @click="removeImage(index)"
                                                            class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[8px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                            <svg class="w-2 h-2" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>

                                                        <!-- Image Number Badge -->
                                                        <div
                                                            class="absolute bottom-1 left-1 bg-blue-600 text-white text-[8px] px-1 py-0.5 rounded-full font-medium">
                                                            <span x-text="index + 1"></span>
                                                        </div>

                                                        <!-- Thumbnail indicator for first image -->
                                                        <div x-show="index === 0" class="absolute top-1 right-1">
                                                            <span
                                                                class="bg-yellow-500 text-white text-[8px] px-1 py-0.5 rounded-full font-medium">Thumbnail</span>
                                                        </div>

                                                        <!-- File Name -->
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
                                                        :style="`width: ${(images.length / maxImages) * 100}%`"></div>
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
                                                    Foto pertama (ditandai dengan label "Thumbnail") akan menjadi gambar
                                                    utama kamar Anda.
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            Sebelumnya
                                        </button>
                                        <button type="button" x-show="step < 4"
                                            @click="validateStep(step) && step++"
                                            class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            Selanjutnya
                                            <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                        <button type="submit" x-show="step === 4"
                                            class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
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

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Search and Filter Section -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <div>
                        <select id="room-filter"
                            class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="" hidden>Pilih Properti</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->idrec }}">{{ $property->name }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div>
                        <select id="status-filter"
                            class="w-full md:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <input type="text" id="search-input" placeholder="Cari kamar..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button id="filter-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Filter
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto" id="roomTableContainer">
                @include('pages.Properties.m-Rooms.partials.room_table', [
                    'properties' => $properties,
                    'per_page' => request('per_page', 8),
                ])
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 rounded p-4" id="paginationContainer">
                {{ $rooms->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modalRoom', () => ({
                modalOpen: false,
                step: 1,
                images: [],
                maxImages: 10,
                minImages: 3,
                priceTypes: [], // For tracking daily/monthly price selection
                dailyPrice: 0,
                monthlyPrice: 0,
                facilities: [{
                        label: 'AC',
                        value: 'ac'
                    },
                    {
                        label: 'TV',
                        value: 'tv'
                    },
                    {
                        label: 'Kamar Mandi',
                        value: 'bathroom'
                    },
                    {
                        label: 'WiFi',
                        value: 'wifi'
                    },
                    {
                        label: 'Lemari',
                        value: 'wardrobe'
                    },
                    {
                        label: 'Meja Kerja',
                        value: 'desk'
                    },
                    {
                        label: 'Kulkas Mini',
                        value: 'refrigerator'
                    },
                    {
                        label: 'Air Panas',
                        value: 'hot_water'
                    },
                    {
                        label: 'Sarapan',
                        value: 'breakfast'
                    }
                ],

                init() {
                    // Initialize price input formatting
                    if (this.$refs.dailyPriceInput) {
                        new Cleave(this.$refs.dailyPriceInput, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand'
                        });
                    }

                    if (this.$refs.monthlyPriceInput) {
                        new Cleave(this.$refs.monthlyPriceInput, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand'
                        });
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
                            title: 'Foto pertama akan menjadi thumbnail kamar',
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
                    if (event) event.preventDefault();
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
                        const requiredFields = ['property_id', 'room_no', 'room_name', 'room_size',
                            'room_bed', 'room_capacity', 'description_id'
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

                    } else if (step === 2) {
                        // Validate at least one price type is selected
                        if (this.priceTypes.length === 0) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Pilih minimal satu jenis harga!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            isValid = false;
                        }

                        // Validate the selected price types have values
                        if (this.priceTypes.includes('daily') && !this.$refs.dailyPriceInput.value) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Masukkan harga harian!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            isValid = false;
                        }

                        if (this.priceTypes.includes('monthly') && !this.$refs.monthlyPriceInput
                            .value) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Masukkan harga bulanan!',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                            isValid = false;
                        }

                        // Convert formatted prices to numbers
                        if (this.priceTypes.includes('daily')) {
                            this.dailyPrice = this.$refs.dailyPriceInput.value.replace(/\D/g, '');
                            if (this.dailyPrice === '0') {
                                isValid = false;
                                alert('Harga harian tidak boleh 0');
                            }
                        }

                        if (this.priceTypes.includes('monthly')) {
                            this.monthlyPrice = this.$refs.monthlyPriceInput.value.replace(/\D/g, '');
                            if (this.monthlyPrice === '0') {
                                isValid = false;
                                alert('Harga bulanan tidak boleh 0');
                            }
                        }

                    } else if (step === 3) {
                        // No validation needed for step 3 (facilities) as they're optional
                    } else if (step === 4) {
                        if (this.images.length < this.minImages) {
                            alert(
                                `Minimal ${this.minImages} foto kamar harus diupload. Saat ini: ${this.images.length} foto.`
                            );
                            isValid = false;
                        } else if (this.images.length > this.maxImages) {
                            alert(
                                `Maksimal ${this.maxImages} foto kamar dapat diupload. Saat ini: ${this.images.length} foto.`
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

                submitForm() {
                    if (!this.validateStep(4)) return;

                    // Store original button state
                    const submitBtn = document.querySelector('#roomForm button[type="submit"]');
                    const originalBtnContent = submitBtn?.innerHTML;
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                Menyimpan...
                                            `;
                    }

                    const form = document.getElementById('roomForm');
                    const formData = new FormData(form);

                    // Clear any existing file inputs
                    formData.delete('room_images[]');

                    // Add each selected image
                    this.images.forEach((image, index) => {
                        formData.append('room_images[]', image.file);
                    });

                    // Add image count for backend validation
                    formData.append('image_count', this.images.length);

                    // Add price types
                    formData.append('price_types', JSON.stringify(this.priceTypes));

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
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                const text = await response.text();
                                throw new Error(
                                    `Expected JSON but got: ${text.substring(0, 100)}...`);
                            }

                            const data = await response.json();

                            if (!response.ok) {
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
                                title: `Kamar berhasil disimpan dengan ${this.images.length} foto!`,
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                                didClose: () => {
                                    window.location.reload();
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
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modalViewRoom', () => ({
                selectedRoom: {
                    currentImageIndex: 0,
                    images: [],
                    facilities: []
                },
                modalOpenDetail: false,
                isLoading: false,
                touchStartX: 0,
                touchEndX: 0,

                openModal(room) {
                    this.isLoading = true;
                    this.modalOpenDetail = true;
                    this.disableBodyScroll();

                    this.$nextTick(() => {
                        this.selectedRoom = {
                            ...room,
                            currentImageIndex: 0,
                            images: Array.isArray(room.images) ?
                                room.images.filter(img => img && img.startsWith(
                                    'data:image')) : [],
                            facilities: Array.isArray(room.facilities) ? room.facilities :
                            []
                        };
                        this.isLoading = false;
                    });
                },

                closeModal() {
                    this.modalOpenDetail = false;
                    this.enableBodyScroll();
                    setTimeout(() => {
                        this.selectedRoom = {
                            currentImageIndex: 0,
                            images: [],
                            facilities: []
                        };
                    }, 300);
                },

                nextImage() {
                    if (this.hasMultipleImages) {
                        this.selectedRoom.currentImageIndex =
                            (this.selectedRoom.currentImageIndex + 1) % this.selectedRoom.images.length;
                    }
                },

                prevImage() {
                    if (this.hasMultipleImages) {
                        this.selectedRoom.currentImageIndex =
                            (this.selectedRoom.currentImageIndex - 1 + this.selectedRoom.images
                                .length) %
                            this.selectedRoom.images.length;
                    }
                },

                goToImage(index) {
                    if (this.hasMultipleImages && index >= 0 && index < this.selectedRoom.images
                        .length) {
                        this.selectedRoom.currentImageIndex = index;
                    }
                },

                get hasMultipleImages() {
                    return this.selectedRoom.images?.length > 1;
                },

                get currentImage() {
                    return this.selectedRoom.images[this.selectedRoom.currentImageIndex];
                },

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
                        this.nextImage();
                    } else if (diff < -threshold) {
                        this.prevImage();
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

                    this.$el.addEventListener('alpine:initialized', () => {
                        this.$el.addEventListener('alpine:destroying', () => {
                            document.removeEventListener('keydown', handleKeyDown);
                        });
                    });
                }
            }));
        });

        flatpickr.localize({
            id: {
                weekdays: {
                    shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                    longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
                },
                months: {
                    shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
                    longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                        "September", "Oktober", "November", "Desember"
                    ]
                },
                firstDayOfWeek: 1, // Monday as first day
                rangeSeparator: " hingga ",
                weekAbbreviation: "Mgg",
                scrollTitle: "Scroll untuk memperbesar",
                toggleTitle: "Klik untuk mengganti",
                time_24hr: true,
                ordinal: () => {
                    return "";
                }
            }
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('priceModal', (roomId = null, basePrice = 0) => ({
                isOpen: false,
                isLoading: false,
                startDate: '',
                setPrice: '',
                cleaveInstance: null,
                priceMap: {},
                roomId: roomId,
                basePrice: basePrice,
                fpInstance: null,

                // Computed properties
                get formattedDatePrice() {
                    const price = this.priceMap[this.startDate];
                    if (price === undefined || price === null || isNaN(price)) return '-';
                    return this.formatCurrency(price);
                },

                get formattedBasePrice() {
                    const price = this.basePrice;
                    if (price === undefined || price === null || isNaN(price)) return '-';
                    return this.formatCurrency(price);
                },

                // Methods
                openModal() {
                    this.isOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeModal() {
                    this.isOpen = false;
                    document.body.classList.remove('overflow-hidden');
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(value);
                },

                async fetchMonthPrices(year, month, fpInstance) {
                    try {
                        const res = await fetch(
                            `/properties/rooms/${this.roomId}/prices?year=${year}&month=${month}`
                        );

                        if (!res.ok) throw new Error('Failed to fetch prices');

                        const data = await res.json();
                        this.priceMap = data;
                        fpInstance.redraw();
                    } catch (error) {
                        console.error('Error fetching prices:', error);
                        this.showAlert('error', 'Gagal memuat data harga');
                    }
                },

                async updatePrice() {
                    if (!this.startDate) {
                        this.showAlert('warning', 'Silakan pilih tanggal terlebih dahulu');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const priceValue = this.setPrice ? parseFloat(this.cleaveInstance
                            .getRawValue()) : null;

                        const res = await fetch(`/properties/rooms/${this.roomId}/update-price`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                start_date: this.startDate,
                                end_date: this.startDate,
                                price: priceValue
                            })
                        });

                        const data = await res.json();

                        if (!res.ok) {
                            throw new Error(data.message || 'Gagal memperbarui harga');
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Harga berhasil diperbarui!',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        const date = this.startDate;
                        const [year, month] = date.split('-');
                        await this.fetchMonthPrices(parseInt(year), parseInt(month), this
                            .fpInstance);

                        // Reset form
                        this.cleaveInstance.setRawValue('');
                        this.setPrice = '';
                    } catch (error) {
                        console.error('Update error:', error);
                        this.showAlert('error', error.message ||
                            'Terjadi kesalahan saat memperbarui harga');
                    } finally {
                        this.isLoading = false;
                    }
                },

                showAlert(type, message) {
                    Swal.fire({
                        icon: type,
                        title: message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },

                init() {
                    const self = this;

                    // Initialize calendar
                    const calendar = flatpickr(this.$el.querySelector('.inline-calendar'), {
                        inline: true,
                        mode: 'single',
                        dateFormat: 'Y-m-d',
                        minDate: 'today',

                        async onReady(selectedDates, dateStr, instance) {
                            self.fpInstance = instance;
                            await self.fetchMonthPrices(instance.currentYear, instance
                                .currentMonth + 1, instance);

                            // Select today's date by default
                            if (selectedDates.length === 0) {
                                instance.setDate(new Date());
                            }
                        },

                        async onMonthChange(selectedDates, dateStr, instance) {
                            await self.fetchMonthPrices(instance.currentYear, instance
                                .currentMonth + 1, instance);
                        },

                        async onChange(dates) {
                            if (dates.length > 0) {
                                const localDate = dates[0];
                                const year = localDate.getFullYear();
                                const month = String(localDate.getMonth() + 1).padStart(2,
                                    '0');
                                const day = String(localDate.getDate()).padStart(2, '0');
                                self.startDate = `${year}-${month}-${day}`;

                                // Set current price in the input field if available
                                const currentPrice = self.priceMap[self.startDate];
                                if (currentPrice && self.cleaveInstance) {
                                    self.cleaveInstance.setRawValue(currentPrice);
                                }
                            }
                        },

                        onDayCreate(dObj, dStr, fp, dayElem) {
                            if (dayElem.classList.contains('prevMonthDay') || dayElem.classList
                                .contains('nextMonthDay')) {
                                return;
                            }

                            const localDate = dayElem.dateObj;
                            const year = localDate.getFullYear();
                            const month = String(localDate.getMonth() + 1).padStart(2, '0');
                            const day = String(localDate.getDate()).padStart(2, '0');
                            const date = `${year}-${month}-${day}`;
                            const price = self.priceMap[date];

                            if (price === undefined || price === null || price == 0) {
                                dayElem.style.backgroundColor = '#d1d5db'; // gray-300 for empty
                            } else if (parseInt(price) === parseInt(self.basePrice)) {
                                dayElem.style.backgroundColor =
                                    '#3b82f6'; // blue-500 for base price
                            } else if (parseInt(price) > parseInt(self.basePrice)) {
                                dayElem.style.backgroundColor =
                                    '#ef4444'; // red-500 for higher price
                            } else {
                                dayElem.style.backgroundColor =
                                    '#4CAF50'; // green-500 for lower price
                            }

                            dayElem.style.color = '#ffffff';
                        }
                    });

                    this.fpInstance = calendar;

                    // Initialize price input formatter
                    this.$nextTick(() => {
                        this.cleaveInstance = new Cleave(this.$refs.setPrice, {
                            numeral: true,
                            numeralDecimalMark: ',',
                            delimiter: '.',
                            numeralThousandsGroupStyle: 'thousand',
                            onValueChanged: (e) => {
                                this.setPrice = parseFloat(e.target.rawValue || 0);
                            }
                        });
                    });
                }
            }));
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('modalRoomEdit', (room) => ({
                editModalOpen: false,
                editStep: 1,
                editMinImages: 3,
                editMaxImages: 10,
                editImages: [],
                priceTypes: ['daily', 'monthly'],
                dailyPrice: 0,
                monthlyPrice: 0,
                facilities: [{
                        label: 'AC',
                        value: 'ac'
                    },
                    {
                        label: 'TV',
                        value: 'tv'
                    },
                    {
                        label: 'Kamar Mandi',
                        value: 'bathroom'
                    },
                    {
                        label: 'WiFi',
                        value: 'wifi'
                    },
                    {
                        label: 'Lemari',
                        value: 'wardrobe'
                    },
                    {
                        label: 'Meja Kerja',
                        value: 'desk'
                    },
                    {
                        label: 'Kulkas Mini',
                        value: 'refrigerator'
                    },
                    {
                        label: 'Air Panas',
                        value: 'hot_water'
                    },
                    {
                        label: 'Sarapan',
                        value: 'breakfast'
                    }
                ],
                isSubmitting: false,
                originalRoomData: {},
                roomData: {
                    id: room.idrec || '',
                    property_id: room.property_id || '',
                    name: room.name || '',
                    number: room.no || '',
                    size: room.size || '',
                    bed: room.bed_type || 'Single',
                    capacity: room.capacity || '',
                    description: room.descriptions || '',
                    daily_price: room.price_original_daily ? room.price_original_daily : '',
                    monthly_price: room.price_original_monthly ? room.price_original_monthly : '',

                    facilities: room.facility || [],
                    existingImages: room.roomImages || []
                },

                init() {
                    this.originalRoomData = JSON.parse(JSON.stringify(this.roomData));

                    // Initialize price types based on existing prices
                    this.priceTypes = [];
                    if (this.roomData.daily_price) this.priceTypes.push('daily');
                    if (this.roomData.monthly_price) this.priceTypes.push('monthly');

                    // Convert price strings to numbers for hidden inputs
                    this.dailyPrice = this.roomData.daily_price ? parseInt(this.roomData.daily_price
                        .replace(/\./g, '')) : 0;
                    this.monthlyPrice = this.roomData.monthly_price ? parseInt(this.roomData
                        .monthly_price.replace(/\./g, '')) : 0;
                },

                get editRemainingSlots() {
                    return this.editMaxImages - (this.roomData.existingImages.filter(img => !img
                        .markedForDeletion).length + this.editImages.length);
                },

                get editCanUploadMore() {
                    return (this.roomData.existingImages.filter(img => !img.markedForDeletion)
                        .length + this.editImages.length) < this.editMaxImages;
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
                    this.roomData = {
                        ...this.roomData,
                        ...data
                    };
                    this.editModalOpen = true;
                    this.editStep = 1;
                    this.editImages = [];
                    this.priceTypes = [];
                    if (this.roomData.daily_price) this.priceTypes.push('daily');
                    if (this.roomData.monthly_price) this.priceTypes.push('monthly');
                },

                handleEditFileSelect(event) {
                    const files = Array.from(event.target.files);
                    this.processEditFiles(files);
                },

                handleEditDrop(event) {
                    event.preventDefault();
                    const files = Array.from(event.dataTransfer.files);
                    this.processEditFiles(files);
                },

                processEditFiles(files) {
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
                    this.roomData.existingImages[index].markedForDeletion = true;
                },

                validateEditStep(step) {
                    if (step === 1) {
                        if (!this.roomData.property_id) {
                            alert('Properti harus dipilih');
                            return false;
                        }
                        if (!this.roomData.name.trim()) {
                            alert('Nama kamar harus diisi');
                            return false;
                        }
                        if (!this.roomData.number.trim()) {
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
                        if (!this.roomData.description.trim()) {
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

                    // Store the submit button reference and original text
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
                        // Create FormData for the submission
                        const formData = new FormData();

                        // Add all room data
                        for (const [key, value] of Object.entries(this.roomData)) {
                            if (key === 'existingImages') continue; // Handled separately

                            if (Array.isArray(value)) {
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

                        // Add price values
                        formData.append('daily_price', this.dailyPrice);
                        formData.append('monthly_price', this.monthlyPrice);

                        // Add CSRF token
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .content);
                        formData.append('_method', 'PUT');

                        const response = await fetch(document.getElementById(
                            `roomFormEdit-${this.roomData.id}`).action, {
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
                }
            }));
        });

        function toggleStatus(checkbox) {
            const room = checkbox.getAttribute('data-id');
            const newStatus = checkbox.checked ? 1 : 0;

            fetch(`/properties/rooms/${room}/status`, {
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

        function deleteRoom(id) {
            Swal.fire({
                title: 'Hapus kamar ini?',
                text: 'Data kamar tidak akan tampil lagi setelah dihapus.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/properties/rooms/${id}/destroy`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal menghapus data');
                            return response.json();
                        })
                        .then(data => {
                            // Notifikasi sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // refresh halaman setelah toast hilang
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Notifikasi error
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Terjadi kesalahan saat menghapus!',
                            });
                        });
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Elemen filter
            const roomFilter = document.getElementById('room-filter');
            const statusFilter = document.getElementById('status-filter');
            const searchInput = document.getElementById('search-input');
            const filterBtn = document.getElementById('filter-btn');

            // Fungsi untuk memproses filter
            function applyFilters() {
                const propertyId = roomFilter.value;
                const status = statusFilter.value;
                const searchQuery = searchInput.value.trim();

                // Kirim permintaan AJAX
                fetchRooms(propertyId, status, searchQuery);
            }

            // Event listeners
            filterBtn.addEventListener('click', applyFilters);

            // Juga bisa trigger filter saat enter di search input
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    applyFilters();
                }
            });

            // Fungsi AJAX untuk mengambil data
            function fetchRooms(propertyId, status, searchQuery) {
                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);

                // Update parameter
                if (propertyId) params.set('property_id', propertyId);
                else params.delete('property_id');

                if (status !== '') params.set('status', status);
                else params.delete('status');

                if (searchQuery) params.set('search', searchQuery);
                else params.delete('search');

                // Simpan per_page value jika ada
                const perPage = params.get('per_page') || '8';
                params.set('per_page', perPage);

                // Tampilkan loading indicator jika diperlukan
                document.getElementById('roomTableContainer').innerHTML =
                    '<div class="p-4 text-center">Loading...</div>';

                // Kirim permintaan
                fetch(`{{ route('rooms.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        // Pastikan container ada sebelum mengupdate
                        const tableContainer = document.getElementById('roomTableContainer');
                        const paginationContainer = document.getElementById('paginationContainer');

                        if (tableContainer) tableContainer.innerHTML = data.html || '';
                        if (paginationContainer) {
                            paginationContainer.innerHTML = data.pagination || '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const container = document.getElementById('roomTableContainer');
                        if (container) {
                            container.innerHTML =
                                '<div class="p-4 text-center text-red-500">Error loading data</div>';
                        }
                    });
            }

            // Inisialisasi filter dari URL jika ada
            function initializeFiltersFromUrl() {
                const urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('property_id')) {
                    roomFilter.value = urlParams.get('property_id');
                }

                if (urlParams.has('status')) {
                    statusFilter.value = urlParams.get('status');
                }

                if (urlParams.has('search')) {
                    searchInput.value = urlParams.get('search');
                }
            }

            initializeFiltersFromUrl();
        });
    </script>

</x-app-layout>
