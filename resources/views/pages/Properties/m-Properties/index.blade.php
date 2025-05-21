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

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <input type="text" placeholder="Search properties..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select
                        class="w-full md:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>
            </div>
        </div>

        <!-- Property Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name</th>
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
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($properties as $property)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $property->image ?? '' }}"
                                                alt="">
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

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                    {{ $property->creator->username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <form class="toggle-status-form">
                                        @csrf
                                        <input type="hidden" name="idrec" value="{{ $property->idrec }}">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="status" class="sr-only peer"
                                                {{ $property->status == 1 ? 'checked' : '' }} value="1">
                                            <div
                                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                            </div>
                                        </label>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="" class="text-blue-600 hover:text-blue-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="" method="POST"
                                            onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
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
                    <a href="#"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <a href="#"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $properties->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $properties->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $properties->total() }}</span>
                            results
                        </p>
                    </div>
                    <div>
                        {{ $properties->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                step: 1,

                init() {
                    // Initialize province and city dropdown
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
    </script>
</x-app-layout>
