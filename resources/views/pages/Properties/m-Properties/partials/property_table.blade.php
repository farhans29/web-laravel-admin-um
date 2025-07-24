<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nama</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Provinsi</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal Penambahan</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal Perubahan</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ditambahkan Oleh</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status</th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="propertyTableBody">
        @foreach ($properties as $property)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            @if ($property->images->isNotEmpty() && !empty($property->images->first()->image))
                                <img src="data:image/jpeg;base64,{{ $property->images->first()->image }}"
                                    alt="Property Image" class="w-full h-full object-cover rounded" />
                            @else
                                <img src="{{ asset('images/picture.png') }}" alt="Default Property Image"
                                    class="w-full h-full object-cover rounded" />
                            @endif
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
                        <input type="checkbox" value="" class="sr-only peer" data-id="{{ $property->idrec }}"
                            {{ $property->status ? 'checked' : '' }} onchange="toggleStatus(this)">
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
                                // Initialize empty array for images
                                $images = [];

                                // Only add images that belong to this property
                                foreach ($property->images as $image) {
                                    if (!empty($image->image)) {
                                        $images[] = 'data:image/jpeg;base64,' . $image->image;
                                    }
                                }

                                $features = json_encode($property->features, JSON_HEX_APOS | JSON_HEX_QUOT);
                            @endphp
                            <button class="text-blue-500 hover:text-blue-700 transition-colors duration-200"
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
                                                images: {!! json_encode($images) !!},
                                                location: @json($property->location),
                                                distance: @json($property->distance),
                                                features: {!! $features !!},                                                                                                                                                                                              
                                            })'
                                aria-controls="property-detail-modal" title="View Details">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <!-- Modal backdrop -->
                            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 transition-opacity"
                                x-show="modalOpenDetail" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-out duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                aria-hidden="true" x-cloak>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
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
                                                <template x-for="(image, index) in selectedProperty.images"
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
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <button x-show="selectedProperty.images.length > 1"
                                                @click="selectedProperty.currentImageIndex = (selectedProperty.currentImageIndex + 1) % selectedProperty.images.length"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 shadow-md">
                                                <svg class="w-6 h-6 text-gray-800" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
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
                                                <template x-for="(image, index) in selectedProperty.images"
                                                    :key="index">
                                                    <button @click="selectedProperty.currentImageIndex = index"
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
                                                <div class="flex flex-col items-center justify-center text-center">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-indigo-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
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

                                                <div class="flex flex-col items-center justify-center text-center">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-red-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
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

                                                <div class="flex flex-col items-center justify-center text-center">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-blue-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
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

                                                <div class="flex flex-col items-center justify-center text-center">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-green-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
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
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <h4 class="text-lg font-bold text-gray-900">
                                                        Features</h4>
                                                </div>
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                                    <template x-for="feature in selectedProperty.features">
                                                        <div
                                                            class="flex items-center space-x-3 bg-green-50 p-3 rounded-lg border border-green-100">
                                                            <!-- Feature Icons -->
                                                            <template x-if="feature === '24/7 Security'">
                                                                <svg class="h-5 w-5 text-green-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'Concierge'">
                                                                <svg class="h-5 w-5 text-purple-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'Laundry Service'">
                                                                <svg class="h-5 w-5 text-indigo-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <rect x="4" y="4" width="16" height="16"
                                                                        rx="2" stroke-width="1.5" />
                                                                    <rect x="5" y="5" width="14" height="14"
                                                                        rx="1" stroke-width="1.5" />
                                                                    <circle cx="12" cy="12" r="4"
                                                                        stroke-width="1.5" />
                                                                    <circle cx="12" cy="12" r="3"
                                                                        stroke-width="1" stroke-dasharray="1.5,1" />
                                                                    <circle cx="15" cy="12" r="0.5"
                                                                        fill="currentColor" />
                                                                    <circle cx="18" cy="7" r="0.8"
                                                                        fill="currentColor" />
                                                                    <circle cx="18" cy="9.5" r="0.8"
                                                                        fill="currentColor" />
                                                                    <rect x="6" y="6" width="3" height="1.5"
                                                                        rx="0.5" stroke-width="0.5" />
                                                                    <rect x="6" y="9" width="3" height="1.5"
                                                                        rx="0.5" stroke-width="0.5" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'Room Service'">
                                                                <svg class="h-5 w-5 text-pink-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v0M8 5v4m4-4v4m4-4v4" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'High-speed WiFi'">
                                                                <svg class="h-5 w-5 text-blue-500" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'Parking'">
                                                                <svg class="h-5 w-5 text-yellow-600"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <rect x="3" y="3" width="18" height="18"
                                                                        rx="2" stroke-width="2"
                                                                        fill="none" />
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2.5"
                                                                        d="M9 7h4a3 3 0 1 1 0 6H9v5" fill="none" />
                                                                    <path stroke-linecap="round" stroke-width="2.5"
                                                                        d="M9 7v11" fill="none" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'Swimming Pool'">
                                                                <svg class="h-5 w-5 text-cyan-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M2 16c2 2 5 2 7 0s5-2 7 0 5 2 7 0M2 12c2 2 5 2 7 0s5-2 7 0 5 2 7 0M2 8c2 2 5 2 7 0s5-2 7 0 5 2 7 0" />
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="1.5"
                                                                        d="M8 4c1 1 2 1 3 0M16 4c1 1 2 1 3 0" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'Gym'">
                                                                <svg class="h-5 w-5 text-red-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="3"
                                                                        d="M8 12h8" />
                                                                    <circle cx="6" cy="12" r="3"
                                                                        stroke-width="2" />
                                                                    <circle cx="6" cy="12" r="1.5"
                                                                        stroke-width="1" fill="currentColor" />
                                                                    <circle cx="18" cy="12" r="3"
                                                                        stroke-width="2" />
                                                                    <circle cx="18" cy="12" r="1.5"
                                                                        stroke-width="1" fill="currentColor" />
                                                                    <path stroke-linecap="round" stroke-width="2"
                                                                        d="M4 12h2M18 12h2" />
                                                                </svg>
                                                            </template>
                                                            <template x-if="feature === 'Restaurant'">
                                                                <svg class="h-5 w-5 text-orange-600" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 10h14a2 2 0 010 4H5a2 2 0 010-4z" />
                                                                    <rect x="6" y="12" width="12" height="2"
                                                                        rx="1" stroke-width="1.5" />
                                                                    <path stroke-linecap="round" stroke-width="1.2"
                                                                        d="M8 14.5h8M8 15.5h8M8 16.5h8" />
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
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
                                $features = json_encode($property->features, JSON_HEX_APOS | JSON_HEX_QUOT);
                                $images = $property->images
                                    ->map(function ($image) {
                                        return [
                                            'id' => $image->idrec,
                                            'url' => 'data:image/jpeg;base64,' . $image->image,
                                            'caption' => $image->caption,
                                            'name' => 'Image_' . $image->idrec . '.jpg',
                                        ];
                                    })
                                    ->toJson();
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
                                    features: {!! $features !!},
                                    existingImages: {!! $images !!},
                                    latitude: {{ $property->latitude ?? 'null' }},
                                    longitude: {{ $property->longitude ?? 'null' }},
                                    address: @json($property->address),
                                    subdistrict: @json($property->subdistrict),
                                    village: @json($property->village),
                                    postal_code: @json($property->postal_code),
                                    type: @json($property->type)
                                })'
                                aria-controls="property-edit-modal-{{ $property->idrec }}" title="Edit Property">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>

                            <!-- Modal backdrop -->
                            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                                x-show="editModalOpen" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-out duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                aria-hidden="true" x-cloak></div>

                            <!-- Modal dialog -->
                            <div id="property-edit-modal-{{ $property->idrec }}"
                                class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                role="dialog" aria-modal="true" x-show="editModalOpen"
                                x-transition:enter="transition ease-in-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in-out duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak>

                                <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full flex flex-col text-left"
                                    @click.outside="editModalOpen = false"
                                    @keydown.escape.window="editModalOpen = false">

                                    <!-- Modal header with step indicator -->
                                    <div
                                        class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                        <div class="flex justify-between items-center mb-4">
                                            <div class="font-bold text-xl text-gray-800">Edit Properti
                                            </div>
                                            <button type="button"
                                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                @click="editModalOpen = false">
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
                                                    :class="editStep >= 1 ?
                                                        'bg-blue-600 border-blue-600 text-white' :
                                                        'border-gray-300 text-gray-500'">
                                                    <span class="text-sm font-semibold" x-show="editStep < 1">1</span>
                                                    <svg x-show="editStep >= 1" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <p class="font-medium transition-colors duration-300"
                                                        :class="editStep >= 1 ? 'text-blue-600' :
                                                            'text-gray-500'">
                                                        Informasi Dasar
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Connector -->
                                            <div class="w-16 h-0.5 transition-colors duration-300"
                                                :class="editStep >= 2 ? 'bg-blue-600' : 'bg-gray-300'">
                                            </div>

                                            <!-- Step 2 -->
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                    :class="editStep >= 2 ?
                                                        'bg-blue-600 border-blue-600 text-white' :
                                                        'border-gray-300 text-gray-500'">
                                                    <span class="text-sm font-semibold" x-show="editStep < 2">2</span>
                                                    <svg x-show="editStep >= 2" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <p class="font-medium transition-colors duration-300"
                                                        :class="editStep >= 2 ? 'text-blue-600' :
                                                            'text-gray-500'">
                                                        Detail Lokasi</p>
                                                </div>
                                            </div>

                                            <!-- Connector -->
                                            <div class="w-16 h-0.5 transition-colors duration-300"
                                                :class="editStep >= 3 ? 'bg-blue-600' : 'bg-gray-300'">
                                            </div>

                                            <!-- Step 3 (Fasilitas) -->
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                    :class="editStep >= 3 ?
                                                        'bg-blue-600 border-blue-600 text-white' :
                                                        'border-gray-300 text-gray-500'">
                                                    <span class="text-sm font-semibold" x-show="editStep < 3">3</span>
                                                    <svg x-show="editStep >= 3" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <p class="font-medium transition-colors duration-300"
                                                        :class="editStep >= 3 ? 'text-blue-600' :
                                                            'text-gray-500'">
                                                        Fasilitas</p>
                                                </div>
                                            </div>

                                            <!-- Connector -->
                                            <div class="w-16 h-0.5 transition-colors duration-300"
                                                :class="editStep >= 4 ? 'bg-blue-600' : 'bg-gray-300'">
                                            </div>

                                            <!-- Step 4 (Foto) -->
                                            <div class="flex items-center">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                    :class="editStep >= 4 ?
                                                        'bg-blue-600 border-blue-600 text-white' :
                                                        'border-gray-300 text-gray-500'">
                                                    <span class="text-sm font-semibold" x-show="editStep < 4">4</span>
                                                    <svg x-show="editStep >= 4" class="w-5 h-5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <p class="font-medium transition-colors duration-300"
                                                        :class="editStep >= 4 ? 'text-blue-600' :
                                                            'text-gray-500'">
                                                        Foto</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal content -->
                                    <div class="flex-1 overflow-y-auto px-6 py-6">
                                        <form id="propertyFormEdit-{{ $property->idrec }}" method="POST"
                                            action="{{ route('properties.update', $property->idrec) }}"
                                            enctype="multipart/form-data" @submit.prevent="submitEditForm">
                                            @csrf
                                            @method('PUT')

                                            <!-- Step 1 - Basic Information -->
                                            <div x-show="editStep === 1"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                x-transition:enter-end="opacity-100 translate-x-0">
                                                <div class="space-y-6">
                                                    <div>
                                                        <label for="property_name_edit_{{ $property->idrec }}"
                                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Nama Properti <span class="text-red-500">*</span>
                                                        </label>
                                                        <input type="text"
                                                            id="property_name_edit_{{ $property->idrec }}"
                                                            name="property_name" required x-model="propertyData.name"
                                                            class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                            placeholder="Masukkan nama properti">
                                                    </div>

                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                            Jenis Properti <span class="text-red-500">*</span>
                                                        </label>
                                                        <div class="grid grid-cols-2 gap-4" x-data="{
                                                            types: [
                                                                { label: 'Kos', value: 'Kos' },
                                                                { label: 'Apartment', value: 'Apartment' },
                                                                { label: 'Villa', value: 'Villa' },
                                                                { label: 'Hotel', value: 'Hotel' }
                                                            ],
                                                            selectedType: propertyData.tags
                                                        }">
                                                            <template x-for="type in types" :key="type.value">
                                                                <div class="relative">
                                                                    <input
                                                                        :id="'type-edit-{{ $property->idrec }}-' +
                                                                        type.value"
                                                                        name="property_type" type="radio"
                                                                        :value="type.value" class="sr-only peer"
                                                                        required x-model="selectedType"
                                                                        @change="propertyData.tags = type.value">
                                                                    <label
                                                                        :for="'type-edit-{{ $property->idrec }}-' +
                                                                        type.value"
                                                                        class="flex items-center justify-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                        <span x-text="type.label"></span>
                                                                    </label>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="description_edit_{{ $property->idrec }}"
                                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Deskripsi <span class="text-red-500">*</span>
                                                        </label>
                                                        <textarea id="description_edit_{{ $property->idrec }}" name="description" rows="4" required
                                                            x-model="propertyData.description"
                                                            class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                            placeholder="Deskripsikan properti Anda..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Step 2 - Location Details -->
                                            <div x-show="editStep === 2"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                                <div class="space-y-6">
                                                    <div>
                                                        <label for="full_address_edit_{{ $property->idrec }}"
                                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                                            Alamat Lengkap <span class="text-red-500">*</span>
                                                        </label>
                                                        <textarea id="full_address_edit_{{ $property->idrec }}" name="full_address" rows="3" required
                                                            x-model="propertyData.address"
                                                            class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                            placeholder="Masukkan alamat lengkap properti"></textarea>
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                                            Pinpoint Lokasi <span class="text-red-500 ml-1">*</span>
                                                            <span class="text-gray-500 text-sm font-normal ml-2">(Klik
                                                                untuk menandai langsung pada
                                                                peta)</span>
                                                        </label>
                                                        <div id="map_edit_{{ $property->idrec }}"
                                                            class="h-64 bg-gray-100 rounded-lg border-2 border-gray-200 flex items-center justify-center">
                                                            <div class="text-gray-500 text-center"
                                                                x-show="!propertyData.latitude || !propertyData.longitude">
                                                                <svg class="w-12 h-12 mx-auto mb-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                                    </path>
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                                    </path>
                                                                </svg>
                                                                <p>Klik untuk menentukan lokasi</p>
                                                            </div>
                                                        </div>
                                                        <div id="coordinates_edit_{{ $property->idrec }}"
                                                            class="mt-2 text-sm text-gray-500">
                                                            <span
                                                                x-show="propertyData.latitude && propertyData.longitude">
                                                                Koordinat: <span
                                                                    x-text="propertyData.latitude"></span>,
                                                                <span x-text="propertyData.longitude"></span>
                                                            </span>
                                                        </div>
                                                        <input type="hidden"
                                                            id="latitude_edit_{{ $property->idrec }}" name="latitude"
                                                            x-model="propertyData.latitude">
                                                        <input type="hidden"
                                                            id="longitude_edit_{{ $property->idrec }}"
                                                            name="longitude" x-model="propertyData.longitude">
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label for="province_edit_{{ $property->idrec }}"
                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                Provinsi <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="text"
                                                                id="province_edit_{{ $property->idrec }}"
                                                                name="province" required
                                                                x-model="propertyData.province"
                                                                placeholder="Masukkan Provinsi"
                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                                        </div>

                                                        <div>
                                                            <label for="city_edit_{{ $property->idrec }}"
                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                Kota/Kabupaten <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="text"
                                                                id="city_edit_{{ $property->idrec }}" name="city"
                                                                required x-model="propertyData.city"
                                                                placeholder="Masukkan Kota atau Kabupaten"
                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label for="district_edit_{{ $property->idrec }}"
                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                Kecamatan <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="text"
                                                                id="district_edit_{{ $property->idrec }}"
                                                                name="district" required
                                                                x-model="propertyData.subdistrict"
                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                placeholder="Masukkan kecamatan">
                                                        </div>

                                                        <div>
                                                            <label for="village_edit_{{ $property->idrec }}"
                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                Kelurahan <span class="text-red-500">*</span>
                                                            </label>
                                                            <input type="text"
                                                                id="village_edit_{{ $property->idrec }}"
                                                                name="village" required x-model="propertyData.village"
                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                placeholder="Masukkan kelurahan">
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <label for="postal_code_edit_{{ $property->idrec }}"
                                                                class="block text-sm font-semibold text-gray-700 mb-2">
                                                                Kode Pos
                                                            </label>
                                                            <input type="text"
                                                                id="postal_code_edit_{{ $property->idrec }}"
                                                                name="postal_code" x-model="propertyData.postal_code"
                                                                class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                placeholder="Masukkan kode pos">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Step 3 - Facilities -->
                                            <div x-show="editStep === 3"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                                <div class="space-y-6">
                                                    <!-- Combined Facilities (Amenities + Features) -->
                                                    <div x-data="{ facilities: ['High-speed WiFi', 'Parking', 'Swimming Pool', 'Gym', 'Restaurant', '24/7 Security', 'Concierge', 'Laundry Service', 'Room Service'] }">
                                                        <h3
                                                            class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
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
                                                                    <input
                                                                        :id="'facility-edit-{{ $property->idrec }}-' +
                                                                        index"
                                                                        name="facilities[]" type="checkbox"
                                                                        :value="item" class="sr-only peer"
                                                                        x-model="propertyData.features"
                                                                        :checked="propertyData.features
                                                                            .includes(item)">
                                                                    <label
                                                                        :for="'facility-edit-{{ $property->idrec }}-' +
                                                                        index"
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
                                            <div x-show="editStep === 4"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-x-4"
                                                x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                                <div class="space-y-6">
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                                                            Foto Properti <span class="text-red-500">*</span>
                                                            <span class="text-sm font-normal text-gray-500">
                                                                (Minimal <span x-text="editMinImages"></span>
                                                                foto,
                                                                maksimal <span x-text="editMaxImages"></span>
                                                                foto)
                                                            </span>
                                                        </label>
                                                        <!-- Info about thumbnail -->
                                                        <div
                                                            class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-r-lg">
                                                            <div class="flex items-start">
                                                                <div class="flex-shrink-0">
                                                                    <svg class="h-5 w-5 text-blue-500" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                        </path>
                                                                    </svg>
                                                                </div>
                                                                <div class="ml-3">
                                                                    <p class="text-sm text-blue-700">
                                                                        <span class="font-semibold">Perhatian:</span>
                                                                        Foto
                                                                        pertama yang Anda upload akan
                                                                        menjadi <span class="font-bold">thumbnail
                                                                            utama</span> iklan
                                                                        properti ini. Pastikan foto
                                                                        pertama adalah yang terbaik!
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Upload Area -->
                                                        <div x-show="editCanUploadMore" @drop="handleEditDrop($event)"
                                                            @dragover.prevent @dragenter.prevent
                                                            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200 cursor-pointer"
                                                            :class="{ 'border-blue-400 bg-blue-50': editCanUploadMore }">
                                                            <div class="space-y-2">
                                                                <svg class="w-12 h-12 mx-auto text-gray-400"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <div class="flex text-sm text-gray-600 justify-center">
                                                                    <label
                                                                        for="edit_property_images_{{ $property->idrec }}"
                                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                                        <span>Upload foto</span>
                                                                        <input
                                                                            id="edit_property_images_{{ $property->idrec }}"
                                                                            name="edit_property_images[]"
                                                                            type="file" multiple accept="image/*"
                                                                            @change="handleEditFileSelect($event)"
                                                                            class="sr-only">
                                                                    </label>
                                                                    <p class="pl-1">atau drag and
                                                                        drop</p>
                                                                </div>
                                                                <p class="text-xs text-gray-500">PNG,
                                                                    JPG, JPEG (maks. 5MB per file)</p>
                                                                <p class="text-xs"
                                                                    :class="{
                                                                        'text-red-600': editRemainingSlots <=
                                                                            0,
                                                                        'text-yellow-600': editRemainingSlots >
                                                                            0 && editRemainingSlots < 3,
                                                                        'text-blue-600': editRemainingSlots >=
                                                                            3
                                                                    }"
                                                                    x-text="editImageUploadStatus.message">
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <!-- Full Upload Message -->
                                                        <div x-show="!editCanUploadMore"
                                                            class="border-2 border-green-300 rounded-lg p-8 text-center bg-green-50">
                                                            <div class="space-y-2">
                                                                <svg class="w-12 h-12 mx-auto text-green-500"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                <p class="text-sm text-green-600 font-medium">
                                                                    <span x-text="editMaxImages"></span>
                                                                    foto telah diupload!
                                                                </p>
                                                                <p class="text-xs text-green-500">
                                                                    Maksimal upload foto tercapai</p>
                                                            </div>
                                                        </div>

                                                        <!-- Image Preview Grid -->
                                                        <div x-show="propertyData.existingImages.filter(img => !img.markedForDeletion).length > 0 || editImages.length > 0"
                                                            class="mt-2 grid grid-cols-5 gap-1"
                                                            x-transition:enter="transition ease-out duration-300"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100">

                                                            <!-- Existing Images -->
                                                            <template
                                                                x-for="(image, index) in propertyData.existingImages"
                                                                :key="'existing-' + index">
                                                                <div class="relative group"
                                                                    x-show="!image.markedForDeletion">
                                                                    <!-- Image Preview -->
                                                                    <div
                                                                        class="aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-400 transition-colors duration-200 relative">
                                                                        <img :src="image.url"
                                                                            :alt="'Existing Image ' + (index +
                                                                                1)"
                                                                            class="w-full h-full object-cover">
                                                                        <div
                                                                            class="absolute bottom-1 left-1 bg-blue-600 text-white text-[8px] px-1 py-0.5 rounded-full font-medium">
                                                                            <span x-text="index + 1"></span>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="w-full text-xs px-2 py-1 bg-transparent border-0 focus:outline-none focus:ring-0">
                                                                        <p class="text-[8px] text-gray-600 truncate"
                                                                            x-text="image.name"></p>
                                                                    </div>

                                                                    <!-- Remove Button -->
                                                                    <button type="button"
                                                                    @click.prevent.stop="removeEditExistingImage(index)"
                                                                        class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[8px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                        <svg class="w-2 h-2" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>

                                                                    <!-- Hidden Image ID -->
                                                                    <input type="hidden" name="existing_images[]"
                                                                        :value="image.id">
                                                                </div>
                                                            </template>

                                                            <!-- New Images -->
                                                            <template x-for="(image, index) in editImages"
                                                                :key="'new-' + index">
                                                                <div class="relative group">
                                                                    <div
                                                                        class="aspect-square bg-gray-100 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-400 transition-colors duration-200 relative">
                                                                        <img :src="image.url"
                                                                            :alt="'New Image ' + (index + 1)"
                                                                            class="w-full h-full object-cover">
                                                                        <div
                                                                            class="absolute bottom-1 left-1 bg-blue-600 text-white text-[8px] px-1 py-0.5 rounded-full font-medium">
                                                                            <span
                                                                                x-text="propertyData.existingImages.filter(img => !img.markedForDeletion).length + index + 1"></span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Remove Button -->
                                                                    <button @click="removeEditImage(index)"
                                                                        class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[8px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                        <svg class="w-2 h-2" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>

                                                                    <!-- File Name (Optional) -->
                                                                    <div
                                                                        class="w-full text-xs px-2 py-1 bg-transparent border-0 focus:outline-none focus:ring-0">
                                                                        <p class="text-[8px] text-gray-600 truncate"
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
                                                                    x-text="`${propertyData.existingImages.filter(img => !img.markedForDeletion).length + editImages.length}/${editMaxImages} foto`"></span>
                                                            </div>
                                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                                <div class="h-2.5 rounded-full transition-all duration-300"
                                                                    :style="`width: ${editUploadProgress.percentage}%`"
                                                                    :class="{
                                                                        'bg-red-500': editUploadProgress
                                                                            .status === 'danger',
                                                                        'bg-yellow-500': editUploadProgress
                                                                            .status === 'warning',
                                                                        'bg-green-500': editUploadProgress
                                                                            .status === 'success'
                                                                    }">
                                                                </div>
                                                            </div>
                                                            <p class="text-sm mt-1"
                                                                :class="editImageUploadStatus.class"
                                                                x-text="editImageUploadStatus.message">
                                                            </p>
                                                        </div>

                                                        <!-- Validation Messages -->
                                                        <div x-show="(propertyData.existingImages.filter(img => !img.markedForDeletion).length + editImages.length) < editMinImages"
                                                            class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                            <p class="text-red-600 text-sm">
                                                                <span class="font-medium">Perhatian:</span>
                                                                Anda harus mengupload minimal <span
                                                                    x-text="editMinImages"></span>
                                                                foto.
                                                            </p>
                                                        </div>

                                                        <div x-show="(propertyData.existingImages.filter(img => !img.markedForDeletion).length + editImages.length) >= editMinImages"
                                                            class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                            <p class="text-green-600 text-sm">
                                                                <span class="font-medium">Sempurna!</span>
                                                                Foto sudah memenuhi syarat minimal.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Form Actions -->
                                            <div class="mt-6 flex justify-end">
                                                <div>
                                                    <button type="button" x-show="editStep > 1" @click="editStep--"
                                                        class="px-6 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                        <svg class="w-4 h-4 inline mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7">
                                                            </path>
                                                        </svg>
                                                        Sebelumnya
                                                    </button>
                                                    <button type="button" x-show="editStep < 4"
                                                        @click="validateEditStep(editStep) && editStep++"
                                                        class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                        Selanjutnya
                                                        <svg class="w-4 h-4 inline ml-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M9 5l7 7-7 7">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                    <button type="submit" x-show="editStep === 4"
                                                        class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                                        <svg class="w-4 h-4 inline mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
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
        @endforeach
    </tbody>
</table>
