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
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ditambahkan Oleh</th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status</th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Periode</th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="rooms-table-body">
                @forelse ($rooms as $room)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $room->property_name ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $room->property->subdistrict ?? '-' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                            <div class="text-sm font-medium text-gray-900">{{ $room->name }}</div>
                            <div class="text-sm text-gray-400">Number:{{ $room->no }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                            @if ($room->created_at)
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($room->created_at)->format('Y M d') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($room->created_at)->format('H:i') }}</div>
                            @else
                                <div>-</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                            @if ($room->updated_at)
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($room->updated_at)->format('Y M d') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($room->updated_at)->format('H:i') }}</div>
                            @else
                                <div>-</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $room->creator->username }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer" data-id="{{ $room->idrec }}"
                                    {{ $room->status ? 'checked' : '' }} onchange="toggleStatus(this)">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900">
                                    {{ $room->status ? 'Active' : 'Inactive' }}
                                </span>
                            </label>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center space-y-2">
                                @php
                                    $periode = [];
                                    if (isset($room->periode) && is_string($room->periode) && !empty($room->periode)) {
                                        try {
                                            $decoded = json_decode($room->periode, true);

                                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                $periode = $decoded;
                                            }
                                        } catch (Exception $e) {
                                            $periode = [];
                                        }
                                    }
                                    $isDaily = isset($periode['daily']) && $periode['daily'] === true;
                                    $isMonthly = isset($periode['monthly']) && $periode['monthly'] === true;
                                @endphp

                                @if ($isDaily && $isMonthly)
                                    <div class="flex items-center justify-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-400" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Daily
                                        </span>
                                        <span
                                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor"
                                                viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3" />
                                            </svg>
                                            Monthly
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">Both options available</span>
                                @elseif ($isDaily)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-blue-400" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Daily Only
                                    </span>
                                @elseif ($isMonthly)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Monthly Only
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor"
                                            viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Not Set
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2 justify-center">
                                <!-- View -->
                                <div x-data="modalViewRoom()" class="relative group">
                                    @php
                                        $roomImages = [];
                                        foreach ($room->roomImages as $image) {
                                            if (!empty($image->image)) {
                                                $roomImages[] = 'data:image/jpeg;base64,' . $image->image;
                                            }
                                        }
                                        $facilities = json_encode($room->facility, JSON_HEX_APOS | JSON_HEX_QUOT);
                                    @endphp
                                    <button
                                        class="p-2 text-blue-500 hover:text-blue-700 transition-colors duration-200 rounded-full hover:bg-blue-50"
                                        type="button"
                                        @click.prevent='openModal({
                                                        name: @json($room->name),
                                                        number: @json($room->no),
                                                        size: @json($room->size),
                                                        bed: @json($room->bed_type),
                                                        capacity: @json($room->capacity),
                                                        description: @json($room->descriptions),
                                                        created_at: "{{ \Carbon\Carbon::parse($room->created_at)->format('Y-m-d H:i') }}",
                                                        updated_at: "{{ $room->updated_at ? \Carbon\Carbon::parse($room->updated_at)->format('Y-m-d H:i') : '-' }}",
                                                        creator: "{{ $room->creator->username ?? 'Unknown' }}",
                                                        status: "{{ $room->status ? 'Active' : 'Inactive' }}",
                                                        images: {!! json_encode($roomImages) !!},
                                                        daily_price: "{{ number_format($room->price_original_daily, 0, ',', '.') }}",
                                                        monthly_price: "{{ number_format($room->price_original_monthly, 0, ',', '.') }}",
                                                        facilities: {!! $facilities !!},
                                                        property_name: @json($room->property_name ?? 'Unknown Property')
                                                    })'
                                        aria-controls="room-detail-modal" title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
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
                                    <div id="room-detail-modal"
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
                                                        x-text="selectedRoom.name"></h3>
                                                    <p class="text-gray-600">
                                                        <span x-text="'Room No: ' + selectedRoom.number"></span>
                                                        •
                                                        <span x-text="selectedRoom.property_name"></span>
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
                                                <!-- Room image slider -->
                                                <div class="relative h-72 overflow-hidden bg-gray-200">
                                                    <!-- Images -->
                                                    <div class="flex h-full transition-transform duration-300 ease-in-out"
                                                        :style="'transform: translateX(-' + (selectedRoom
                                                            .currentImageIndex * 100) + '%)'">
                                                        <template x-for="(image, index) in selectedRoom.images"
                                                            :key="index">
                                                            <img :src="image" alt="Room Image"
                                                                class="w-full h-full object-cover object-center flex-shrink-0">
                                                        </template>
                                                    </div>

                                                    <!-- Navigation arrows -->
                                                    <button x-show="selectedRoom.images.length > 1"
                                                        @click="selectedRoom.currentImageIndex = (selectedRoom.currentImageIndex - 1 + selectedRoom.images.length) % selectedRoom.images.length"
                                                        class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full p-2 shadow-md">
                                                        <svg class="w-6 h-6 text-gray-800" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 19l-7-7 7-7" />
                                                        </svg>
                                                    </button>
                                                    <button x-show="selectedRoom.images.length > 1"
                                                        @click="selectedRoom.currentImageIndex = (selectedRoom.currentImageIndex + 1) % selectedRoom.images.length"
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
                                                            :class="selectedRoom && selectedRoom
                                                                .status === 'Active' ?
                                                                'text-green-600 font-semibold' :
                                                                'text-red-600 font-semibold'"
                                                            class="text-sm flex items-center">
                                                            <span class="w-2.5 h-2.5 rounded-full mr-2 block"
                                                                :class="selectedRoom && selectedRoom
                                                                    .status === 'Active' ?
                                                                    'bg-green-500' : 'bg-red-500'"></span>
                                                            <span
                                                                x-text="selectedRoom && selectedRoom.status ? selectedRoom.status : ''"></span>
                                                        </span>
                                                    </div>

                                                    <!-- Image indicators -->
                                                    <div x-show="selectedRoom.images.length > 1"
                                                        class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                                                        <template x-for="(image, index) in selectedRoom.images"
                                                            :key="index">
                                                            <button @click="selectedRoom.currentImageIndex = index"
                                                                class="w-3 h-3 rounded-full transition-all"
                                                                :class="selectedRoom.currentImageIndex ===
                                                                    index ? 'bg-white w-6' :
                                                                    'bg-white/50'"></button>
                                                        </template>
                                                    </div>
                                                </div>

                                                <div class="p-6 space-y-8">
                                                    <!-- Room Details Grid -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                        <div class="space-y-4">
                                                            <div>
                                                                <h4
                                                                    class="text-lg font-bold text-gray-900 mb-3 text-left">
                                                                    Room Specifications</h4>
                                                                <div class="space-y-3">
                                                                    <div class="flex items-center">
                                                                        <svg class="w-5 h-5 text-blue-500 mr-3"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                                        </svg>
                                                                        <span class="text-gray-700">
                                                                            <span class="font-medium">Property:</span>
                                                                            <span
                                                                                x-text="selectedRoom.property_name"></span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <svg class="w-5 h-5 text-blue-500 mr-3"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 6h16M4 12h16M4 18h16" />
                                                                        </svg>
                                                                        <span class="text-gray-700">
                                                                            <span class="font-medium">Room
                                                                                Number:</span>
                                                                            <span x-text="selectedRoom.number"></span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <svg class="w-5 h-5 text-blue-500 mr-3"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        <span class="text-gray-700">
                                                                            <span class="font-medium">Size:</span>
                                                                            <span
                                                                                x-text="selectedRoom.size + ' m²'"></span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <svg class="w-5 h-5 text-blue-500 mr-3"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                                                        </svg>
                                                                        <span class="text-gray-700">
                                                                            <span class="font-medium">Bed
                                                                                Type:</span>
                                                                            <span x-text="selectedRoom.bed"></span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex items-center">
                                                                        <svg class="w-5 h-5 text-blue-500 mr-3"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                        </svg>
                                                                        <span class="text-gray-700">
                                                                            <span class="font-medium">Capacity:</span>
                                                                            <span
                                                                                x-text="selectedRoom.capacity + ' person(s)'"></span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Pricing -->
                                                            <div>
                                                                <h4
                                                                    class="text-lg font-bold text-gray-900 mb-3 text-left">
                                                                    Pricing</h4>
                                                                <div class="space-y-3">
                                                                    <template
                                                                        x-if="selectedRoom.daily_price && selectedRoom.daily_price !== '0'">
                                                                        <div
                                                                            class="flex items-center justify-between bg-blue-50 p-3 rounded-lg">
                                                                            <div class="flex items-center">
                                                                                <svg class="w-5 h-5 text-blue-600 mr-3"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                                </svg>
                                                                                <span
                                                                                    class="text-gray-700 font-medium">Daily
                                                                                    Rate:</span>
                                                                            </div>
                                                                            <span class="text-blue-600 font-bold"
                                                                                x-text="'Rp ' + selectedRoom.daily_price"></span>
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        x-if="selectedRoom.monthly_price && selectedRoom.monthly_price !== '0'">
                                                                        <div
                                                                            class="flex items-center justify-between bg-green-50 p-3 rounded-lg">
                                                                            <div class="flex items-center">
                                                                                <svg class="w-5 h-5 text-green-600 mr-3"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                </svg>
                                                                                <span
                                                                                    class="text-gray-700 font-medium">Monthly
                                                                                    Rate:</span>
                                                                            </div>
                                                                            <span class="text-green-600 font-bold"
                                                                                x-text="'Rp ' + selectedRoom.monthly_price"></span>
                                                                        </div>
                                                                    </template>
                                                                    <template
                                                                        x-if="!selectedRoom.daily_price && !selectedRoom.monthly_price || (selectedRoom.daily_price === '0' && selectedRoom.monthly_price === '0')">
                                                                        <div class="text-center py-3 text-gray-500">
                                                                            No pricing information
                                                                            available
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Description -->
                                                        <div>
                                                            <h4 class="text-lg font-bold text-gray-900 mb-3">
                                                                Description</h4>
                                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                                <p class="text-gray-700 leading-relaxed whitespace-pre-line"
                                                                    x-text="selectedRoom.description">
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Facilities room Section -->
                                                    <div x-show="selectedRoom.facilities && selectedRoom.facilities.length > 0"
                                                        class="space-y-4">
                                                        <div class="flex items-center space-x-2 mb-4">
                                                            <h4 class="text-lg font-bold text-gray-900">Room Facilities
                                                            </h4>
                                                        </div>
                                                        <div class="flex flex-wrap gap-2">
                                                            <template x-for="facilityId in selectedRoom.facilities"
                                                                :key="facilityId">
                                                                <span x-text="getFacilityName(facilityId, 'room')"
                                                                    class="px-2.5 py-1 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                                </span>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <!-- Meta Information -->
                                                    <div
                                                        class="grid grid-cols-3 grid-rows-1 gap-4 bg-gray-50 p-6 rounded-xl">
                                                        <!-- Added By -->
                                                        <div
                                                            class="flex flex-col items-center justify-center text-center space-y-2">
                                                            <div
                                                                class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                <svg class="w-4 h-4 text-blue-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                <p>Added By</p>
                                                            </div>
                                                            <p class="text-gray-800 font-medium"
                                                                x-text="selectedRoom.creator"></p>
                                                        </div>

                                                        <!-- Added -->
                                                        <div
                                                            class="flex flex-col items-center justify-center text-center space-y-2">
                                                            <div
                                                                class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                <svg class="w-4 h-4 text-blue-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <p>Added</p>
                                                            </div>
                                                            <p class="text-gray-800 font-medium"
                                                                x-text="selectedRoom.created_at"></p>
                                                        </div>

                                                        <!-- Last Updated -->
                                                        <div
                                                            class="flex flex-col items-center justify-center text-center space-y-2">
                                                            <div
                                                                class="flex items-center space-x-1 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                <svg class="w-4 h-4 text-green-500" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                                <p>Last Updated</p>
                                                            </div>
                                                            <p class="text-gray-800 font-medium"
                                                                x-text="selectedRoom.updated_at"></p>
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

                                <!-- Edit Room Modal -->
                                <div x-data="modalRoomEdit({{ $room }})" class="relative group">
                                    @php
                                        $roomImages = $room->roomImages
                                            ->map(function ($image) {
                                                return [
                                                    'id' => $image->idrec,
                                                    'url' => 'data:image/jpeg;base64,' . $image->image,
                                                    'caption' => $image->caption,
                                                    'name' => 'Image_' . $image->idrec . '.jpg',
                                                    'is_thumbnail' => $image->thumbnail == 1,
                                                ];
                                            })
                                            ->toJson();
                                        $facilities = json_encode($room->facility, JSON_HEX_APOS | JSON_HEX_QUOT);
                                    @endphp

                                    <button
                                        class="p-2 flex items-center justify-center text-amber-600 hover:text-amber-900 transition-colors duration-200 rounded-full hover:bg-amber-50"
                                        type="button"
                                        @click.prevent='openModal({
                                                            id: {{ $room->idrec }},
                                                            property_id: {{ $room->property_id }},
                                                            name: @json($room->name),
                                                            number: @json($room->no),
                                                            size: @json($room->size),
                                                            bed: @json($room->bed_type),
                                                            capacity: @json($room->capacity),
                                                            description: @json($room->descriptions),
                                                            daily_price: "{{ $room->price_original_daily }}",
                                                            monthly_price: "{{ $room->price_original_monthly }}",
                                                            facilities: {!! $facilities !!},
                                                            existingImages: {!! $roomImages !!}
                                                        })'
                                        aria-controls="room-edit-modal-{{ $room->idrec }}" title="Edit Room">
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
                                    <div id="room-edit-modal-{{ $room->idrec }}"
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
                                                    <div class="font-bold text-xl text-gray-800">Edit Kamar</div>
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
                                                            <span class="text-sm font-semibold"
                                                                x-show="editStep < 1">1</span>
                                                            <svg x-show="editStep >= 1" class="w-5 h-5"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <p class="font-medium transition-colors duration-300"
                                                                :class="editStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
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
                                                            <span class="text-sm font-semibold"
                                                                x-show="editStep < 2">2</span>
                                                            <svg x-show="editStep >= 2" class="w-5 h-5"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <p class="font-medium transition-colors duration-300"
                                                                :class="editStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
                                                                Harga
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Connector -->
                                                    <div class="w-16 h-0.5 transition-colors duration-300"
                                                        :class="editStep >= 3 ? 'bg-blue-600' : 'bg-gray-300'">
                                                    </div>

                                                    <!-- Step 3 -->
                                                    <div class="flex items-center">
                                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                            :class="editStep >= 3 ?
                                                                'bg-blue-600 border-blue-600 text-white' :
                                                                'border-gray-300 text-gray-500'">
                                                            <span class="text-sm font-semibold"
                                                                x-show="editStep < 3">3</span>
                                                            <svg x-show="editStep >= 3" class="w-5 h-5"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <p class="font-medium transition-colors duration-300"
                                                                :class="editStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
                                                                Fasilitas
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Connector -->
                                                    <div class="w-16 h-0.5 transition-colors duration-300"
                                                        :class="editStep >= 4 ? 'bg-blue-600' : 'bg-gray-300'">
                                                    </div>

                                                    <!-- Step 4 -->
                                                    <div class="flex items-center">
                                                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 transition-all duration-300"
                                                            :class="editStep >= 4 ?
                                                                'bg-blue-600 border-blue-600 text-white' :
                                                                'border-gray-300 text-gray-500'">
                                                            <span class="text-sm font-semibold"
                                                                x-show="editStep < 4">4</span>
                                                            <svg x-show="editStep >= 4" class="w-5 h-5"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <p class="font-medium transition-colors duration-300"
                                                                :class="editStep >= 4 ? 'text-blue-600' : 'text-gray-500'">
                                                                Foto
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal content -->
                                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                                <form id="roomFormEdit-{{ $room->idrec }}" method="POST"
                                                    action="{{ route('rooms.update', $room->idrec) }}"
                                                    enctype="multipart/form-data" @submit.prevent="submitEditForm">
                                                    @csrf
                                                    @method('PUT')

                                                    <!-- Step 1 - Basic Information -->
                                                    <div x-show="editStep === 1"
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 translate-x-4"
                                                        x-transition:enter-end="opacity-100 translate-x-0">
                                                        <div class="space-y-6">
                                                            <!-- Property Selector -->
                                                            <div>
                                                                <label for="edit_property_id_{{ $room->idrec }}"
                                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                                    Properti <span class="text-red-500">*</span>
                                                                </label>
                                                                <input type="text"
                                                                    id="edit_property_id_{{ $room->idrec }}"
                                                                    name="property_name"
                                                                    value="{{ $room->property->name ?? '' }}" readonly
                                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 bg-gray-100 text-gray-700 focus:outline-none transition-all duration-200">

                                                                {{-- Hidden input untuk menyimpan ID-nya ke database --}}
                                                                <input type="hidden" name="property_id"
                                                                    value="{{ $room->property_id }}">
                                                            </div>


                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <div>
                                                                    <label for="edit_room_no_{{ $room->idrec }}"
                                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                                        Nomor Kamar <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text"
                                                                        id="edit_room_no_{{ $room->idrec }}"
                                                                        name="room_no" required
                                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="Masukkan nomor kamar"
                                                                        x-model="roomData.number">
                                                                </div>

                                                                <div>
                                                                    <label for="edit_room_name_{{ $room->idrec }}"
                                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                                        Nama Kamar <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text"
                                                                        id="edit_room_name_{{ $room->idrec }}"
                                                                        name="room_name" required
                                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="Masukkan nama kamar"
                                                                        x-model="roomData.name">
                                                                </div>
                                                            </div>

                                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                <div>
                                                                    <label for="edit_room_size_{{ $room->idrec }}"
                                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                                        Ukuran Kamar (m²) <span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="number"
                                                                        id="edit_room_size_{{ $room->idrec }}"
                                                                        name="room_size" required
                                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="Masukkan ukuran kamar"
                                                                        x-model="roomData.size">
                                                                </div>

                                                                <div>
                                                                    <label for="edit_room_bed_{{ $room->idrec }}"
                                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                                        Jenis Kasur <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <select id="edit_room_bed_{{ $room->idrec }}"
                                                                        name="room_bed" required
                                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        x-model="roomData.bed">
                                                                        <option value="">Pilih Jenis Kasur
                                                                        </option>
                                                                        <option value="Single">Single</option>
                                                                        <option value="Double">Double</option>
                                                                        <option value="King">King</option>
                                                                        <option value="Queen">Queen</option>
                                                                        <option value="Twin">Twin</option>
                                                                    </select>
                                                                </div>

                                                                <div>
                                                                    <label
                                                                        for="edit_room_capacity_{{ $room->idrec }}"
                                                                        class="block text-sm font-semibold text-gray-700 mb-2">
                                                                        Kapasitas (Pax) <span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="number"
                                                                        id="edit_room_capacity_{{ $room->idrec }}"
                                                                        name="room_capacity" required
                                                                        class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="Masukkan kapasitas kamar"
                                                                        x-model="roomData.capacity">
                                                                </div>
                                                            </div>

                                                            <div>
                                                                <label for="edit_description_{{ $room->idrec }}"
                                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                                    Deskripsi Kamar <span class="text-red-500">*</span>
                                                                </label>
                                                                <textarea id="edit_description_{{ $room->idrec }}" name="description" rows="4" required
                                                                    class="w-full border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                    placeholder="Deskripsikan kamar Anda..." x-model="roomData.description"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Step 2 - Pricing -->
                                                    <div x-show="editStep === 2"
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 translate-x-4"
                                                        x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                                                        <div class="space-y-6">
                                                            <div class="mb-4">
                                                                <h3 class="text-md font-semibold text-gray-700 mb-2">
                                                                    Jenis Harga</h3>
                                                                <div class="flex space-x-6">
                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" value="daily"
                                                                            x-model="priceTypes"
                                                                            class="form-checkbox text-blue-600">
                                                                        <span class="ml-2">Harian</span>
                                                                    </label>
                                                                    <label class="inline-flex items-center">
                                                                        <input type="checkbox" value="monthly"
                                                                            x-model="priceTypes"
                                                                            class="form-checkbox text-blue-600">
                                                                        <span class="ml-2">Bulanan</span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div x-show="priceTypes.includes('daily')" x-transition>
                                                                <label
                                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                                    Harga Harian <span class="text-red-500">*</span>
                                                                </label>
                                                                <div class="relative">
                                                                    <div
                                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <span class="text-gray-500">Rp</span>
                                                                    </div>
                                                                    <input type="text" x-ref="dailyPriceInput"
                                                                        class="w-full pl-10 border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="Masukkan harga harian"
                                                                        x-model="roomData.daily_price"
                                                                        @input="formatPriceInput($event, 'daily_price')">
                                                                    <input type="hidden" name="daily_price"
                                                                        x-model="dailyPrice">
                                                                </div>
                                                            </div>

                                                            <div x-show="priceTypes.includes('monthly')" x-transition
                                                                class="mt-4">
                                                                <label
                                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                                    Harga Bulanan <span class="text-red-500">*</span>
                                                                </label>
                                                                <div class="relative">
                                                                    <div
                                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                        <span class="text-gray-500">Rp</span>
                                                                    </div>
                                                                    <input type="text" x-ref="monthlyPriceInput"
                                                                        class="w-full pl-10 border-2 border-gray-200 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                                                        placeholder="Masukkan harga bulanan"
                                                                        x-model="roomData.monthly_price"
                                                                        @input="formatPriceInput($event, 'monthly_price')">
                                                                    <input type="hidden" name="monthly_price"
                                                                        x-model="monthlyPrice">
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mt-4">
                                                                <div class="flex items-start">
                                                                    <div class="flex-shrink-0">
                                                                        <svg class="h-5 w-5 text-blue-500"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="ml-3">
                                                                        <p class="text-sm text-blue-700">
                                                                            Anda bisa memilih salah satu atau kedua
                                                                            jenis harga. Pastikan
                                                                            mengisi harga yang sesuai dengan jenis
                                                                            yang
                                                                            dipilih.
                                                                        </p>
                                                                    </div>
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
                                                            <div>
                                                                <h3
                                                                    class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                    <svg class="w-5 h-5 mr-2 text-blue-600"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    Fasilitas Kamar
                                                                </h3>
                                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                                                    @foreach ($facilities as $facility)
                                                                        <div class="relative">
                                                                            <input
                                                                                id="edit_facility-{{ $room->idrec }}-{{ $facility->idrec }}"
                                                                                name="facilities[]" type="checkbox"
                                                                                value="{{ $facility->idrec }}"
                                                                                class="sr-only peer"
                                                                                x-model="roomData.facilities"
                                                                                :checked="roomData.facilities.includes(
                                                                                    '{{ $facility->idrec }}')">
                                                                            <label
                                                                                for="edit_facility-{{ $room->idrec }}-{{ $facility->idrec }}"
                                                                                class="flex items-center p-3 text-sm font-medium text-gray-700 bg-white border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-600 transition-all duration-200">
                                                                                <span>{{ $facility->facility }}</span>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
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
                                                                <!-- Hidden field to store thumbnail index -->
                                                                <input type="hidden" name="thumbnail_index"
                                                                    x-model="thumbnailIndex">

                                                                <label
                                                                    class="block text-sm font-semibold text-gray-700 mb-3">
                                                                    Foto Kamar <span class="text-red-500">*</span>
                                                                    <span class="text-sm font-normal text-gray-500">
                                                                        (Minimal <span x-text="editMinImages"></span>
                                                                        foto,
                                                                        maksimal <span x-text="editMaxImages"></span>
                                                                        foto)
                                                                    </span>
                                                                </label>

                                                                <!-- Thumbnail Preview Section -->
                                                                <div class="mb-6">
                                                                    <h4
                                                                        class="text-sm font-semibold text-gray-700 mb-2">
                                                                        Thumbnail Saat Ini <span
                                                                            class="text-red-500">*</span>
                                                                        <span
                                                                            class="text-xs font-normal text-gray-500">(Foto
                                                                            utama kamar)</span>
                                                                    </h4>

                                                                    <div class="flex items-center space-x-4">
                                                                        <!-- Thumbnail Preview -->
                                                                        <div
                                                                            class="w-32 h-32 bg-gray-100 rounded-lg border-2 border-gray-300 overflow-hidden relative flex items-center justify-center">
                                                                            <template x-if="getCurrentThumbnail()">
                                                                                <img :src="getCurrentThumbnail().url"
                                                                                    class="w-full h-full object-cover"
                                                                                    alt="Current Thumbnail">
                                                                            </template>
                                                                            <div class="absolute inset-0 flex items-center justify-center text-gray-400"
                                                                                x-show="!getCurrentThumbnail()">
                                                                                <svg class="w-10 h-10" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                                </svg>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Thumbnail Selection Instructions -->
                                                                        <div class="flex-1">
                                                                            <p class="text-sm text-gray-600 mb-2">
                                                                                <span x-show="thumbnailIndex === null"
                                                                                    class="font-medium text-red-500">
                                                                                    Belum ada thumbnail dipilih!
                                                                                </span>
                                                                                <span x-show="thumbnailIndex !== null"
                                                                                    class="font-medium text-green-600">
                                                                                    Thumbnail sudah dipilih.
                                                                                </span>
                                                                                Klik salah satu foto di bawah untuk
                                                                                memilih sebagai thumbnail.
                                                                            </p>
                                                                            <p class="text-xs text-gray-500">
                                                                                Pastikan memilih foto terbaik sebagai
                                                                                thumbnail karena ini akan menjadi gambar
                                                                                utama properti Anda.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Upload Area -->
                                                                <div x-show="editCanUploadMore"
                                                                    @dragover="handleEditDragOver($event)"
                                                                    @dragleave="handleEditDragLeave($event)"
                                                                    @drop="handleEditDrop($event)"
                                                                    class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200 cursor-pointer"
                                                                    :class="{ 'border-blue-400 bg-blue-50': editCanUploadMore }">
                                                                    <div class="space-y-2">
                                                                        <svg class="w-12 h-12 mx-auto text-gray-400"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                        <div
                                                                            class="flex text-sm text-gray-600 justify-center">
                                                                            <label
                                                                                for="edit_room_images_{{ $room->idrec }}"
                                                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                                                <span>Upload foto</span>
                                                                                <input
                                                                                    id="edit_room_images_{{ $room->idrec }}"
                                                                                    name="edit_room_images[]"
                                                                                    type="file" multiple
                                                                                    accept="image/*"
                                                                                    @change="handleEditFileSelect($event)"
                                                                                    class="sr-only">
                                                                            </label>
                                                                            <p class="pl-1">atau drag and drop</p>
                                                                        </div>
                                                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG
                                                                            (maks. 5MB per file)</p>
                                                                        <p class="text-xs text-blue-600"
                                                                            x-text="`Dapat upload ${editRemainingSlots} foto lagi`">
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
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2" d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                        <p class="text-sm text-green-600 font-medium">
                                                                            <span x-text="editMaxImages"></span> foto
                                                                            telah diupload!
                                                                        </p>
                                                                        <p class="text-xs text-green-500">Maksimal
                                                                            upload foto tercapai</p>
                                                                    </div>
                                                                </div>

                                                                <!-- Image Preview Grid -->
                                                                <div x-show="getAllImages().length > 0"
                                                                    class="mt-4">
                                                                    <h4
                                                                        class="text-sm font-semibold text-gray-700 mb-2">
                                                                        Foto Terupload</h4>
                                                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3"
                                                                        x-transition:enter="transition ease-out duration-300"
                                                                        x-transition:enter-start="opacity-0 scale-95"
                                                                        x-transition:enter-end="opacity-100 scale-100">

                                                                        <!-- Existing Images -->
                                                                        <template
                                                                            x-for="(image, index) in roomData.existingImages"
                                                                            :key="'existing-' + index">
                                                                            <div class="relative group"
                                                                                x-show="!image.markedForDeletion"
                                                                                @click="setThumbnail(index)">
                                                                                <!-- Image Container -->
                                                                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                                                                    :class="thumbnailIndex === index || image
                                                                                        .is_thumbnail ?
                                                                                        'border-blue-600 ring-2 ring-blue-400' :
                                                                                        'border-gray-200 hover:border-blue-400'">
                                                                                    <img :src="image.url"
                                                                                        class="w-full h-full object-cover"
                                                                                        :alt="'Existing Image ' + (index + 1)">

                                                                                    <!-- Thumbnail Badge -->
                                                                                    <div x-show="thumbnailIndex === index || image.is_thumbnail"
                                                                                        class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                                                        Thumbnail
                                                                                    </div>

                                                                                    <!-- Image Number -->
                                                                                    <div
                                                                                        class="absolute bottom-1 left-1 bg-gray-800 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                                                        <span
                                                                                            x-text="index + 1"></span>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Remove Button -->
                                                                                <button type="button"
                                                                                    @click.stop="removeEditExistingImage(index)"
                                                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                                    <svg class="w-3 h-3"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                                    </svg>
                                                                                </button>

                                                                                <!-- Hidden Image ID -->
                                                                                <input type="hidden"
                                                                                    name="existing_images[]"
                                                                                    :value="image.id">
                                                                            </div>
                                                                        </template>

                                                                        <!-- New Images -->
                                                                        <template x-for="(image, index) in editImages"
                                                                            :key="'new-' + index">
                                                                            <div class="relative group"
                                                                                @click="setThumbnail(roomData.existingImages.filter(img => !img.markedForDeletion).length + index)">
                                                                                <!-- Image Container -->
                                                                                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                                                                    :class="thumbnailIndex === (roomData
                                                                                            .existingImages.filter(
                                                                                                img => !img
                                                                                                .markedForDeletion)
                                                                                            .length + index) ?
                                                                                        'border-blue-600 ring-2 ring-blue-400' :
                                                                                        'border-gray-200 hover:border-blue-400'">
                                                                                    <img :src="image.url"
                                                                                        class="w-full h-full object-cover"
                                                                                        :alt="'New Image ' + (index + 1)">

                                                                                    <!-- Thumbnail Badge -->
                                                                                    <div x-show="thumbnailIndex === (roomData.existingImages.filter(img => !img.markedForDeletion).length + index)"
                                                                                        class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                                                        Thumbnail
                                                                                    </div>

                                                                                    <!-- Image Number -->
                                                                                    <div
                                                                                        class="absolute bottom-1 left-1 bg-gray-800 text-white text-xs px-1.5 py-0.5 rounded-full font-medium">
                                                                                        <span
                                                                                            x-text="roomData.existingImages.filter(img => !img.markedForDeletion).length + index + 1"></span>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Remove Button -->
                                                                                <button
                                                                                    @click.stop="removeEditImage(index)"
                                                                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] hover:bg-red-600 transition-colors duration-200 opacity-0 group-hover:opacity-100">
                                                                                    <svg class="w-3 h-3"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                                    </svg>
                                                                                </button>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                                <!-- Progress Indicator -->
                                                                <div class="mt-4">
                                                                    <div
                                                                        class="flex justify-between text-sm text-gray-600 mb-2">
                                                                        <span>Progress Upload</span>
                                                                        <span
                                                                            x-text="`${getAllImages().length}/${editMaxImages} foto`"></span>
                                                                    </div>
                                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                                            :style="`width: ${(getAllImages().length / editMaxImages) * 100}%`">
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Validation Messages -->
                                                                <div class="mt-3 space-y-2">
                                                                    <div x-show="getAllImages().length < editMinImages"
                                                                        class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                                                        <p class="text-sm text-red-600">
                                                                            <span class="font-medium">Perhatian:</span>
                                                                            Anda harus mengupload minimal <span
                                                                                x-text="editMinImages"></span> foto.
                                                                        </p>
                                                                    </div>

                                                                    <div x-show="thumbnailIndex === null && getAllImages().length > 0"
                                                                        class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                                        <p class="text-sm text-yellow-600">
                                                                            <span class="font-medium">Perhatian:</span>
                                                                            Anda harus memilih thumbnail untuk
                                                                            melanjutkan.
                                                                        </p>
                                                                    </div>

                                                                    <div x-show="getAllImages().length >= editMinImages && thumbnailIndex !== null"
                                                                        class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                                                        <p class="text-sm text-green-600">
                                                                            <span class="font-medium">Sempurna!</span>
                                                                            Foto sudah memenuhi syarat dan thumbnail
                                                                            telah dipilih.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Form Actions -->
                                                    <div class="mt-6 flex justify-end">
                                                        <div>
                                                            <button type="button" x-show="editStep > 1"
                                                                @click="editStep--"
                                                                class="px-6 py-2 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                                <svg class="w-4 h-4 inline mr-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M15 19l-7-7 7-7"></path>
                                                                </svg>
                                                                Sebelumnya
                                                            </button>
                                                            <button type="button" x-show="editStep < 4"
                                                                @click="validateEditStep(editStep) && editStep++"
                                                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                                Selanjutnya
                                                                <svg class="w-4 h-4 inline ml-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 5l7 7-7 7"></path>
                                                                </svg>
                                                            </button>
                                                            <button type="submit" x-show="editStep === 4"
                                                                class="px-6 py-2 border-2 border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                                                <svg class="w-4 h-4 inline mr-2" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7"></path>
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

                                @if ($isDaily)
                                    <!-- Edit Price (Calendar) -->
                                    <div x-data="priceModal({{ $room->idrec }}, {{ $room->price_original_daily ?? 0 }})">
                                        <!-- Trigger Button -->
                                        <button @click="openModal()"
                                            class="p-2 text-green-600 hover:text-green-900 transition-colors duration-200 rounded-full hover:bg-green-50"
                                            title="Edit Harga">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7V3m8 4V3m-9 8h10m-12 8h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </button>

                                        <!-- Modal -->
                                        <div x-show="isOpen" x-cloak x-transition
                                            class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape="closeModal()">
                                            <!-- Overlay -->
                                            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
                                                x-show="isOpen" x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0">
                                            </div>

                                            <!-- Modal Container -->
                                            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                                                <!-- Modal Panel -->
                                                <div x-show="isOpen" @click.away="closeModal()"
                                                    x-transition:enter="ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                    x-transition:leave="ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                    class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all">

                                                    <!-- Header -->
                                                    <div class="flex items-center justify-between p-6 border-b">
                                                        <h3 class="text-xl font-semibold text-gray-900">
                                                            Manajemen Harga Harian
                                                        </h3>
                                                        <button @click="closeModal()"
                                                            class="text-gray-400 hover:text-gray-500">
                                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Content -->
                                                    <div class="p-6">
                                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                                            <!-- Calendar Section -->
                                                            <div
                                                                class="bg-gray-50 p-4 rounded-lg flex flex-col items-center">
                                                                <div class="mb-4 w-full flex justify-center">
                                                                    <div class="inline-calendar"></div>
                                                                </div>

                                                                <!-- Legend -->
                                                                <div
                                                                    class="flex flex-wrap justify-center gap-4 text-sm">
                                                                    <div class="flex items-center space-x-2">
                                                                        <span
                                                                            class="w-4 h-4 rounded bg-gray-300 border border-gray-400"></span>
                                                                        <span>Belum ada harga</span>
                                                                    </div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <span
                                                                            class="w-4 h-4 rounded bg-blue-500 border border-blue-600"></span>
                                                                        <span>Harga standar</span>
                                                                    </div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <span
                                                                            class="w-4 h-4 rounded bg-red-500 border border-red-600"></span>
                                                                        <span>Harga lebih tinggi</span>
                                                                    </div>
                                                                    <div class="flex items-center space-x-2">
                                                                        <span
                                                                            class="w-4 h-4 rounded bg-green-500 border border-green-600"></span>
                                                                        <span>Harga lebih rendah</span>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <!-- Form Section -->
                                                            <div class="space-y-6">
                                                                <!-- Date Input -->
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Tanggal Terpilih
                                                                    </label>
                                                                    <input type="text" x-model="startDate"
                                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50"
                                                                        readonly>
                                                                </div>

                                                                <!-- Current Price -->
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Harga Saat Ini
                                                                    </label>
                                                                    <input type="text" x-model="formattedDatePrice"
                                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 font-medium"
                                                                        readonly>
                                                                    <p class="mt-1 text-xs text-gray-500">
                                                                        Harga original: <span
                                                                            x-text="formattedBasePrice"
                                                                            class="font-medium"></span>
                                                                    </p>
                                                                </div>

                                                                <!-- New Price -->
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Harga Baru
                                                                    </label>
                                                                    <input type="text" x-ref="setPrice"
                                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                                        placeholder="Masukkan harga baru">
                                                                    <p class="mt-1 text-xs text-gray-500">
                                                                        Kosongkan untuk reset ke harga
                                                                        original
                                                                    </p>
                                                                </div>

                                                                <!-- Actions -->
                                                                <div class="flex justify-end space-x-3 pt-4">
                                                                    <button @click="closeModal()"
                                                                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                                                        Batal
                                                                    </button>
                                                                    <button @click="updatePrice()"
                                                                        :disabled="isLoading"
                                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center space-x-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                                                        <svg x-show="!isLoading"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            class="h-5 w-5" viewBox="0 0 20 20"
                                                                            fill="currentColor">
                                                                            <path fill-rule="evenodd"
                                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                                clip-rule="evenodd" />
                                                                        </svg>
                                                                        <svg x-show="isLoading"
                                                                            class="animate-spin h-5 w-5"
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
                                                                        <span
                                                                            x-text="isLoading ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Delete (Bin) -->
                                <button title="Delete"
                                    class="p-2 flex items-center justify-center text-red-600 hover:text-red-900 transition-colors duration-200 rounded-full hover:bg-red-50"
                                    onclick="deleteRoom({{ $room->idrec }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            No Rooms found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
