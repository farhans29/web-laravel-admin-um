<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-in</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-out</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ID Pesanan</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nama</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Properti/Kamar</th>
            @if ($showStatus ?? true)
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status</th>
            @endif
            @if ($showActions ?? true)
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Aksi</th>
            @endif
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($checkOuts as $booking)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($booking->transaction && $booking->transaction->check_in)
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($booking->transaction->check_in)->format('Y M d') }}
                            </span>
                            <span class="text-xs text-gray-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($booking->transaction->check_in)->format('H:i') }}
                            </span>
                        </div>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Belum check-in
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    @if ($booking->transaction->check_out)
                        <div class="text-sm font-medium text-gray-900">
                            {{ $booking->transaction->check_out->format('Y M d') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $booking->transaction->check_out->format('H:i') }}
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic">Belum check-out</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-indigo-600">{{ $booking->order_id }}</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $booking->transaction->user_name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->transaction->user_email ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->transaction->user_phone_number ?? '-' }}</div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $booking->property->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }}</div>
                </td>
                @if ($showStatus ?? true)
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if ($booking->check_out_at)
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                Sudah Check-Out
                            </span>
                        @else
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Terisi
                            </span>
                        @endif
                    </td>
                @endif
                @if ($showActions ?? true)
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-center" x-data="{ open: false }">
                        @if (is_null($booking->check_out_at))
                            <div x-data="checkOutModal('{{ $booking->order_id }}')">
                                <!-- Trigger Button -->
                                <button type="button"
                                    @click="openModal('{{ $booking->idrec }}', '{{ $booking->order_id }}')"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-yellow-600 rounded-md hover:bg-yellow-700 focus:outline-none">
                                    <!-- Heroicon: door-closed -->
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 21V3a1 1 0 011-1h5.5a1 1 0 011 1v2m0 0v14m0-14l7 2v14l-7-2">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h1">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18">
                                        </path>
                                    </svg>
                                    Check-Out
                                </button>

                                <!-- Modal Backdrop -->
                                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                                    x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-out duration-200"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    aria-hidden="true" x-cloak></div>

                                <!-- Modal Dialog -->
                                <div class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                    role="dialog" aria-modal="true" x-show="isOpen"
                                    x-transition:enter="transition ease-in-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition ease-in-out duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak>

                                    <div class="bg-white rounded-lg shadow-xl overflow-auto w-full overflow-auto max-h-full flex flex-col text-left max-w-4xl"
                                        @click.outside="closeModal" @keydown.escape.window="closeModal">

                                        <!-- Modal Header -->
                                        <div
                                            class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-yellow-100">
                                            <div class="flex justify-between items-center">
                                                <div class="font-bold text-xl text-gray-800">Proses Check-Out
                                                </div>
                                                <button type="button"
                                                    class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                    @click="closeModal">
                                                    <div class="sr-only">Tutup</div>
                                                    <svg class="w-6 h-6 fill-current">
                                                        <path
                                                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">Silakan verifikasi kondisi kamar dan
                                                selesaikan proses check-out</p>
                                            <p class="text-lg font-bold mt-1"
                                                :class="{
                                                    'text-red-600': isLateCheckout,
                                                    'text-gray-800': !isLateCheckout
                                                }"
                                                x-text="currentDateTime"></p>
                                            <div x-show="isLateCheckout" class="mt-2 flex items-center text-red-600">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm font-medium">Check-out terlambat! Tamu telah
                                                    melewati
                                                    waktu check-out yang dijadwalkan.</span>
                                            </div>
                                        </div>

                                        <!-- Modal Content -->
                                        <div class="flex-1 overflow-y-auto px-6 py-6">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <!-- Booking Details -->
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <h3
                                                        class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                        </svg>
                                                        Detail Pemesanan
                                                    </h3>

                                                    <div class="space-y-3">
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-medium text-gray-600">ID
                                                                Pesanan:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.order_id"></span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-medium text-gray-600">Tanggal
                                                                Check-In:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.check_in"></span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-medium text-gray-600">Tanggal
                                                                Check-Out:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.check_out"></span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-medium text-gray-600">Nama
                                                                Tamu:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.guest_name"></span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span
                                                                class="text-sm font-medium text-gray-600">Properti:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.property_name"></span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span
                                                                class="text-sm font-medium text-gray-600">Kamar:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.room_name"></span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span
                                                                class="text-sm font-medium text-gray-600">Durasi:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.duration"></span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-sm font-medium text-gray-600">Total
                                                                Pembayaran:</span>
                                                            <span class="text-sm text-gray-800"
                                                                x-text="bookingDetails.total_payment"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Room Inventory Check -->
                                                <div>
                                                    <h3
                                                        class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                        <svg class="w-5 h-5 mr-2 text-red-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                        </svg>
                                                        Pemeriksaan Inventaris Kamar
                                                    </h3>

                                                    <div class="mb-4">
                                                        <p class="text-sm text-gray-600 mb-3">Silakan periksa semua
                                                            barang
                                                            yang hilang atau rusak:</p>

                                                        <!-- Add Select All checkbox -->
                                                        <div class="flex items-center mb-2">
                                                            <input id="select-all-items" type="checkbox"
                                                                @change="toggleSelectAll($event.target.checked)"
                                                                class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                                            <label for="select-all-items"
                                                                class="ml-2 block text-sm font-medium text-gray-700">Pilih
                                                                Semua</label>
                                                        </div>

                                                        <div class="space-y-2">
                                                            <template x-for="(item, index) in roomInventory"
                                                                :key="index">
                                                                <div class="flex flex-col space-y-2">
                                                                    <div class="flex items-center">
                                                                        <input :id="'item-' + index" type="checkbox"
                                                                            x-model="item.missingOrDamaged"
                                                                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                                                        <label :for="'item-' + index"
                                                                            class="ml-2 block text-sm text-gray-700"
                                                                            x-text="item.name"></label>
                                                                        <template x-if="item.missingOrDamaged && item.name !== 'Lain-lain'">
                                                                            <div class="ml-4 flex-1">
                                                                                <select x-model="item.condition"
                                                                                    class="block w-full pl-3 pr-10 py-1 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                                                                    <option value="missing">Hilang</option>
                                                                                    <option value="damaged">Rusak</option>
                                                                                </select>
                                                                            </div>
                                                                        </template>
                                                                    </div>

                                                                    <!-- Show textbox for Lain-lain when checked -->
                                                                    <template x-if="item.missingOrDamaged && item.name === 'Lain-lain'">
                                                                        <div class="ml-6 space-y-2">
                                                                            <select x-model="item.condition"
                                                                                class="block w-full pl-3 pr-10 py-1 text-base border-gray-300 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm rounded-md">
                                                                                <option value="missing">Hilang</option>
                                                                                <option value="damaged">Rusak</option>
                                                                            </select>
                                                                            <input type="text"
                                                                                x-model="item.customText"
                                                                                placeholder="Sebutkan barang lain-lain..."
                                                                                class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>

                                                    <!-- Additional Notes -->
                                                    <div class="mt-4">
                                                        <label for="checkout-notes"
                                                            class="block text-sm font-medium text-gray-700">Catatan
                                                            Tambahan</label>
                                                        <textarea id="checkout-notes" x-model="additionalNotes" rows="3"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"></textarea>
                                                    </div>

                                                    <!-- Damage Charges -->
                                                    <div class="mt-4" x-show="hasDamagedItems">
                                                        <label class="block text-sm font-medium text-gray-700">Biaya
                                                            Kerusakan</label>
                                                        <div class="mt-1 relative rounded-md shadow-sm">
                                                            <div
                                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">Rp</span>
                                                            </div>
                                                            <input type="number" x-model="damageCharges"
                                                                class="focus:ring-red-500 focus:border-red-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                                                                placeholder="0">
                                                            <div class="absolute inset-y-0 right-0 flex items-center">
                                                                <label for="currency" class="sr-only">Mata
                                                                    Uang</label>
                                                            </div>
                                                        </div>
                                                        <p class="mt-1 text-sm text-gray-500"
                                                            x-show="damageCharges > 0">
                                                            Jumlah ini akan dipotong dari deposit jaminan.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div
                                            class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between">
                                            <button type="button" @click="closeModal"
                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                Batal
                                            </button>
                                            <button type="button" @click="submitCheckOut"
                                                class="px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                                Selesaikan Check-Out
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!is_null($booking->check_in_at) && !is_null($booking->check_out_at))
                            <div class="flex flex-col items-center space-y-2">
                                <span class="text-green-600">Sudah Check-Out</span>

                                <a href="{{ route('newReserv.checkin.invoice', $booking->order_id) }}"
                                    target="_blank"
                                    class="iinline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none">
                                    View Invoice
                                </a>
                            </div>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                    Tidak ada pemesanan baru.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
