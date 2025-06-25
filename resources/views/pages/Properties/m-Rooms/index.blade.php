<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        @if (session()->has('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded shadow-md mb-2">
                {{ session('success') }}
            </div>
        @elseif (session()->has('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded shadow-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Kamar</h1>
            <div class="mt-4 md:mt-0">
                <!-- AlpineJS Wrapper -->
                <div x-data="{
                                modalOpen: false,
                                step: 1,
                            
                                validateStep(step) {
                                    if (step === 1) {
                                        const requiredInputs = Array.from(document.querySelectorAll('#roomForm [required]'));
                                        let isValid = true;
                            
                                        requiredInputs.forEach(input => {
                                            if (!input.value) {
                                                input.classList.add('border-red-500');
                                                isValid = false;
                                            } else {
                                                input.classList.remove('border-red-500');
                                            }
                                        });
                            
                                        return isValid;
                                    }
                                    return true;
                                },
                            
                                submitForm() {
                                    if (this.validateStep(this.step)) {
                                        document.getElementById('roomForm').submit();
                                    }
                                }
                            }">
                    <!-- Add Room Button -->
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center"
                        type="button" @click.prevent="modalOpen = true;" aria-controls="room-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Kamar
                    </button>

                    <!-- Modal backdrop -->
                    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity" x-show="modalOpen"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-out duration-200"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" aria-hidden="true"
                        x-cloak></div>

                    <!-- Modal dialog -->
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
                                                :class="step >= 1 ? 'text-blue-600' : 'text-gray-500'">Informasi Kamar
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
                                                :class="step >= 2 ? 'text-blue-600' : 'text-gray-500'">Fasilitas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal content -->
                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                <form id="roomForm" method="POST" action="{{ route('rooms.store') }}"
                                    enctype="multipart/form-data" @submit.prevent="submitForm">
                                    @csrf

                                    <!-- Step 1 - Room Information -->
                                    <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-x-4"
                                        x-transition:enter-end="opacity-100 translate-x-0">
                                        <div class="space-y-6">
                                            <!-- Property Selector -->
                                            <div>
                                                @livewire('property-room-selector', ['userProperty' => Auth::user()->property_id])
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
                                                        Ukuran Kamar (m³) <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="number" id="room_size" name="room_size" required
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Masukkan ukuran kamar">
                                                </div>

                                                <div>
                                                    <label for="room_bed"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                        Jenis Kasur
                                                    </label>
                                                    <select id="room_bed" name="room_bed"
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                                        @foreach (['Single', 'Double', 'King', 'Queen', 'Twin'] as $bedType)
                                                            <option value="{{ $bedType }}">{{ $bedType }}
                                                            </option>
                                                        @endforeach
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

                                            <div x-data="{
                                                mode: null,
                                                dailyOriginal: 0,
                                                monthlyOriginal: 0,
                                                cleaveInstance: null,
                                            
                                                get priceLabel() {
                                                    if (this.mode === 'daily') return 'Harga Original Harian';
                                                    if (this.mode === 'monthly') return 'Harga Original Bulanan';
                                                    return '';
                                                },
                                            
                                                get priceNotes() {
                                                    if (this.mode === 'daily') return `Harga harian akan berlaku selama setahun terhitung dari tanggal pembuatan kamar.<br>Untuk harga spesial, silahkan gunakan fitur ubah harga setelah kamar disimpan.`;
                                                    return '';
                                                },
                                            
                                                get currentPrice() {
                                                    return this.mode === 'daily' ? this.dailyOriginal : this.monthlyOriginal;
                                                },
                                            
                                                set currentPrice(value) {
                                                    if (this.mode === 'daily') {
                                                        this.dailyOriginal = value;
                                                    } else if (this.mode === 'monthly') {
                                                        this.monthlyOriginal = value;
                                                    }
                                                },
                                            
                                                init() {
                                                    this.$watch('mode', () => {
                                                        this.$nextTick(() => {
                                                            if (this.cleaveInstance) {
                                                                this.cleaveInstance.destroy();
                                                            }
                                            
                                                            const input = this.$refs.priceInput;
                                                            input.value = ''; // reset visible input
                                            
                                                            this.cleaveInstance = new Cleave(input, {
                                                                numeral: true,
                                                                numeralDecimalMark: ',',
                                                                delimiter: '.',
                                                                numeralThousandsGroupStyle: 'thousand',
                                                                onValueChanged: (e) => {
                                                                    this.currentPrice = parseFloat(e.target.rawValue || 0);
                                                                }
                                                            });
                                                        });
                                                    });
                                                }
                                            }" x-init="init()">
                                                <div class="mb-4">
                                                    <h3 class="text-md font-semibold text-gray-700 mb-2">Jenis Kamar
                                                    </h3>
                                                    <div class="flex space-x-6">
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" value="daily" x-model="mode"
                                                                class="form-radio text-blue-600">
                                                            <span class="ml-2">Harian</span>
                                                        </label>
                                                        <label class="inline-flex items-center">
                                                            <input type="radio" value="monthly" x-model="mode"
                                                                class="form-radio text-blue-600">
                                                            <span class="ml-2">Bulanan</span>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div x-show="mode" x-transition>
                                                    <label class="block text-sm font-semibold text-gray-700 mb-2"
                                                        x-text="priceLabel"></label>
                                                    <input type="hidden" name="mode" x-model="mode">
                                                    <input type="hidden"
                                                        :name="mode === 'daily' ? 'daily_price' : 'monthly_price'"
                                                        :value="currentPrice">
                                                    <input type="text" x-ref="priceInput"
                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                        placeholder="Masukkan harga" />
                                                    <p class="text-sm text-red-600 italic mt-2" x-html="priceNotes">
                                                    </p>
                                                </div>
                                            </div>

                                            <div>
                                                <label for="description_id"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Deskripsi Kamar (Indonesia) <span class="text-red-500">*</span>
                                                </label>
                                                <textarea id="description_id" name="description_id" rows="4" required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                    placeholder="Deskripsikan kamar Anda..."></textarea>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Foto Kamar <span class="text-red-500">*</span>
                                                    <span class="text-sm font-normal text-gray-500">(Foto ini akan
                                                        dijadikan thumbnail kamar)</span>
                                                </label>
                                                <input type="file" id="photo" name="photo" accept="image/*"
                                                    required
                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                                <p class="text-sm text-gray-600 italic mt-2">
                                                    Untuk menambahkan gambar lebih bisa setelah kamar sudah disimpan.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2 - Facilities -->
                                    <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
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
                                                    <div class="relative">
                                                        <input id="facility-wifi" name="wifi" type="checkbox"
                                                            value="1" class="sr-only peer">
                                                        <label for="facility-wifi"
                                                            class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                            <span>WiFi</span>
                                                        </label>
                                                    </div>
                                                    <div class="relative">
                                                        <input id="facility-tv" name="tv" type="checkbox"
                                                            value="1" class="sr-only peer">
                                                        <label for="facility-tv"
                                                            class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                            <span>TV</span>
                                                        </label>
                                                    </div>
                                                    <div class="relative">
                                                        <input id="facility-ac" name="ac" type="checkbox"
                                                            value="1" class="sr-only peer">
                                                        <label for="facility-ac"
                                                            class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                            <span>AC</span>
                                                        </label>
                                                    </div>
                                                    <div class="relative">
                                                        <input id="facility-bathroom" name="bathroom" type="checkbox"
                                                            value="1" class="sr-only peer">
                                                        <label for="facility-bathroom"
                                                            class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                            <span>Bathroom</span>
                                                        </label>
                                                    </div>
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
                                            <button type="button" x-show="step < 2"
                                                @click="validateStep(step) && step++"
                                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                Selanjutnya
                                                <svg class="w-4 h-4 inline ml-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                            <button type="submit" x-show="step === 2"
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

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div>
                    @if (auth::user()->property_id == 0)
                        <select id="room-filter"
                            class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="" hidden>Pilih Properti</option>
                            @foreach ($rooms as $property)
                                <option value="{{ $property->idrec }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" id="room-filter"
                            class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            value="{{ $properties->idrec }}" readonly>
                        <input type="text" id="room-filter"
                            class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-center"
                            value="{{ $properties->name }}" readonly>
                    @endif
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

        <!-- Room Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Properti</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Kamar</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tgl Penambahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tgl Perubahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ditambahkan Oleh</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Room Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="rooms-table-body">
                        @foreach ($rooms as $room)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $room->property_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $room->name }}</div>
                                    <div class="text-sm text-gray-500">Room {{ $room->no }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($room->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $room->updated_at ? \Carbon\Carbon::parse($room->updated_at)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $room->creator->username }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @livewire('room-status-toggle', ['roomId' => $room->idrec, 'status' => $room->status, 'roomNo' => $room->no])
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @livewire('room-type-toggle', ['roomId' => $room->idrec, 'roomType' => $room->periode])
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <!-- AlpineJS Wrapper -->
                                        <div x-data="{ open: false, step: 1, isValid: true }">

                                            <!-- Add Room Button -->
                                            <button @click="open = true" class="text-blue-600 hover:text-blue-900"
                                                title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </button>

                                            <!-- Modal -->
                                            <div x-show="open" x-cloak
                                                class="fixed inset-0 backdrop-blur bg-opacity-30 flex items-center justify-center z-50 transition-opacity">
                                                <div
                                                    class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative overflow-hidden">
                                                    <!-- Modal Header -->
                                                    <div class="flex justify-between items-center px-6 py-4 border-b">
                                                        <div>
                                                            <h2 x-show="step === 1"
                                                                class="text-xl font-semibold text-slate-800">Edit
                                                                Informasi Kamar</h2>
                                                            <h2 x-show="step === 2"
                                                                class="text-xl font-semibold text-slate-800">Edit
                                                                Fasilitas</h2>
                                                        </div>
                                                        <button @click="open = false"
                                                            class="text-gray-600 hover:text-black text-2xl">&times;</button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="p-6 max-h-[80vh] overflow-y-auto text-left">
                                                        <!-- Form -->
                                                        <form x-ref="roomForm"
                                                            @submit.prevent="
                                                                if (step === 2) {
                                                                    const requireds = $refs.step2Form.querySelectorAll('[required]');
                                                                    let step2Valid = true;

                                                                    requireds.forEach(input => {
                                                                        if (!input.value) {
                                                                            input.classList.add('border-red-500');
                                                                            step2Valid = false;
                                                                        } else {
                                                                            input.classList.remove('border-red-500');
                                                                        }
                                                                    });

                                                                    if (step2Valid) {
                                                                        $refs.roomForm.submit(); // Submit to rooms.store
                                                                    } else {
                                                                        isValidStep2 = false;
                                                                    }
                                                                }
                                                            "
                                                            x-data="{ open: false, step: 1, isValid: true, isValidStep2: true }" method="POST"
                                                            action="{{ route('rooms.update', ['idrec' => $room->idrec]) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf

                                                            <!-- Step 1 -->
                                                            <div x-show="step === 1" x-transition x-ref="step1Form">
                                                                <div class="grid grid-cols-2 gap-6 mb-4">
                                                                    <div class="space-y-4">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-medium">Nomor
                                                                                Kamar</label>
                                                                            <input type="text" name="edit_room_no"
                                                                                required
                                                                                class="w-full border rounded p-2 bg-gray-50"
                                                                                value="{{ e($room->no) }}" readonly>
                                                                        </div>
                                                                    </div>

                                                                    <div class="space-y-4">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-medium">Nama
                                                                                Kamar</label>
                                                                            <input type="text"
                                                                                name="edit_room_name" required
                                                                                class="w-full border rounded p-2"
                                                                                value="{{ e($room->name) }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="grid grid-cols-3 gap-6 mb-4">
                                                                    <div class="space-y-4">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-medium">Ukuran
                                                                                Kamar (m³)</label>
                                                                            <input type="number"
                                                                                name="edit_room_size" required
                                                                                class="w-full border rounded p-2"
                                                                                value="{{ e($room->size) }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="space-y-4">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-medium">Jenis
                                                                                Kasur</label>
                                                                            <select name="edit_room_bed"
                                                                                class="w-full border rounded p-2">
                                                                                @foreach (['Single', 'Double', 'King', 'Queen', 'Twin'] as $bedType)
                                                                                    <option
                                                                                        value="{{ $bedType }}"
                                                                                        {{ $room->bed_type == $bedType ? 'selected' : '' }}>
                                                                                        {{ $bedType }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <div class="space-y-4">
                                                                        <div>
                                                                            <label
                                                                                class="block text-sm font-medium">Kapasitas
                                                                                (Pax)</label>
                                                                            <input type="number"
                                                                                name="edit_room_capacity" required
                                                                                class="w-full border rounded p-2"
                                                                                value="{{ e($room->capacity) }}">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div x-data="{
                                                                    mode: '{{ e($room->periode) }}',
                                                                    dailyOriginal: {{ e($room->price_original_daily) }},
                                                                    monthlyOriginal: {{ e($room->price_original_monthly) }},
                                                                    cleaveInstance: null,
                                                                
                                                                    get priceLabel() {
                                                                        if (this.mode === 'daily') return 'Harga Original Harian';
                                                                        if (this.mode === 'monthly') return 'Harga Original Bulanan';
                                                                        return '';
                                                                    },
                                                                
                                                                    get priceNotes() {
                                                                        if (this.mode === 'daily') return `Harga harian akan berlaku selama setahun terhitung dari tanggal pembuatan kamar.<br>Untuk harga spesial, silahkan gunakan fitur ubah harga setelah kamar disimpan.`;
                                                                        return '';
                                                                    },
                                                                
                                                                    get currentPrice() {
                                                                        return this.mode === 'daily' ? this.dailyOriginal : this.monthlyOriginal;
                                                                    },
                                                                
                                                                    set currentPrice(value) {
                                                                        if (this.mode === 'daily') {
                                                                            this.dailyOriginal = value;
                                                                        } else if (this.mode === 'monthly') {
                                                                            this.monthlyOriginal = value;
                                                                        }
                                                                    },
                                                                
                                                                    formatInitialValue() {
                                                                        const input = this.$refs.priceInput;
                                                                
                                                                        // Destroy existing Cleave instance if any
                                                                        if (this.cleaveInstance) {
                                                                            this.cleaveInstance.destroy();
                                                                        }
                                                                
                                                                        // Create a new Cleave instance
                                                                        this.cleaveInstance = new Cleave(input, {
                                                                            numeral: true,
                                                                            numeralDecimalMark: ',',
                                                                            delimiter: '.',
                                                                            numeralThousandsGroupStyle: 'thousand',
                                                                            onValueChanged: (e) => {
                                                                                this.currentPrice = parseFloat(e.target.rawValue || 0);
                                                                            }
                                                                        });
                                                                
                                                                        // 💡 Set correct price for the current mode
                                                                        const price = this.mode === 'daily' ? this.dailyOriginal : this.monthlyOriginal;
                                                                        this.cleaveInstance.setRawValue(price);
                                                                    },
                                                                
                                                                    init() {
                                                                        this.$nextTick(() => {
                                                                            this.formatInitialValue();
                                                                        });
                                                                
                                                                        this.$watch('mode', () => {
                                                                            this.$nextTick(() => {
                                                                                this.formatInitialValue();
                                                                            });
                                                                        });
                                                                    }
                                                                }" x-init="init()"
                                                                    class="space-y-4">
                                                                    <div class="col-span-2 mb-2">
                                                                        <h3
                                                                            class="text-md font-medium border-gray-300 pb-1">
                                                                            Jenis Kamar</h3>
                                                                    </div>

                                                                    <!-- Mode selector -->
                                                                    <div class="flex space-x-6">
                                                                        <label class="inline-flex items-center">
                                                                            <input type="radio" value="daily"
                                                                                x-model="mode"
                                                                                class="form-radio text-blue-600">
                                                                            <span class="ml-2">Harian</span>
                                                                        </label>
                                                                        <label class="inline-flex items-center">
                                                                            <input type="radio" value="monthly"
                                                                                x-model="mode"
                                                                                class="form-radio text-blue-600">
                                                                            <span class="ml-2">Bulanan</span>
                                                                        </label>
                                                                    </div>

                                                                    <!-- Price input shown only if mode is selected -->
                                                                    <div x-show="mode" x-transition>
                                                                        <label class="block text-sm font-medium"
                                                                            x-text="priceLabel"></label>

                                                                        <!-- Hidden field to submit the actual raw price -->
                                                                        <input type="hidden"
                                                                            :name="mode === 'daily' ? 'daily_price' :
                                                                                'monthly_price'"
                                                                            :value="currentPrice">

                                                                        <!-- Visible input formatted with Cleave.js -->
                                                                        <input type="text" x-ref="priceInput"
                                                                            class="w-full border rounded p-2 mt-1"
                                                                            placeholder="Masukkan harga" required />

                                                                        <p class="text-sm text-red-600 italic mb-4"
                                                                            x-html="priceNotes"></p>
                                                                    </div>
                                                                </div>

                                                                <div class="flex gap-4 mb-4">
                                                                    <div class="w-full">
                                                                        <label
                                                                            class="block text-sm font-medium">Deskripsi
                                                                            Kamar Indonesia</label>
                                                                        <textarea name="description_id" required class="w-full border rounded p-2" value="{{ e($room->descriptions) }}">{{ e($room->descriptions) }}</textarea>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium">Foto
                                                                        Kamar</label>
                                                                    <input type="file" name="photo"
                                                                        accept="image/*"
                                                                        class="w-full border rounded p-2">
                                                                </div>

                                                                <p class="text-sm text-gray-600 italic mb-4">
                                                                    Gambar ini akan dijadikan thumbnail kamar. Untuk
                                                                    menambahkan gambar lebih bisa setelah kamar sudah
                                                                    disimpan.
                                                                </p>

                                                                <!-- Validation message -->
                                                                <div x-show="!isValid"
                                                                    class="text-red-600 text-sm mb-2">Semua kolom wajib
                                                                    diisi sebelum lanjut.</div>

                                                                <div class="flex justify-end gap-2 mt-4">
                                                                    {{-- <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tutup</button> --}}
                                                                    <button type="button"
                                                                        @click="
                                                                            const inputs = $refs.step1Form.querySelectorAll('[required]');
                                                                            isValid = true;
                                                                            inputs.forEach(input => {
                                                                                if (!input.value) {
                                                                                    input.classList.add('border-red-500');
                                                                                    isValid = false;
                                                                                } else {
                                                                                    input.classList.remove('border-red-500');
                                                                                }
                                                                            });
                                                                            if (isValid) step = 2;
                                                                        "
                                                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                        Selanjutnya
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <!-- Step 2 -->
                                                            <div x-show="step === 2" x-transition x-ref="step2Form">
                                                                <div class="col-span-2 mb-2">
                                                                    <h3
                                                                        class="text-lg font-medium border-gray-300 pb-1">
                                                                        Fasilitas</h3>
                                                                </div>

                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="wifi"
                                                                            class="form-checkbox text-blue-600"
                                                                            value="1"
                                                                            {{ ($room->facility['wifi'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">WiFi</span>
                                                                    </label>

                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="tv"
                                                                            class="form-checkbox text-blue-600"
                                                                            value="1"
                                                                            {{ ($room->facility['tv'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">TV</span>
                                                                    </label>

                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="ac"
                                                                            class="form-checkbox text-blue-600"
                                                                            value="1"
                                                                            {{ ($room->facility['ac'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">AC</span>
                                                                    </label>

                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="bathroom"
                                                                            class="form-checkbox text-blue-600"
                                                                            value="1"
                                                                            {{ ($room->facility['bathroom'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">Bathroom</span>
                                                                    </label>
                                                                </div>

                                                                <div class="flex justify-end gap-2 mt-4">
                                                                    <button type="button" @click="step = 1"
                                                                        class="bg-gray-500  hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-white px-4 py-2 rounded">Kembali</button>
                                                                    <button type="submit"
                                                                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <style>
                                                [x-cloak] {
                                                    display: none !important;
                                                }
                                            </style>
                                        </div>

                                        <!-- Edit Price Button -->
                                        <div x-data="{ priceModalOpen: false }">
                                            <!-- Trigger Button -->
                                            <a href="#" class="text-green-600 hover:text-green-900"
                                                title="Edit Harga" @click.prevent="priceModalOpen = true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zm1 4a1 1 0 000 2h10a1 1 0 100-2H5zm-1 4a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zm1 3a1 1 0 000 2h6a1 1 0 100-2H5z" />
                                                </svg>
                                            </a>

                                            <!-- Modal -->
                                            <div x-show="priceModalOpen" x-cloak x-transition
                                                class="fixed inset-0 flex items-center justify-center backdrop-blur bg-opacity-30 z-50 text-left">
                                                <div x-data="{
                                                    startDate: '',
                                                    setPrice: '',
                                                    cleaveInstance: null,
                                                    priceMap: {},
                                                    basePrice: {{ $room->price_original_daily }},
                                                
                                                    get formattedDatePrice() {
                                                        const price = this.priceMap[this.startDate];
                                                        if (price === undefined || price === null || isNaN(price)) return '-';
                                                        return new Intl.NumberFormat('id-ID', {
                                                            minimumFractionDigits: 0,
                                                            maximumFractionDigits: 2
                                                        }).format(price);
                                                    },
                                                
                                                    get formattedBasePrice() {
                                                        const price = this.basePrice;
                                                        if (price === undefined || price === null || isNaN(price)) return '-';
                                                        return new Intl.NumberFormat('id-ID', {
                                                            minimumFractionDigits: 0,
                                                            maximumFractionDigits: 2
                                                        }).format(price);
                                                    },
                                                
                                                    async fetchMonthPrices(year, month, fpInstance) {
                                                        try {
                                                            const res = await fetch(`/properties/rooms/{{ $room->idrec }}/prices?year=${year}&month=${month}`);
                                                            const data = await res.json();
                                                            this.priceMap = data;
                                                            {{-- console.log('Fetched priceMap:', data); --}}
                                                            fpInstance.redraw(); // Recolor calendar
                                                        } catch (e) {
                                                            console.error('Failed to fetch prices', e);
                                                        }
                                                    },
                                                
                                                    async updatePrice() {
                                                        try {
                                                            const res = await fetch(`/properties/rooms/{{ $room->idrec }}/update-price`, {
                                                                method: 'POST',
                                                                headers: {
                                                                    'Content-Type': 'application/json',
                                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                                },
                                                                body: JSON.stringify({
                                                                    start_date: this.startDate,
                                                                    end_date: this.startDate,
                                                                    price: this.setPrice
                                                                })
                                                            });
                                                
                                                            const data = await res.json();
                                                            {{-- console.log(data); --}}
                                                
                                                            if (res.ok) {
                                                                alert(data.message || 'Harga berhasil diperbarui!');
                                                                const date = this.startDate;
                                                                {{-- console.log(date); --}}
                                                                if (!date) {
                                                                    alert('Tanggal belum dipilih.');
                                                                    return;
                                                                }
                                                                const [year, month] = date.split('-');
                                                                {{-- console.log(year, month); --}}
                                                                await this.fetchMonthPrices(parseInt(year), parseInt(month), this.fpInstance);
                                                            } else {
                                                                alert(data.message || 'Gagal memperbarui harga.');
                                                            }
                                                        } catch (e) {
                                                            console.error('Update error:', e);
                                                            alert('Terjadi kesalahan saat memperbarui harga.');
                                                        }
                                                    },
                                                
                                                    init() {
                                                        const self = this;
                                                        const calendar = flatpickr(this.$el.querySelector('.inline-calendar'), {
                                                            inline: true,
                                                            mode: 'single',
                                                            dateFormat: 'Y-m-d',
                                                
                                                            async onReady(selectedDates, dateStr, instance) {
                                                                self.fpInstance = instance; // ✅ store reference
                                                                await self.fetchMonthPrices(instance.currentYear, instance.currentMonth + 1, instance);
                                                            },
                                                
                                                            async onMonthChange(selectedDates, dateStr, instance) {
                                                                await self.fetchMonthPrices(instance.currentYear, instance.currentMonth + 1, instance);
                                                            },
                                                
                                                            async onChange(dates) {
                                                                if (dates.length > 0) {
                                                                    const localDate = dates[0];
                                                                    const year = localDate.getFullYear();
                                                                    const month = String(localDate.getMonth() + 1).padStart(2, '0');
                                                                    const day = String(localDate.getDate()).padStart(2, '0');
                                                                    self.startDate = `${year}-${month}-${day}`;
                                                                }
                                                            },
                                                
                                                            onDayCreate(dObj, dStr, fp, dayElem) {
                                                                // Skip days from previous or next month
                                                                if (dayElem.classList.contains('prevMonthDay') || dayElem.classList.contains('nextMonthDay')) {
                                                                    return;
                                                                }
                                                
                                                                const localDate = dayElem.dateObj;
                                                                const year = localDate.getFullYear();
                                                                const month = String(localDate.getMonth() + 1).padStart(2, '0');
                                                                const day = String(localDate.getDate()).padStart(2, '0');
                                                                const date = `${year}-${month}-${day}`;
                                                                const price = self.priceMap[date];
                                                
                                                                {{-- dayElem.style.backgroundColor = price === undefined || price === null
                                                                    ? '#d1d5db' // gray-300
                                                                    : parseInt(price) === parseInt(self.basePrice)
                                                                        ? '#3b82f6' // blue-500
                                                                        : '#ef4444'; // red-500 --}}
                                                
                                                                dayElem.style.backgroundColor =
                                                                    (price === undefined || price === null || price == 0) ?
                                                                    '#d1d5db' // gray-300 for empty
                                                                    :
                                                                    (parseInt(price) === parseInt(self.basePrice) ?
                                                                        '#3b82f6' // blue-500 for base price
                                                                        :
                                                                        (parseInt(price) > parseInt(self.basePrice) ?
                                                                            '#ef4444' // red-500 for higher price
                                                                            :
                                                                            '#4CAF50')); // green-500 for lower price
                                                
                                                                dayElem.style.color = '#ffffff'; // white text
                                                            }
                                                        });
                                                
                                                        this.fpInstance = calendar; // ✅ fallback if needed
                                                
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
                                                }" x-init="init"
                                                    class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 relative space-y-6 overflow-y-auto max-h-[90vh]">

                                                    <!-- Header -->
                                                    <div class="flex justify-between items-center border-b pb-3">
                                                        <h2 class="text-lg font-semibold text-gray-800">Manajemen Harga
                                                            (Harian)</h2>
                                                        <button @click="priceModalOpen = false"
                                                            class="text-gray-500 hover:text-red-500 text-2xl font-bold leading-none">&times;</button>
                                                    </div>

                                                    <!-- Content Grid -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                                                        <!-- LEFT: Calendar -->
                                                        <div class="flex flex-col h-full justify-between space-y-4">
                                                            <div class="p-2 inline-block" style="position: relative;">
                                                                <style>
                                                                    .inline-calendar input {
                                                                        display: none;
                                                                    }
                                                                </style>
                                                                <div class="inline-calendar"></div>
                                                            </div>

                                                            <!-- Legend -->
                                                            <div class="grid grid-cols-2 gap-2 text-sm text-left">
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-4 h-4 rounded"
                                                                        style="background-color: #d1d5db;"></div>
                                                                    <span>Belum ada harga</span>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-4 h-4 rounded"
                                                                        style="background-color: #3b82f6;"></div>
                                                                    <span>Harga standar</span>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-4 h-4 rounded"
                                                                        style="background-color: #ef4444;"></div>
                                                                    <span>Harga lebih tinggi</span>
                                                                </div>
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-4 h-4 rounded"
                                                                        style="background-color: #4CAF50;"></div>
                                                                    <span>Harga lebih rendah</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- RIGHT: Form -->
                                                        <div class="flex flex-col h-full justify-between space-y-4">
                                                            <div class="w-full space-y-1">
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                                                <input type="text" :value="startDate"
                                                                    class="border border-gray-300 px-4 py-2 rounded w-full"
                                                                    readonly>
                                                            </div>
                                                            <div class="w-full space-y-1">
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700">Harga
                                                                    pada tanggal</label>
                                                                <input type="text" :value="formattedDatePrice"
                                                                    class="border border-gray-300 px-4 py-2 rounded w-full"
                                                                    readonly>
                                                                <p
                                                                    class="text-xs text-gray-500 mt-1 w-full break-words whitespace-normal italic">
                                                                    Harga original: <span
                                                                        x-text="formattedBasePrice"></span><br>
                                                                    Jika harga menunjukkan '-', maka harga kosong.
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">Harga
                                                                    Baru</label>
                                                                <input type="text" x-ref="setPrice"
                                                                    class="border border-gray-300 px-4 py-2 rounded w-full"
                                                                    placeholder="Masukkan harga" />
                                                            </div>
                                                            <div class="flex justify-between pt-2">
                                                                <button @click="updatePrice"
                                                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Update</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <!-- Flatpickr & Alpine -->
                                        <link rel="stylesheet"
                                            href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                                        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                                        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

                                        <!-- Delete Button -->
                                        <form action="" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="{{ $rooms->previousPageUrl() }}"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Sebelumnya
                    </a>
                    <a href="{{ $rooms->nextPageUrl() }}"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Berikutnya
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan
                            <span class="font-medium">{{ $rooms->firstItem() }}</span>
                            sampai
                            <span class="font-medium">{{ $rooms->lastItem() }}</span>
                            dari
                            <span class="font-medium">{{ $rooms->total() }}</span>
                            hasil
                        </p>
                    </div>
                    <div>
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Reusable success toast notification
            function showSuccessToast(message) {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#4CAF50",
                        color: "#FFFFFF",
                        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
                        borderRadius: "0.375rem",
                        padding: "0.75rem 1rem",
                        fontSize: "0.875rem",
                        fontWeight: "500",
                        display: "flex",
                        alignItems: "center"
                    },
                    stopOnFocus: true
                }).showToast();
            }

            function modalRoom() {
                return {
                    modalOpen: false,
                    step: 1,

                    validateStep(step) {
                        if (step === 1) {
                            const requiredInputs = Array.from(document.querySelectorAll('#roomForm [required]'));
                            let isValid = true;

                            requiredInputs.forEach(input => {
                                if (!input.value) {
                                    input.classList.add('border-red-500');
                                    isValid = false;
                                } else {
                                    input.classList.remove('border-red-500');
                                }
                            });

                            return isValid;
                        }
                        return true;
                    },

                    submitForm() {
                        if (this.validateStep(this.step)) {
                            document.getElementById('roomForm').submit();
                        }
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Toggle status

                // Filter functionality
                const filterBtn = document.getElementById('filter-btn');
                if (filterBtn) {
                    filterBtn.addEventListener('click', function() {
                        const search = document.getElementById('search-input').value;
                        const status = document.getElementById('status-filter').value;
                        const property = document.getElementById('property-filter').value;

                        const url = new URL(window.location.href);
                        const params = new URLSearchParams();

                        if (search) params.append('search', search);
                        if (status) params.append('status', status);
                        if (property) params.append('property', property);

                        window.location.href = url.pathname + '?' + params.toString();
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
