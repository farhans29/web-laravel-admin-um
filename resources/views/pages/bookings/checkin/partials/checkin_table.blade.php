<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Order ID
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Guest Name
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Property/Room
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-in Date
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-out Date
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
            </th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($bookings as $booking)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="text-sm font-medium text-indigo-600">{{ $booking->order_id }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                {{ $booking->transaction->user_name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->transaction->user_email }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $booking->property->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    @if ($booking->check_in_at)
                        <div class="text-sm font-medium text-gray-900">
                            {{ $booking->check_in_at->format('Y-m-d') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $booking->check_in_at->format('H:i') }}
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic">Not checked in</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    @if ($booking->check_out_at)
                        <div class="text-sm font-medium text-gray-900">
                            {{ $booking->check_out_at->format('Y M d') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $booking->check_out_at->format('H:i') }}
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic">Not checked out</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    @php
                        $statusClasses = [
                            'Waiting for Check-In' => 'bg-yellow-100 text-yellow-800',
                            'Checked-In' => 'bg-green-100 text-green-800',
                            'Checked-Out' => 'bg-blue-100 text-blue-800',
                            'Unknown' => 'bg-gray-100 text-gray-800',
                        ];
                    @endphp
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $booking->status }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    @if (is_null($booking->check_in_at))
                        <div x-data="checkInModal('{{ $booking->order_id }}')">
                            <!-- Trigger Button -->
                            <button type="button"
                                @click="openModal('{{ $booking->idrec }}', '{{ $booking->order_id }}')"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none">
                                <!-- Heroicon: door-open -->
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 21V3a1 1 0 011-1h5.5a1 1 0 011 1v2m0 0v14m0-14l7 2v14l-7-2">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h1"></path>
                                </svg>
                                Check-In
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
                                        class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                                        <div class="flex justify-between items-center">
                                            <div class="font-bold text-xl text-gray-800">Check-In Process</div>
                                            <button type="button"
                                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                @click="closeModal">
                                                <div class="sr-only">Close</div>
                                                <svg class="w-6 h-6 fill-current">
                                                    <path
                                                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">Please upload your identification document
                                            to complete check-in</p>
                                        <p class="text-lg font-bold text-gray-800 mt-1" x-text="currentDateTime"></p>
                                    </div>

                                    <!-- Modal Content -->
                                    <div class="flex-1 overflow-y-auto px-6 py-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Booking Details -->
                                            <div class="bg-gray-50 p-4 rounded-lg">
                                                <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    Booking Details
                                                </h3>

                                                <div class="space-y-3">
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-medium text-gray-600">Order
                                                            ID:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.order_id"></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-medium text-gray-600">Check-In
                                                            Date:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.check_in"></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-medium text-gray-600">Check-Out
                                                            Date:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.check_out"></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-medium text-gray-600">Guest
                                                            Name:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.guest_name"></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span
                                                            class="text-sm font-medium text-gray-600">Property:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.property_name"></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-medium text-gray-600">Room:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.room_name"></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span
                                                            class="text-sm font-medium text-gray-600">Duration:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.duration"></span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm font-medium text-gray-600">Total
                                                            Payment:</span>
                                                        <span class="text-sm text-gray-800"
                                                            x-text="bookingDetails.total_payment"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Document Upload Section -->
                                            <div>
                                                <!-- Profile Photo Display -->
                                                <div class="mb-6">
                                                    <h3 class="font-semibold text-lg text-gray-800 mb-2">Guest Profile
                                                    </h3>
                                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                                        <template x-if="profilePhotoUrl">
                                                            <div class="flex flex-col items-center">
                                                                <img :src="profilePhotoUrl" alt="Profile Photo"
                                                                    class="w-full h-48 object-contain rounded-lg">
                                                                <span class="mt-2 text-sm text-gray-600"
                                                                    x-text="bookingDetails.guest_name"></span>
                                                            </div>
                                                        </template>
                                                        <template x-if="!profilePhotoUrl">
                                                            <div
                                                                class="flex flex-col items-center justify-center h-48">
                                                                <div
                                                                    class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-12 w-12 text-gray-400" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                    </svg>
                                                                </div>
                                                                <span class="mt-2 text-sm text-gray-600"
                                                                    x-text="bookingDetails.guest_name"></span>
                                                                <span class="text-xs text-red-500 mt-1">Account not
                                                                    Verified</span>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                                <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Upload Identification
                                                </h3>

                                                <!-- Document Type Selection -->
                                                <div class="mb-4">
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 mb-1">Document
                                                        Type</label>
                                                    <select x-model="selectedDocType"
                                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                                        <option value="ktp">KTP</option>
                                                        <option value="passport">Passport</option>
                                                        <option value="sim">SIM</option>
                                                        <option value="other">Other ID</option>
                                                    </select>
                                                </div>

                                                <!-- Upload Section -->
                                                <div>
                                                    <div x-show="!docPreview"
                                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors duration-200 cursor-pointer"
                                                        @click="$refs.docInput.click()"
                                                        @drop.prevent="handleDocDrop($event)" @dragover.prevent
                                                        @dragenter.prevent
                                                        :class="{ 'border-green-400 bg-green-50': isDragging }">
                                                        <input type="file" id="document" name="document"
                                                            accept="image/*,.pdf" class="hidden" x-ref="docInput"
                                                            @change="handleDocUpload($event)">

                                                        <div class="space-y-2">
                                                            <svg class="w-12 h-12 mx-auto text-gray-400"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                            <p class="text-sm text-gray-600">
                                                                <span class="font-medium text-green-600">Click to
                                                                    upload</span> or drag and drop
                                                            </p>
                                                            <p class="text-xs text-gray-500">JPG, PNG, PDF up to 5MB
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <!-- Document Preview -->
                                                    <div class="mt-4" x-show="docPreview" x-transition>
                                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Document
                                                            Preview
                                                            (<span x-text="selectedDocType.toUpperCase()"></span>)
                                                            :</h4>
                                                        <div class="border border-gray-200 rounded-lg p-2">
                                                            <template x-if="docPreviewType === 'image'">
                                                                <img :src="docPreview" alt="Document Preview"
                                                                    class="w-full h-auto max-h-48 object-contain">
                                                            </template>
                                                            <template x-if="docPreviewType === 'pdf'">
                                                                <div class="bg-gray-100 p-4 text-center">
                                                                    <svg class="w-12 h-12 mx-auto text-red-500"
                                                                        fill="none" stroke="currentColor"
                                                                        viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                    <p class="text-sm text-gray-600 mt-2">PDF Document
                                                                    </p>
                                                                </div>
                                                            </template>
                                                            <div class="mt-2 flex justify-between items-center">
                                                                <span class="text-xs text-gray-500">Uploaded
                                                                    document</span>
                                                                <button type="button" @click="removeDoc"
                                                                    class="text-red-500 hover:text-red-700 text-xs">
                                                                    Remove
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Validation Message -->
                                                <div class="mt-3" x-show="!docPreview">
                                                    <p class="text-sm text-red-600">
                                                        <span class="font-medium">Note:</span> Identification document
                                                        is required for check-in.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between">
                                        <button type="button" @click="closeModal"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Cancel
                                        </button>
                                        <button type="button" @click="submitCheckIn" :disabled="!docPreview"
                                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                            Complete Check-In
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif (!is_null($booking->check_in_at) && is_null($booking->check_out_at))
                        {{-- Sudah check-in, belum check-out --}}
                        <span class="text-yellow-600">Currently Staying</span>
                    @elseif (!is_null($booking->check_in_at) && !is_null($booking->check_out_at))
                        {{-- Sudah check-in dan check-out --}}
                        <span class="text-green-600">Checked-Out</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
