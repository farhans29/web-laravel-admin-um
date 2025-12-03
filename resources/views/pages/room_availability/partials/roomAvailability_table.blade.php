<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kamar
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Property
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Tipe & Kapasitas
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Booking Terkait
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if ($room->thumbnail)
                                        <img class="h-10 w-10 rounded-lg object-cover"
                                            src="{{ asset('storage/' . $room->thumbnail->image_path) }}"
                                            alt="{{ $room->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $room->name }}</div>
                                    <div class="text-sm text-gray-500">No. {{ $room->no }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $room->property_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 capitalize">{{ $room->type }}</div>
                            <div class="text-sm text-gray-500">Kapasitas: {{ $room->capacity }} orang</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $room->rental_status == 1 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $room->rental_status == 1 ? 'Booked' : 'Available' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $startDate = request('start_date');
                                $endDate = request('end_date');

                                // Filter bookings using transaction dates for consistency
                                $currentBookings = $room->bookings->filter(function ($booking) use (
                                    $startDate,
                                    $endDate,
                                ) {
                                    // Skip bookings without transaction
                                    if (!$booking->transaction) {
                                        return false;
                                    }

                                    // If no date filter, show all active bookings
                                    if (!$startDate || !$endDate) {
                                        return true;
                                    }

                                    // Check if booking overlaps with date range
                                    $checkIn = $booking->transaction->check_in;
                                    $checkOut = $booking->transaction->check_out;

                                    return !($checkOut < $startDate || $checkIn > $endDate);
                                });
                            @endphp

                            @if ($currentBookings->count() > 0)
                                <!-- View -->
                                <div x-data="modalView()" class="relative group">
                                    <button
                                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-blue-600 hover:bg-blue-50 hover:text-blue-800 transition-all duration-200"
                                        type="button" @click.prevent="openModal({{ $room->idrec }})"
                                        title="View Bookings">
                                        <span class="text-sm font-semibold">View Bookings
                                            ({{ $currentBookings->count() }})
                                        </span>
                                    </button>

                                    <!-- Modal Structure -->
                                    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 transition-opacity"
                                        x-show="modalOpenDetail" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-out duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        aria-hidden="true" x-cloak>
                                    </div>

                                    <!-- Modal Dialog -->
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

                                            <!-- Modal Header -->
                                            <div
                                                class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                                                <div class="text-left">
                                                    <h3 class="text-2xl font-bold text-gray-900 mb-1"
                                                        x-text="selectedProperty.roomName"></h3>
                                                    <p class="text-gray-600">
                                                        Daftar Pengguna Aktif
                                                    </p>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <div class="text-right">
                                                        <p class="text-sm text-gray-600"
                                                            x-text="`Total: ${selectedProperty.totalBookings || 0} booking`">
                                                        </p>
                                                        <div class="flex items-center mt-1" x-show="loading">
                                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-500"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12"
                                                                    r="10" stroke="currentColor" stroke-width="4">
                                                                </circle>
                                                                <path class="opacity-75" fill="currentColor"
                                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                </path>
                                                            </svg>
                                                            <span class="text-xs text-gray-500">Loading...</span>
                                                        </div>
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
                                            </div>

                                            <!-- Modal Content -->
                                            <div class="overflow-y-auto flex-1 p-6">
                                                <!-- Empty State -->
                                                <template
                                                    x-if="!loading && (!selectedProperty.bookings || selectedProperty.bookings.length === 0)">
                                                    <div class="text-center py-12">
                                                        <div class="w-24 h-24 mx-auto mb-4 text-gray-300">
                                                            <svg fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="1"
                                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada
                                                            booking aktif</h3>
                                                        <p class="text-gray-500 max-w-sm mx-auto">
                                                            Tidak ada booking aktif untuk kamar ini pada periode yang
                                                            dipilih.
                                                        </p>
                                                    </div>
                                                </template>

                                                <!-- Loading State -->
                                                <template x-if="loading">
                                                    <div class="flex justify-center items-center py-12">
                                                        <div
                                                            class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500">
                                                        </div>
                                                    </div>
                                                </template>

                                                <!-- Bookings List -->
                                                <template
                                                    x-if="!loading && selectedProperty.bookings && selectedProperty.bookings.length > 0">
                                                    <div class="grid gap-4 md:grid-cols-2">
                                                        <template x-for="booking in selectedProperty.bookings"
                                                            :key="booking.id">
                                                            <!-- Modern Card Design -->
                                                            <div
                                                                class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 overflow-hidden group">
                                                                <!-- Card Header -->
                                                                <div
                                                                    class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                                                                    <div class="flex justify-between items-start">
                                                                        <div class="flex-1 min-w-0">
                                                                            <h4 class="font-semibold text-gray-900 truncate"
                                                                                x-text="booking.user_name"></h4>
                                                                            <p class="text-sm text-gray-500 truncate"
                                                                                x-text="booking.user_email"></p>
                                                                        </div>
                                                                        <div
                                                                            class="flex flex-col items-end space-y-2 ml-3">
                                                                            <span
                                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                                                :class="booking.status_badge"
                                                                                x-text="booking.status"></span>
                                                                            <span
                                                                                class="text-xs font-mono text-gray-500"
                                                                                x-text="booking.booking_code"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Card Body -->
                                                                <div class="p-5">
                                                                    <!-- Date Information -->
                                                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                                                        <div
                                                                            class="text-center p-3 bg-blue-50 rounded-lg">
                                                                            <p
                                                                                class="text-xs font-medium text-blue-600 uppercase tracking-wide mb-1">
                                                                                Check-in</p>
                                                                            <p class="text-sm font-semibold text-gray-900"
                                                                                x-text="formatDate(booking.check_in)">
                                                                            </p>
                                                                        </div>
                                                                        <div
                                                                            class="text-center p-3 bg-green-50 rounded-lg">
                                                                            <p
                                                                                class="text-xs font-medium text-green-600 uppercase tracking-wide mb-1">
                                                                                Check-out</p>
                                                                            <p class="text-sm font-semibold text-gray-900"
                                                                                x-text="formatDate(booking.check_out)">
                                                                            </p>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Additional Information -->
                                                                    <div class="space-y-3">
                                                                        <div
                                                                            class="flex justify-between items-center text-sm">
                                                                            <span class="text-gray-500">Durasi</span>
                                                                            <span class="font-medium text-gray-900"
                                                                                x-text="booking.duration"></span>
                                                                        </div>
                                                                        <div
                                                                            class="flex justify-between items-center text-sm">
                                                                            <span class="text-gray-500">Total</span>
                                                                            <span class="font-semibold text-green-600"
                                                                                x-text="formatCurrency(booking.total_amount)"></span>
                                                                        </div>
                                                                        <div
                                                                            class="flex justify-between items-center text-sm">
                                                                            <span class="text-gray-500">Dibuat</span>
                                                                            <span class="font-medium text-gray-900"
                                                                                x-text="booking.created_at"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Card Footer -->
                                                                <div
                                                                    class="px-5 py-3 bg-gray-50 border-t border-gray-100">
                                                                    <div
                                                                        class="flex justify-between items-center text-xs">

                                                                        <div class="flex items-center space-x-1">
                                                                            <span class="w-2 h-2 rounded-full blink"
                                                                                :class="{
                                                                                    'bg-amber-400': booking
                                                                                        .payment_status === 'unpaid',
                                                                                    'bg-green-400': booking
                                                                                        .payment_status === 'paid',
                                                                                    'bg-yellow-400': booking
                                                                                        .payment_status === 'pending',
                                                                                    'bg-red-400': booking
                                                                                        .payment_status === 'failed'
                                                                                }">
                                                                            </span>
                                                                            <span class="text-gray-500 capitalize"
                                                                                x-text="booking.payment_status"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div
                                                class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                                                <div class="text-sm text-gray-500">
                                                    <span
                                                        x-text="`Menampilkan ${selectedProperty.bookings ? selectedProperty.bookings.length : 0} dari ${selectedProperty.totalBookings || 0} booking`"></span>
                                                </div>
                                                <div class="flex space-x-3">
                                                    <button @click="modalOpenDetail = false"
                                                        class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-all duration-200 font-medium hover:shadow-md">
                                                        Tutup
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- No Bookings -->
                                <span class="text-sm text-gray-500">Tidak ada booking terkait</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex justify-center items-center">
                                @if ($room->rental_status == 1)
                                    <button onclick="updateRoomStatus({{ $room->idrec }}, 0)"
                                        class="relative overflow-hidden bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-2 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                                        <span class="relative z-10 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Set Available
                                        </span>
                                    </button>
                                @else
                                    <button onclick="updateRoomStatus({{ $room->idrec }}, 1)"
                                        class="relative overflow-hidden bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white px-4 py-2 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                                        <span class="relative z-10 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Set Booked
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data kamar yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
