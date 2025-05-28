<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti</h1>
            <div class="mt-4 md:mt-0">
                <div x-data="modalProperty()">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center"
                        type="button" @click.prevent="modalOpenDetail = true;" aria-controls="feedback-modal1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Property
                    </button>

                    <!-- Modal backdrop -->
                    <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity"
                        x-show="modalOpenDetail" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-out duration-100" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>

                    <!-- Modal dialog -->
                    <div id="feedback-modal1"
                        class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                        role="dialog" aria-modal="true" x-show="modalOpenDetail"
                        x-transition:enter="transition ease-in-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in-out duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                        <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full"
                            @click.outside="modalOpenDetail = false" @keydown.escape.window="modalOpenDetail = false">
                            <!-- Modal header -->
                            <div class="px-5 py-3 border-b border-slate-200" id="modalAddLpjDetail">
                                <div class="flex justify-between items-center">
                                    <div class="font-semibold text-slate-800">Tambahkan Properti</div>
                                    <button type="button" class="text-slate-400 hover:text-slate-500"
                                        @click="modalOpenDetail = false">
                                        <div class="sr-only">Close</div>
                                        <svg class="w-4 h-4 fill-current">
                                            <path
                                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <!-- Modal content -->
                            <div class="modal-content text-xs px-5 py-4">
                                <form id="propertyForm" method="POST" action="{{ route('properties.store') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <!-- Step 1 - Basic Information -->
                                    <div x-show="step === 1">
                                        <div class="space-y-4">
                                            <div>
                                                <label for="property_name"
                                                    class="block text-sm font-medium text-gray-700">Nama
                                                    Properti*</label>
                                                <input type="text" id="property_name" name="property_name" required
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Jenis
                                                    Properti*</label>
                                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3">
                                                    @foreach (['Kos', 'Rumah', 'Apartment', 'Villa', 'Hotel'] as $type)
                                                        <div class="flex items-center">
                                                            <input id="type-{{ $type }}" name="property_type"
                                                                type="radio" value="{{ $type }}"
                                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                                required>
                                                            <label for="type-{{ $type }}"
                                                                class="ml-2 block text-sm text-gray-700">{{ $type }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div>
                                                <label for="description"
                                                    class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                                <textarea id="description" name="description" rows="4" required
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2 - Location Details -->
                                    <div x-show="step === 2" x-cloak>
                                        <div class="space-y-4">
                                            <div>
                                                <label for="full_address"
                                                    class="block text-sm font-medium text-gray-700">Alamat
                                                    Lengkap*</label>
                                                <textarea id="full_address" name="full_address" rows="2" required
                                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Pinpoint
                                                    Lokasi*</label>
                                                <div id="map" class="mt-1 h-64 bg-gray-200 rounded-md"></div>
                                                <div id="coordinates" class="mt-2 text-sm text-gray-500"></div>
                                                <input type="hidden" id="latitude" name="latitude">
                                                <input type="hidden" id="longitude" name="longitude">
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="province"
                                                        class="block text-sm font-medium text-gray-700">Provinsi*</label>
                                                    <select id="province" name="province" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                                        class="block text-sm font-medium text-gray-700">Kota/Kabupaten*</label>
                                                    <select id="city" name="city" required disabled
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                        <option value="">Pilih Kota</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="district"
                                                        class="block text-sm font-medium text-gray-700">Kecamatan*</label>
                                                    <input type="text" id="district" name="district" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                </div>

                                                <div>
                                                    <label for="village"
                                                        class="block text-sm font-medium text-gray-700">Kelurahan*</label>
                                                    <input type="text" id="village" name="village" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="postal_code"
                                                        class="block text-sm font-medium text-gray-700">Kode
                                                        Pos</label>
                                                    <input type="text" id="postal_code" name="postal_code"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                </div>

                                                <div>
                                                    <label for="distance"
                                                        class="block text-sm font-medium text-gray-700">Jarak Terdekat
                                                        dari Fasilitas Umum</label>
                                                    <input type="text" id="distance" name="distance" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 3 - Photos and Facilities -->
                                    <div x-show="step === 3" x-cloak>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Foto Properti*
                                                    (Minimal 3 foto)</label>
                                                <div
                                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                                    <div class="space-y-1 text-center">
                                                        <div class="flex text-sm text-gray-600">
                                                            <label for="property_images"
                                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
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
                                                <div id="imagePreview" class="mt-2 grid grid-cols-3 gap-2 hidden">
                                                </div>
                                            </div>

                                            <div>
                                                <div class="mt-2 space-y-4">
                                                    <div>
                                                        <span
                                                            class="font-semibold text-sm text-gray-700">Fasilitas</span>
                                                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3">
                                                            @foreach (['High-speed WiFi', '24/7 Security', 'Shared Kitchen', 'Laundry Service', 'Parking Area', 'Common Area'] as $item)
                                                                <div class="flex items-center">
                                                                    <input id="amenity-{{ $loop->index }}"
                                                                        name="facilities[]" type="checkbox"
                                                                        value="{{ $item }}"
                                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                                    <label for="amenity-{{ $loop->index }}"
                                                                        class="ml-2 block text-sm text-gray-700">{{ $item }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-sm text-gray-700">Rules</span>
                                                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3">
                                                            @foreach (['No Smoking', 'No Pets', 'ID Card Required', 'Deposit Required'] as $item)
                                                                <div class="flex items-center">
                                                                    <input id="rule-{{ $loop->index }}"
                                                                        name="facilities[]" type="checkbox"
                                                                        value="{{ $item }}"
                                                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                                    <label for="rule-{{ $loop->index }}"
                                                                        class="ml-2 block text-sm text-gray-700">{{ $item }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Form Actions -->
                                    <div class="mt-6 flex justify-between">
                                        <div>
                                            <button type="button" x-show="step > 1" @click="step--"
                                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Sebelumnya
                                            </button>
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="button" @click="modalOpenDetail = false"
                                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Tutup
                                            </button>
                                            <button type="button" x-show="step < 3"
                                                @click="validateStep(step) && step++"
                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Selanjutnya
                                            </button>
                                            <button type="submit" x-show="step === 3"
                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
                                                $firstImage = $images[0] ?? 'https://via.placeholder.com/150';
                                            @endphp
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $firstImage }}" alt="{{ $property->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $property->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $property->city }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $property->province }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <div>{{ \Carbon\Carbon::parse($property->created_at)->format('Y M d') }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($property->created_at)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @if ($property->updated_at)
                                        <div>{{ \Carbon\Carbon::parse($property->updated_at)->format('Y M d') }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($property->updated_at)->format('H:i') }}</div>
                                    @else
                                        <div>-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    {{ $property->creator->username ?? 'Unknown' }}
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
                                    <div class="flex justify-center items-center space-x-3">
                                        <div x-data="modal({{ $property }})" class="flex justify-center space-x-2">
                                            <button @click="modalOpenDetail = true"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                                                Edit
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>

                                            <!-- Modern Modal -->
                                            <div class="fixed inset-0 z-50 overflow-y-auto" x-show="modalOpenDetail"
                                                x-cloak x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0">

                                                <!-- Modal backdrop with blur effect -->
                                                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm"
                                                    aria-hidden="true" @click="modalOpenDetail = false"></div>

                                                <!-- Modal container -->
                                                <div class="flex min-h-screen items-center justify-center p-4">
                                                    <!-- Modal dialog -->
                                                    <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl transition-all"
                                                        @click.stop x-show="modalOpenDetail"
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 scale-95"
                                                        x-transition:enter-end="opacity-100 scale-100"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 scale-100"
                                                        x-transition:leave-end="opacity-0 scale-95">

                                                        <!-- Modal header -->
                                                        <div
                                                            class="flex items-center justify-between p-6 border-b border-gray-200">
                                                            <h3 class="text-xl font-semibold text-gray-900">Edit
                                                                Document
                                                            </h3>
                                                            <button type="button"
                                                                class="text-gray-400 hover:text-gray-500 rounded-full p-1"
                                                                @click="modalOpenDetail = false">
                                                                <svg class="h-6 w-6" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <!-- Modal content -->
                                                        <div class="p-6 overflow-y-auto max-h-[70vh]">
                                                            <form method="POST"
                                                                action="{{ route('properties.update', $property->idrec) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')

                                                                <!-- Grid layout -->
                                                                <div class="grid gap-6 mb-6 md:grid-cols-2">
                                                                    <!-- Date input -->
                                                                    <div class="relative">
                                                                        <input type="date" name="date"
                                                                            id="date" required
                                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                            value="{{ old('date', $property->date) }}" />
                                                                        <label for="date"
                                                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                            Select Date
                                                                        </label>
                                                                    </div>

                                                                    <!-- Document number -->
                                                                    <div class="relative flex items-center">
                                                                        <div class="relative flex-1">
                                                                            <input name="id_document" id="id_document"
                                                                                required
                                                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                                placeholder=" "
                                                                                value="{{ old('document', $property->no_document) }}"
                                                                                onkeypress="return event.key !== ' '"
                                                                                oninput="this.value = this.value.replace(/\s/g, '')" />
                                                                            <label for="id_document"
                                                                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                                Document Number
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Second row -->
                                                                <div class="grid gap-6 mb-6 md:grid-cols-4">
                                                                    <!-- Department dropdown -->
                                                                    <div class="relative">
                                                                        <select name="dep" id="dep" required
                                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                            <option value="" selected>Select
                                                                                Department</option>
                                                                            {{-- @foreach ($deps as $department)
                                                                                <option value="{{ $department->id }}"
                                                                                    @if (isset($property->subDepartment) && $department->id == $property->subDepartment->parent->id) selected @endif>
                                                                                    {{ $department->name }}
                                                                                </option>
                                                                            @endforeach --}}
                                                                        </select>
                                                                        <label for="dep"
                                                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                            Department
                                                                        </label>
                                                                    </div>

                                                                    <!-- Sub Department dropdown -->
                                                                    <div class="relative">
                                                                        <select name="sub_dep" id="sub_dep" required
                                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                            <option value="" disabled selected>
                                                                                Select
                                                                                Sub Department</option>
                                                                            @if (isset($property->subDepartment) && isset($subDeps))
                                                                                @foreach ($subDeps as $subDep)
                                                                                    <option
                                                                                        value="{{ $subDep->id }}"
                                                                                        @if ($property->subDepartment->id == $subDep->id) selected @endif>
                                                                                        {{ $subDep->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        <label for="sub_dep"
                                                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                            Sub Department
                                                                        </label>
                                                                    </div>

                                                                    <!-- Document type dropdown -->
                                                                    <div class="relative">
                                                                        <select name="type_docs_modal"
                                                                            id="type_docs_modal" required
                                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                            <option value="" data-code="">
                                                                                Select
                                                                                Document Type</option>
                                                                            {{-- @foreach ($documentTypes as $docType)
                                                                                <option value="{{ $docType->name }}"
                                                                                    data-code="{{ $docType->code }}"
                                                                                    {{ old('type_docs_modal', $property->doc_type ?? '') == $docType->name ? 'selected' : '' }}>
                                                                                    {{ $docType->name }}
                                                                                </option>
                                                                            @endforeach --}}
                                                                        </select>
                                                                        <label for="type_docs_modal"
                                                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                            Type Document
                                                                        </label>
                                                                    </div>

                                                                    <!-- User email dropdown -->
                                                                    <div class="relative">
                                                                        <select name="user_email" id="user_email"
                                                                            required
                                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                            <option value="" disabled selected>
                                                                                Select
                                                                                User Email</option>
                                                                            {{-- @foreach ($users as $user)
                                                                                <option value="{{ $user->id }}"
                                                                                    @if ($property->created_by == $user->id) selected @endif>
                                                                                    {{ $user->name }}
                                                                                    ({{ $user->email }})
                                                                                </option>
                                                                            @endforeach --}}
                                                                        </select>
                                                                        <label for="user_email"
                                                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                            User Email
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <!-- Description input -->
                                                                <div class="mb-6">
                                                                    <div class="relative">
                                                                        <textarea name="description" id="description" required rows="3"
                                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                            placeholder=" ">{{ old('description', $property->description) }}</textarea>
                                                                        <label for="description"
                                                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-7 peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                            Description
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <!-- File upload with drag and drop -->
                                                                <input type="hidden" name="file_names"
                                                                    id="file_names_input" x-model="fileNames">
                                                                <!-- File upload section -->
                                                                <div class="mb-6">
                                                                    <!-- Show dropzone only if no files are uploaded -->
                                                                    <div x-show="files.length === 0"
                                                                        class="relative group">
                                                                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/50"
                                                                            id="dropzone" @dragover.prevent
                                                                            @dragenter.prevent @dragleave.prevent
                                                                            @drop.prevent="handleDrop($event)"
                                                                            x-bind:class="{ 'border-blue-500 bg-blue-50/50': isDragging }">
                                                                            <input type="file" name="files[]"
                                                                                id="files" class="hidden" multiple
                                                                                @change="handleFiles($event)"
                                                                                accept="application/pdf, image/jpeg, image/jpg" />
                                                                            <div
                                                                                class="flex flex-col items-center justify-center space-y-3">
                                                                                <div
                                                                                    class="p-3 bg-blue-100 rounded-full">
                                                                                    <svg class="w-8 h-8 text-blue-600"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                                                    </svg>
                                                                                </div>
                                                                                <div
                                                                                    class="flex text-sm text-gray-600">
                                                                                    <label for="files"
                                                                                        class="relative cursor-pointer font-medium text-blue-600 hover:text-blue-500">
                                                                                        <span>Upload a file</span>
                                                                                        <input id="files"
                                                                                            name="files[]"
                                                                                            type="file"
                                                                                            class="sr-only" multiple>
                                                                                    </label>
                                                                                    <p class="pl-1">or drag and drop
                                                                                    </p>
                                                                                </div>
                                                                                <p class="text-xs text-gray-500">
                                                                                    PDF, JPG up to 25MB
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Show uploaded files -->
                                                                    <div id="file-names" class="mt-4 space-y-2">
                                                                        <template x-for="(file, index) in files"
                                                                            :key="index">
                                                                            <div
                                                                                class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                                                <div
                                                                                    class="flex items-center space-x-3">
                                                                                    <svg class="w-6 h-6 text-blue-500"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                                    </svg>
                                                                                    <span
                                                                                        class="text-sm font-medium text-gray-700 truncate max-w-xs"
                                                                                        x-text="file.name"></span>
                                                                                </div>
                                                                                <button type="button"
                                                                                    class="text-red-500 hover:text-red-700"
                                                                                    @click="removeFile(index)">
                                                                                    <svg class="w-5 h-5"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                                <!-- Form actions -->
                                                                <div
                                                                    class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                                                    <button type="button"
                                                                        @click="modalOpenDetail = false"
                                                                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                                        Cancel
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 flex items-center">
                                                                        <svg x-show="isSubmitting"
                                                                            class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24">
                                                                            <circle class="opacity-25" cx="12"
                                                                                cy="12" r="10"
                                                                                stroke="currentColor"
                                                                                stroke-width="4">
                                                                            </circle>
                                                                            <path class="opacity-75"
                                                                                fill="currentColor"
                                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                            </path>
                                                                        </svg>
                                                                        <span>Update Document</span>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
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
            Alpine.data('modal', (document) => ({
                modalOpenDetail: false,
                files: [],
                isDragging: false,
                fileUploaded: false,
                document: document,
                fileNames: document.pdfblob ? [document.file_name] : [],
                isSubmitting: false,

                init() {
                    // Jika dokumen sudah memiliki file, tambahkan ke array files
                    if (document.file_name) {
                        this.files.push({
                            name: document.file_name
                        });
                    }
                },

                handleFiles(event) {
                    const files = event.target.files;
                    this.processFiles(files);
                },

                handleDrop(event) {
                    event.preventDefault();
                    const files = event.dataTransfer.files;
                    this.processFiles(files);
                    this.isDragging = false;
                },

                processFiles(files) {
                    if (this.files.length === 0) {
                        for (let file of files) {
                            if (file.type === 'application/pdf' && file.size <= 25 * 1024 * 1024) {
                                this.files.push(file);
                                this.fileUploaded = true;
                                this.updateFileNames();

                                Toastify({
                                    text: "File uploaded successfully!",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#4CAF50"
                                    }
                                }).showToast();

                                break;
                            } else {
                                Toastify({
                                    text: `File ${file.name} invalid or exceeds 25MB.`,
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#FF5733"
                                    }
                                }).showToast();
                            }
                        }
                    } else {
                        Toastify({
                            text: "Only one file can be uploaded.",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#FFC107"
                            }
                        }).showToast();
                    }
                },

                removeFile(index) {
                    this.files.splice(index, 1);
                    if (this.files.length === 0) {
                        this.fileUploaded = false;
                    }
                    this.updateFileNames();

                    Toastify({
                        text: "File deleted.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#607D8B"
                        }
                    }).showToast();
                },

                updateFileNames() {
                    const fileNames = this.files.map(file => file.name).join(',');
                    this.fileNames = fileNames;
                    document.getElementById('file_names_input').value = fileNames;
                },

                submitForm() {
                    this.isSubmitting = true;
                    // Your form submission logic here
                }
            }));
        });

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
