<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti</h1>
            <div class="mt-4 md:mt-0">
                <div x-data="modal()">
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

            <!-- Table -->
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
                                        <!-- View -->
                                        <div x-data="modal()" class="relative group">
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
                                                                    image: @json($firstImage),
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
                                        </div>

                                        <!-- Edit Button -->
                                        <button type="button"
                                            class="text-yellow-500 hover:text-yellow-700 transition-colors duration-200"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </button>

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
            Alpine.data('modal', () => ({
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
