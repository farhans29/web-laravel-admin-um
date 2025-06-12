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
                <div x-data="{ open: false, step: 1, isValid: true }">

                    <!-- Add Room Button -->
                    <button @click="open = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Kamar
                    </button>

                    <!-- Modal -->
                    <div x-show="open" x-cloak class="fixed inset-0 backdrop-blur bg-opacity-30 flex items-center justify-center z-50 transition-opacity">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative overflow-hidden">
                            <!-- Modal Header -->
                            <div class="flex justify-between items-center px-6 py-4 border-b">
                                <div>
                                    <h2 x-show="step === 1" class="text-xl font-semibold text-slate-800">Informasi Kamar</h2>
                                    <h2 x-show="step === 2" class="text-xl font-semibold text-slate-800">Fasilitas</h2>
                                </div>
                                <button @click="open = false" class="text-gray-600 hover:text-black text-2xl">&times;</button>
                            </div>

                            <!-- Modal Body -->
                            <div class="p-6 max-h-[80vh] overflow-y-auto">
                                <!-- Form -->
                                <form
                                    x-ref="roomForm"
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
                                    x-data="{ open: false, step: 1, isValid: true, isValidStep2: true }"
                                    method="POST"
                                    action="{{ route('rooms.store') }}"
                                    enctype="multipart/form-data"
                                >
                                    @csrf

                                    <!-- Step 1 -->
                                    <div x-show="step === 1" x-transition x-ref="step1Form">
                                        @livewire('property-room-selector', ['userProperty' => Auth::user()->property_id])

                                        <div class="grid grid-cols-2 gap-6 mb-4">
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium">Nomor Kamar</label>
                                                    <input type="text" name="room_no" required class="w-full border rounded p-2" placeholder="">
                                                </div>
                                            </div>

                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium">Nama Kamar</label>
                                                    <input type="text" name="room_name" required class="w-full border rounded p-2" placeholder="">
                                                </div>
                                            </div>
                                        </div>

                                        <div 
                                            x-data="{
                                                mode: null,
                                                dailyOriginal: 0,
                                                monthlyOriginal: 0,

                                                get priceLabel() {
                                                    if (this.mode === 'daily') return 'Harga Original Harian';
                                                    if (this.mode === 'monthly') return 'Harga Original Bulanan';
                                                    return '';
                                                },

                                                get priceNotes() {
                                                    if (this.mode === 'daily') return `Harga harian akan berlaku selama setahun terhitung dari tanggal pembuatan kamar.<br>Untuk harga spesial, silahkan gunakan fitur ubah harga setelah kamar disimpan.`;
                                                    if (this.mode === 'monthly') return '';
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
                                            }" 
                                            class="space-y-4"
                                        >
                                            <div class="col-span-2 mb-2">
                                                <h3 class="text-md font-medium border-gray-300 pb-1">Jenis Kamar</h3>
                                            </div>

                                            <!-- Mode selector -->
                                            <div class="flex space-x-6">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" value="daily" x-model="mode" class="form-radio text-blue-600">
                                                    <span class="ml-2">Harian</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" value="monthly" x-model="mode" class="form-radio text-blue-600">
                                                    <span class="ml-2">Bulanan</span>
                                                </label>
                                            </div>

                                            <!-- Price input shown only if mode is selected -->
                                            <div x-show="mode" x-transition>
                                                <label class="block text-sm font-medium" x-text="priceLabel"></label>
                                                <!-- Hidden field to submit the selected mode -->
                                                <input type="hidden" name="mode" x-model="mode">

                                                <!-- Price input -->
                                                <input
                                                    type="number"
                                                    min="0"
                                                    x-model.number="currentPrice"
                                                    :name="mode === 'daily' ? 'daily_price' : 'monthly_price'"
                                                    class="w-full border rounded p-2 mt-1"
                                                    required
                                                />                                              
                                                <p class="text-sm text-red-600 italic mb-4" x-html="priceNotes"></p>
                                            </div>
                                        </div>

                                        <div class="flex gap-4 mb-4">
                                            <div class="w-full">
                                                <label class="block text-sm font-medium">Deskripsi Kamar Indonesia</label>
                                                <textarea name="description_id" required class="w-full border rounded p-2" placeholder=""></textarea>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-sm font-medium">Foto Kamar</label>
                                            <input type="file" name="photo" accept="image/*" class="w-full border rounded p-2">
                                        </div>

                                        <p class="text-sm text-gray-600 italic mb-4">
                                            Gambar ini akan dijadikan thumbnail kamar. Untuk menambahkan gambar lebih bisa setelah kamar sudah disimpan.
                                        </p>

                                        <!-- Validation message -->
                                        <div x-show="!isValid" class="text-red-600 text-sm mb-2">Semua kolom wajib diisi sebelum lanjut.</div>

                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tutup</button>
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
                                            <h3 class="text-lg font-medium border-gray-300 pb-1">Fasilitas</h3>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4 mb-4">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="wifi" class="form-checkbox text-blue-600" />
                                                <span class="ml-2">WiFi</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="tv" class="form-checkbox text-blue-600" />
                                                <span class="ml-2">TV</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="ac" class="form-checkbox text-blue-600" />
                                                <span class="ml-2">AC</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="bathroom" class="form-checkbox text-blue-600" />
                                                <span class="ml-2">Bathroom</span>
                                            </label>
                                        </div>

                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="button" @click="step = 1" class="bg-gray-500  hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-white px-4 py-2 rounded">Kembali</button>
                                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
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
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div>
                    <select id="room-filter"
                        class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Properti</option>
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
                                    <div class="text-sm text-gray-500">{{ $room->no }}</div>
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
                                    @livewire('room-status-toggle', ['roomId' => $room->idrec, 'status' => $room->status])
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <!-- AlpineJS Wrapper -->
                                        <div x-data="{ open: false, step: 1, isValid: true }">

                                            <!-- Add Room Button -->
                                            <button @click="open = true"
                                                class="text-blue-600 hover:text-blue-900" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path
                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                            </button>

                                            <!-- Modal -->
                                            <div x-show="open" x-cloak class="fixed inset-0 backdrop-blur bg-opacity-30 flex items-center justify-center z-50 transition-opacity">
                                                <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative overflow-hidden">
                                                    <!-- Modal Header -->
                                                    <div class="flex justify-between items-center px-6 py-4 border-b">
                                                        <div>
                                                            <h2 x-show="step === 1" class="text-xl font-semibold text-slate-800">Edit Informasi Kamar</h2>
                                                            <h2 x-show="step === 2" class="text-xl font-semibold text-slate-800">Edit Fasilitas</h2>
                                                        </div>
                                                        <button @click="open = false" class="text-gray-600 hover:text-black text-2xl">&times;</button>
                                                    </div>

                                                    <!-- Modal Body -->
                                                    <div class="p-6 max-h-[80vh] overflow-y-auto text-left">
                                                        <!-- Form -->
                                                        <form
                                                            x-ref="roomForm"
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
                                                            x-data="{ open: false, step: 1, isValid: true, isValidStep2: true }"
                                                            method="POST"
                                                            action="{{ route('rooms.update', ['idrec' => $room->idrec]) }}"
                                                            enctype="multipart/form-data"
                                                        >
                                                            @csrf

                                                            <!-- Step 1 -->
                                                            <div x-show="step === 1" x-transition x-ref="step1Form">
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium">Nama Kamar</label>
                                                                    <input type="text" name="edit_room_name" required class="w-full border rounded p-2" value="{{ e($room->name) }}">
                                                                </div>

                                                                <div 
                                                                    x-data="() => ({
                                                                        mode: '{{ e($room->periode) }}',
                                                                        dailyOriginal: {{ e($room->price_original_daily) }},
                                                                        monthlyOriginal: {{ e($room->price_original_monthly) }},

                                                                        get priceLabel() {
                                                                            if (this.mode === 'daily') return 'Harga Original Harian';
                                                                            if (this.mode === 'monthly') return 'Harga Original Bulanan';
                                                                            return '';
                                                                        },

                                                                        get priceNotes() {
                                                                            if (this.mode === 'daily') return `Harga harian akan berlaku selama setahun terhitung dari tanggal pembuatan kamar.<br>Untuk harga spesial, silahkan gunakan fitur ubah harga setelah kamar disimpan.`;
                                                                            if (this.mode === 'monthly') return '';
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
                                                                    })"
                                                                    class="space-y-4"
                                                                >
                                                                    <div class="col-span-2 mb-2">
                                                                        <h3 class="text-md font-medium border-gray-300 pb-1">Jenis Kamar</h3>
                                                                    </div>

                                                                    <!-- Mode selector -->
                                                                    <div class="flex space-x-6">
                                                                        <label class="inline-flex items-center">
                                                                            <input type="radio" value="daily" x-model="mode" class="form-radio text-blue-600">
                                                                            <span class="ml-2">Harian</span>
                                                                        </label>
                                                                        <label class="inline-flex items-center">
                                                                            <input type="radio" value="monthly" x-model="mode" class="form-radio text-blue-600">
                                                                            <span class="ml-2">Bulanan</span>
                                                                        </label>
                                                                    </div>

                                                                    <!-- Price input shown only if mode is selected -->
                                                                    <div x-show="mode" x-transition>
                                                                        <label class="block text-sm font-medium" x-text="priceLabel"></label>
                                                                        <!-- Hidden field to submit the selected mode -->
                                                                        <input type="hidden" name="mode" x-model="mode">

                                                                        <!-- Price input -->
                                                                        <input
                                                                            type="number"
                                                                            min="0"
                                                                            x-model.number="currentPrice"
                                                                            :name="mode === 'daily' ? 'daily_price' : 'monthly_price'"
                                                                            class="w-full border rounded p-2 mt-1"
                                                                            required
                                                                        />                                              
                                                                        <p class="text-sm text-red-600 italic mb-4" x-html="priceNotes"></p>
                                                                    </div>
                                                                </div>

                                                                <div class="flex gap-4 mb-4">
                                                                    <div class="w-full">
                                                                        <label class="block text-sm font-medium">Deskripsi Kamar Indonesia</label>
                                                                        <textarea name="description_id" required class="w-full border rounded p-2" value="{{ e($room->descriptions) }}">{{ e($room->descriptions) }}</textarea>
                                                                    </div>
                                                                </div>

                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium">Foto Kamar</label>
                                                                    <input type="file" name="photo" accept="image/*" class="w-full border rounded p-2">
                                                                </div>

                                                                <p class="text-sm text-gray-600 italic mb-4">
                                                                    Gambar ini akan dijadikan thumbnail kamar. Untuk menambahkan gambar lebih bisa setelah kamar sudah disimpan.
                                                                </p>

                                                                <!-- Validation message -->
                                                                <div x-show="!isValid" class="text-red-600 text-sm mb-2">Semua kolom wajib diisi sebelum lanjut.</div>

                                                                <div class="flex justify-end gap-2 mt-4">
                                                                    <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Tutup</button>
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
                                                                    <h3 class="text-lg font-medium border-gray-300 pb-1">Fasilitas</h3>
                                                                </div>
                                                                
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="wifi" class="form-checkbox text-blue-600"
                                                                            value="1" {{ ($room->facility['wifi'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">WiFi</span>
                                                                    </label>

                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="tv" class="form-checkbox text-blue-600"
                                                                            value="1" {{ ($room->facility['tv'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">TV</span>
                                                                    </label>

                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="ac" class="form-checkbox text-blue-600"
                                                                            value="1" {{ ($room->facility['ac'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">AC</span>
                                                                    </label>

                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" name="bathroom" class="form-checkbox text-blue-600"
                                                                            value="1" {{ ($room->facility['bathroom'] ?? false) == true ? 'checked' : '' }}>
                                                                        <span class="ml-2">Bathroom</span>
                                                                    </label>
                                                                </div>

                                                                <div class="flex justify-end gap-2 mt-4">
                                                                    <button type="button" @click="step = 1" class="bg-gray-500  hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-white px-4 py-2 rounded">Kembali</button>
                                                                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
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
                                            <a href="#" class="text-green-600 hover:text-green-900" title="Edit Harga" @click.prevent="priceModalOpen = true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M4 3a1 1 0 000 2h12a1 1 0 100-2H4zm1 4a1 1 0 000 2h10a1 1 0 100-2H5zm-1 4a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zm1 3a1 1 0 000 2h6a1 1 0 100-2H5z"/>
                                                </svg>
                                            </a>

                                            <!-- Modal -->
                                            <div x-show="priceModalOpen" x-cloak x-transition class="fixed inset-0 flex items-center justify-center backdrop-blur bg-opacity-30 z-50 text-left">
                                                <div x-data="{
                                                    startDate: '',
                                                    endDate: '',
                                                    dateRangePrice: '',
                                                    setPrice: '',
                                                    async fetchPricesForRange(start, end) {
                                                        const dates = [];
                                                        let current = new Date(start);
                                                        while (current <= new Date(end)) {
                                                            dates.push(current.toLocaleDateString('en-CA'));
                                                            current.setDate(current.getDate() + 1);
                                                        }

                                                        const priceList = [];

                                                        for (let date of dates) {
                                                            const res = await fetch(`/properties/rooms/{{ $room->idrec }}/price?date=${date}`);
                                                            const data = await res.json();
                                                            priceList.push(data.price ?? null);
                                                        }

                                                        const uniquePrices = [...new Set(priceList)];

                                                        this.dateRangePrice = uniquePrices.length === 1 && uniquePrices[0] !== null
                                                            ? uniquePrices[0]
                                                            : '-';
                                                    },
                                                    init() {
                                                        flatpickr(this.$el.querySelector('.inline-calendar'), {
                                                            inline: true,
                                                            mode: 'range',
                                                            dateFormat: 'Y-m-d',
                                                            onChange: async (dates) => {
                                                                if (dates.length > 0) {
                                                                    this.startDate = dates[0].toLocaleDateString('en-CA');
                                                                    this.endDate = dates[1]
                                                                        ? dates[1].toLocaleDateString('en-CA')
                                                                        : dates[0].toLocaleDateString('en-CA');

                                                                    await this.fetchPricesForRange(this.startDate, this.endDate);
                                                                }
                                                            }
                                                        });
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
                                                                    end_date: this.endDate,
                                                                    price: this.setPrice
                                                                })
                                                            });

                                                            const data = await res.json();

                                                            if (res.ok) {
                                                                alert(data.message || 'Harga berhasil diperbarui!');
                                                            } else {
                                                                alert(data.message || 'Gagal memperbarui harga.');
                                                            }
                                                        } catch (error) {
                                                            console.error('Unexpected error:', error);
                                                            alert('Terjadi kesalahan saat memperbarui harga.');
                                                        }
                                                    }
                                                }" x-init="init"
                                                class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 relative space-y-6 overflow-y-auto max-h-[90vh]">

                                                    <!-- Header -->
                                                    <div class="flex justify-between items-center border-b pb-3">
                                                        <h2 class="text-lg font-semibold text-gray-800">Edit Harga</h2>
                                                        <button @click="priceModalOpen = false" class="text-gray-500 hover:text-red-500 text-2xl font-bold leading-none">&times;</button>
                                                    </div>

                                                    <!-- Content Grid -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <!-- LEFT: Calendar -->
                                                        <div class="text-center space-y-4">
                                                            <div class="p-2 inline-block" style="position: relative;">
                                                                <style>.inline-calendar input { display: none; }</style>
                                                                <div class="inline-calendar"></div>
                                                            </div>
                                                        </div>

                                                        <!-- RIGHT: Form -->
                                                        <div class="space-y-4">
                                                            <div class="grid grid-cols-2 gap-4">
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Awal</label>
                                                                    <input type="text" :value="startDate" class="border border-gray-300 px-4 py-2 rounded w-full" readonly>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Akhir</label>
                                                                    <input type="text" :value="endDate" class="border border-gray-300 px-4 py-2 rounded w-full" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="w-full space-y-1">
                                                                <label class="block text-sm font-medium text-gray-700">Harga pada rentang tanggal</label>
                                                                <input type="text" :value="dateRangePrice" class="border border-gray-300 px-4 py-2 rounded w-full" readonly>
                                                                <p class="text-xs text-gray-500 mt-1 w-full break-words whitespace-normal">
                                                                    Jika harga menunjukkan "-", maka ada harga yang berbeda atau harga yang kosong.
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Baru</label>
                                                                <input type="number" x-model="setPrice" class="border border-gray-300 px-4 py-2 rounded w-full" placeholder="Masukkan Harga">
                                                            </div>
                                                            <div class="flex justify-between pt-2">
                                                                <button @click="updatePrice" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Update</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Flatpickr & Alpine -->
                                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
