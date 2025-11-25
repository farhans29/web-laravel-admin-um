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
                            @php
                                $periode = is_array($room->periode)
                                    ? $room->periode
                                    : json_decode($room->periode, true);

                                $isDaily = $periode['daily'] ?? false;
                                $isMonthly = $periode['monthly'] ?? false;
                            @endphp

                            <div class="flex flex-col items-center space-y-2">
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
                                <!-- View Room Button -->
                                @include('pages.Properties.m-Rooms.components.room-detail-modal', [
                                    'room' => $room,
                                ])

                                <!-- Edit Room Modal -->
                                @include('pages.Properties.m-Rooms.components.room-edit-modal', [
                                    'room' => $room,
                                ])

                                @if ($isDaily)
                                    <!-- Edit Price (Calendar) -->
                                    @include('pages.Properties.m-Rooms.components.edit-price-daily-modal', [
                                        'room' => $room,
                                    ])
                                @endif

                                <!-- Delete (Bin) -->
                                <button title="Delete"
                                    class="p-2 flex items-center justify-center text-red-600 hover:text-red-900 transition-colors duration-200 rounded-full hover:bg-red-50"
                                    onclick="deleteRoom({{ $room->idrec }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
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
