<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Manajemen Kamar
            </h1>
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

                    <div class="bg-white rounded-2xl shadow-lg overflow-auto w-3/4 max-h-full flex flex-col text-left"
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

                                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                                            <div>
                                                <label for="room_no"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nomor Kamar <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" id="room_no" name="room_no" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                                    placeholder="Nomor Kamar">
                                            </div>

                                            <div>
                                                <label for="room_name"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Nama / Tipe Kamar <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" id="room_name" name="room_name" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                                    placeholder="Nama / Tipe Kamar">
                                            </div>

                                            <div>
                                                <label for="room_bed"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Jenis Tempat Tidur <span class="text-red-500">*</span>
                                                </label>
                                                <select id="room_bed" name="room_bed" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4">
                                                    <option value="">Pilih Jenis Tempat Tidur</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Twin">Twin</option>
                                                    <option value="Double">Double</option>
                                                    <option value="Queen">Queen</option>
                                                    <option value="King">King</option>
                                                </select>
                                            </div>



                                            <div>
                                                <label for="room_capacity"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Kapasitas (Pax) <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" id="room_capacity" name="room_capacity"
                                                    required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                                    placeholder="Kapasitas" readonly>
                                            </div>
                                            <div>
                                                <label for="room_size"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Ukuran Kamar (m²) <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" id="room_size" name="room_size" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4"
                                                    placeholder="Ukuran">
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
                                            <p x-show="dailyPriceError" class="text-red-500 text-xs mt-1"
                                                x-text="dailyPriceError"></p>
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
                                            <p x-show="monthlyPriceError" class="text-red-500 text-xs mt-1"
                                                x-text="monthlyPriceError"></p>
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

                                            <!-- Facilities Grid -->
                                            <div
                                                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
                                                @foreach ($facilities as $facility)
                                                    <div class="relative">
                                                        <label
                                                            class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                                            <input type="checkbox" name="general_facilities[]"
                                                                value="{{ $facility->idrec }}"
                                                                id="facility_{{ $facility->idrec }}"
                                                                class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">

                                                            <div class="ml-3 flex-1">
                                                                <span class="block text-sm font-medium text-gray-900">
                                                                    {{ $facility->facility }}
                                                                </span>
                                                                @if (!empty($facility->description))
                                                                    <span class="block text-xs text-gray-500 mt-1">
                                                                        {{ $facility->description }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- No Facilities Available Message -->
                                            @if ($facilities->isEmpty())
                                                <div class="text-center py-8">
                                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <p class="mt-2 text-sm text-gray-500">Tidak ada fasilitas tersedia
                                                    </p>
                                                </div>
                                            @endif
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

                                            <!-- Thumbnail Selection Area -->
                                            <div class="mb-6">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2">
                                                    Pilih Thumbnail <span class="text-red-500">*</span>
                                                    <span class="text-xs font-normal text-gray-500">(Foto utama yang
                                                        akan ditampilkan)</span>
                                                </h4>

                                                <div class="flex items-center space-x-4">
                                                    <!-- Thumbnail Preview -->
                                                    <div class="w-32 h-32 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 overflow-hidden relative"
                                                        x-show="images.length > 0">
                                                        <template x-if="thumbnailIndex !== null">
                                                            <img :src="images[thumbnailIndex].url"
                                                                alt="Selected Thumbnail"
                                                                class="w-full h-full object-cover">
                                                        </template>
                                                        <div class="absolute inset-0 flex items-center justify-center text-gray-400"
                                                            x-show="thumbnailIndex === null">
                                                            <svg class="w-10 h-10" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    </div>

                                                    <!-- Thumbnail Selection Instructions -->
                                                    <div class="flex-1">
                                                        <p class="text-sm text-gray-600 mb-2">
                                                            <span x-show="thumbnailIndex === null"
                                                                class="font-medium text-red-500">Belum ada thumbnail
                                                                dipilih!</span>
                                                            <span x-show="thumbnailIndex !== null"
                                                                class="font-medium text-green-600">Thumbnail sudah
                                                                dipilih.</span>
                                                            Klik salah satu foto di bawah untuk memilih sebagai
                                                            thumbnail.
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            Pastikan memilih foto terbaik sebagai thumbnail karena ini
                                                            akan menjadi gambar utama kamar Anda.
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
                                            <div x-show="images.length > 0" class="mt-4">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Foto Terupload
                                                </h4>
                                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100">
                                                    <template x-for="(image, index) in images" :key="index">
                                                        <div class="relative group" @click="setThumbnail(index)">
                                                            <!-- Image Container -->
                                                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                                                :class="thumbnailIndex === index ?
                                                                    'border-blue-600 ring-2 ring-blue-400' :
                                                                    'border-gray-200 hover:border-blue-400'">
                                                                <img :src="image.url"
                                                                    :alt="`Preview ${index + 1}`"
                                                                    class="w-full h-full object-cover">

                                                                <!-- Thumbnail badge -->
                                                                <div x-show="thumbnailIndex === index"
                                                                    class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                                    Thumbnail
                                                                </div>
                                                            </div>

                                                            <!-- Remove Button -->
                                                            <button @click.stop="removeImage(index, $event)"
                                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                <svg class="w-3 h-3" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>

                                                            <!-- Image Number Badge -->
                                                            <div
                                                                class="absolute bottom-1 left-1 bg-gray-800 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                                <span x-text="index + 1"></span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
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

                                            <!-- Validation Messages -->
                                            <div class="mt-3 space-y-2">
                                                <p class="text-sm text-red-600" x-show="images.length < 3">
                                                    <span class="font-medium">Perhatian:</span>
                                                    Anda harus mengupload tepat 3 foto untuk melanjutkan.
                                                </p>

                                                <p class="text-sm text-red-600"
                                                    x-show="images.length >= 3 && thumbnailIndex === null">
                                                    <span class="font-medium">Perhatian:</span>
                                                    Anda harus memilih thumbnail untuk melanjutkan.
                                                </p>

                                                <p class="text-sm text-green-600"
                                                    x-show="images.length === 3 && thumbnailIndex !== null">
                                                    <span class="font-medium">Sempurna!</span>
                                                    Semua foto telah diupload dan thumbnail telah dipilih.
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

                    <!-- Filter Properti -->
                    <div>
                        <select id="room-filter"
                            class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="" hidden>Pilih Properti</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->idrec }}"
                                    {{ request('property_id') == $property->idrec ? 'selected' : '' }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Kamar -->
                    <div class="flex-1">
                        <input type="text" id="search-input" placeholder="Cari kamar..."
                            value="{{ request('search') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Filter Status -->
                    <div>
                        <select id="status-filter"
                            class="w-full md:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

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
                maxImages: 5,
                minImages: 3,
                priceTypes: [],
                dailyPrice: 0,
                monthlyPrice: 0,
                dailyPriceError: '',
                monthlyPriceError: '',
                isCheckingRoomNo: false,
                roomNoError: '',
                thumbnailIndex: null,
                facilities: [],


                init() {
                    // Initialize price input formatting
                    if (this.$refs.dailyPriceInput) {
                        new Cleave(this.$refs.dailyPriceInput, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand',
                            onValueChanged: (e) => {
                                this.validatePriceInput(this.$refs.dailyPriceInput,
                                    'dailyPrice');
                            }
                        });
                    }

                    if (this.$refs.monthlyPriceInput) {
                        new Cleave(this.$refs.monthlyPriceInput, {
                            numeral: true,
                            numeralThousandsGroupStyle: 'thousand',
                            onValueChanged: (e) => {
                                this.validatePriceInput(this.$refs.monthlyPriceInput,
                                    'monthlyPrice');
                            }
                        });
                    }
                },

                validatePriceInput(inputRef, priceType) {
                    // Get the raw value without formatting
                    let rawValue = inputRef.value.replace(/[^\d.]/g, '');

                    // Ensure only one decimal point
                    const decimalParts = rawValue.split('.');
                    if (decimalParts.length > 2) {
                        rawValue = decimalParts[0] + '.' + decimalParts.slice(1).join('');
                    }

                    // Limit to 4 decimal places
                    if (decimalParts.length === 2 && decimalParts[1].length > 4) {
                        rawValue = decimalParts[0] + '.' + decimalParts[1].substring(0, 4);
                        this[priceType + 'Error'] = 'Maksimal 4 digit di belakang koma';
                    } else {
                        this[priceType + 'Error'] = '';
                    }

                    // Limit total digits (14 before decimal + 4 after = 18)
                    const withoutDecimal = rawValue.replace('.', '');
                    if (withoutDecimal.length > 18) {
                        rawValue = rawValue.substring(0, rawValue.length - (withoutDecimal.length -
                            18));
                        this[priceType + 'Error'] =
                            'Maksimal 18 digit total (14 sebelum koma, 4 setelah)';
                    }

                    // Convert to number and check maximum value
                    const numericValue = parseFloat(rawValue) || 0;
                    if (numericValue > 999999999999) {
                        this[priceType + 'Error'] = 'Nilai maksimum adalah 999,999,999,999.9999';
                    }

                    // Update the model value (without formatting)
                    this[priceType] = numericValue;

                    // Update the input value with proper formatting
                    if (inputRef) {
                        // Format with Cleave (it will handle the thousands separators)
                        const cleave = inputRef._cleave;
                        if (cleave) {
                            cleave.setRawValue(rawValue);
                        }
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
                            Swal.fire({
                                toast: true,
                                icon: 'error',
                                title: `File ${file.name} terlalu besar. Maksimal 5MB.`,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                            });
                        }
                    });

                    // Clear the file input to allow re-selection
                    if (event.target) {
                        event.target.value = '';
                    }
                },

                setThumbnail(index) {
                    this.thumbnailIndex = index;
                },

                removeImage(index, event) {
                    if (event) event.preventDefault();

                    // Adjust thumbnail index if we're removing the current thumbnail
                    if (this.thumbnailIndex === index) {
                        this.thumbnailIndex = null;
                    } else if (this.thumbnailIndex > index) {
                        this.thumbnailIndex--;
                    }

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

                        if (isValid) {
                            const propertyId = document.getElementById('property_id').value;
                            const roomNo = document.getElementById('room_no').value;

                            // Tampilkan loading
                            this.isCheckingRoomNo = true;
                            this.roomNoError = '';

                            // Lakukan AJAX request untuk mengecek nomor kamar
                            return fetch('/properties/rooms/check-room-number', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').content
                                    },
                                    body: JSON.stringify({
                                        property_id: propertyId,
                                        room_no: roomNo
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    this.isCheckingRoomNo = false;

                                    if (data.exists) {
                                        document.getElementById('room_no').classList.add(
                                            'border-red-500');
                                        this.roomNoError = data.message;

                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Nomor Kamar Sudah Ada',
                                            text: data.message,
                                            confirmButtonText: 'OK'
                                        });

                                        return false;
                                    }

                                    return true;
                                })
                                .catch(error => {
                                    this.isCheckingRoomNo = false;
                                    console.error('Error:', error);
                                    return true; // Biarkan lanjut meskipun error
                                });
                        }

                    } else if (step === 2) {
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

                        // Validate the selected price types have valid values
                        if (this.priceTypes.includes('daily')) {
                            if (!this.dailyPrice || this.dailyPrice <= 0) {
                                this.dailyPriceError = 'Masukkan harga harian yang valid';
                                isValid = false;
                            } else if (this.dailyPriceError) {
                                isValid = false;
                            }
                        }

                        if (this.priceTypes.includes('monthly')) {
                            if (!this.monthlyPrice || this.monthlyPrice <= 0) {
                                this.monthlyPriceError = 'Masukkan harga bulanan yang valid';
                                isValid = false;
                            } else if (this.monthlyPriceError) {
                                isValid = false;
                            }
                        }

                        // Show error messages if any
                        if (!isValid) {
                            if (this.priceTypes.includes('daily') && this.dailyPriceError) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: this.dailyPriceError,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }

                            if (this.priceTypes.includes('monthly') && this.monthlyPriceError) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: this.monthlyPriceError,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        }
                    } else if (step === 3) {
                        // No validation needed for step 3 (facilities) as they're optional
                    } else if (step === 4) {
                        if (step === 4) {
                            if (this.images.length < this.minImages) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: `Minimal ${this.minImages} foto kamar harus diupload!`,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                return false;
                            }

                            if (this.thumbnailIndex === null) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Anda harus memilih thumbnail untuk kamar ini!',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                return false;
                            }

                            return true;
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
                    formData.append('thumbnail_index', this.thumbnailIndex);

                    // Add each selected image
                    this.images.forEach((image, index) => {
                        formData.append('room_images[]', image.file);
                    });

                    // Add image count for backend validation
                    formData.append('image_count', this.images.length);

                    // Add price types
                    const priceObject = {
                        daily: this.priceTypes.includes('daily'),
                        monthly: this.priceTypes.includes('monthly')
                    };
                    formData.append('price_types', JSON.stringify(priceObject));


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

        document.getElementById('room_bed').addEventListener('change', function() {
            const bed = this.value;
            const capacityField = document.getElementById('room_capacity');

            if (bed === "Single") {
                capacityField.value = 1;
                capacityField.readOnly = true;
            } else if (bed === "Twin" || bed === "Double" || bed === "Queen" || bed === "King") {
                capacityField.value = 2;
                capacityField.readOnly = true;
            } else {
                capacityField.value = "";
                capacityField.readOnly = false;
            }
        });

        function toggleStatus(checkbox) {
            const propertyId = checkbox.getAttribute('data-id');
            const newStatus = checkbox.checked ? 1 : 0;

            const statusLabel = checkbox.closest('label').querySelector('span');

            fetch(`/properties/rooms/${propertyId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error("Gagal update status");
                    return res.json();
                })
                .then(() => {
                    // Animasi perubahan label status
                    statusLabel.classList.add('opacity-0');

                    setTimeout(() => {
                        statusLabel.textContent = newStatus === 1 ? 'Active' : 'Inactive';
                        statusLabel.classList.remove('opacity-0');
                        statusLabel.classList.add('opacity-100');
                    }, 200);

                    // Notifikasi Toastify lebih menarik
                    Toastify({
                        text: newStatus === 1 ?
                            "✓ Kamar berhasil diaktifkan" : "⚠ Kamar berhasil dinonaktifkan",
                        duration: 3500,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: "shadow-lg rounded-md",
                        style: {
                            background: newStatus === 1 ?
                                "linear-gradient(to right, #4CAF50, #2E7D32)" :
                                "linear-gradient(to right, #F44336, #C62828)"
                        }
                    }).showToast();
                })

                .catch(err => {
                    console.error(err);
                    checkbox.checked = !checkbox.checked;

                    alert("Gagal memperbarui status properti");
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

            // Timer untuk debounce
            let searchTimer;

            // Fungsi untuk memproses filter
            function applyFilters() {
                const propertyId = roomFilter.value;
                const status = statusFilter.value;
                const searchQuery = searchInput.value.trim();

                // Kirim permintaan AJAX
                fetchRooms(propertyId, status, searchQuery);
            }

            // Event listeners untuk filter real-time
            roomFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);

            // Debounce untuk search input (menunggu 500ms setelah user berhenti mengetik)
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(applyFilters, 500);
            });

            // Juga bisa trigger filter saat enter di search input
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimer);
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

                // Update URL tanpa reload halaman
                window.history.replaceState({}, '', `${window.location.pathname}?${params}`);

                // Tampilkan loading indicator
                document.getElementById('roomTableContainer').innerHTML =
                    '<div class="p-4 text-center"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-2 text-gray-600">Memuat data...</p></div>';

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
                        // Update tabel dan pagination
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
                                '<div class="p-4 text-center text-red-500">Error loading data. Please try again.</div>';
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
