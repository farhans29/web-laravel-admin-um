<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Guest Check-in
                </h1>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">

            <!-- Search and Filter Section -->
            <div class="p-4 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Booking</label>
                        <input type="text" id="search" name="search" placeholder="Order ID or Guest Name"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                        <input type="date" id="date" name="date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="waiting">Waiting for Check-In</option>
                            <option value="checked-in">Checked-In</option>
                            <option value="checked-out">Checked-Out</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Guest Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Room
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-in Date
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Check-out Date
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $booking->order_id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->transaction->user_name ?? 'N/A' }}
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
                                            <div x-data="checkInModal()">
                                                <!-- Trigger Button -->
                                                <button type="button"
                                                    onclick="showCheckInModal('{{ $booking->idrec }}', '{{ $booking->order_id }}')"
                                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none"
                                                    @click="openModal">
                                                    <!-- Heroicon: door-open -->
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        stroke-width="2" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3 21V3a1 1 0 011-1h5.5a1 1 0 011 1v2m0 0v14m0-14l7 2v14l-7-2">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M13 16h1"></path>
                                                    </svg>
                                                    Check-In
                                                </button>

                                                <!-- Modal Backdrop -->
                                                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                                                    x-show="isOpen"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition ease-out duration-200"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>

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
                                                                <div class="font-bold text-xl text-gray-800">Check-In
                                                                    Process</div>
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
                                                            <p class="text-sm text-gray-600 mt-1">Please upload your
                                                                KTP to complete check-in</p>
                                                            <p class="text-xs text-gray-500 mt-1"
                                                                x-text="currentDateTime"></p>
                                                        </div>

                                                        <!-- Modal Content -->
                                                        <div class="flex-1 overflow-y-auto px-6 py-6">
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                                <!-- Booking Details -->
                                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                                    <h3
                                                                        class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                        <svg class="w-5 h-5 mr-2 text-blue-600"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                                        </svg>
                                                                        Booking Details
                                                                    </h3>

                                                                    <div class="space-y-3">
                                                                        <div class="flex justify-between">
                                                                            <span
                                                                                class="text-sm font-medium text-gray-600">Order
                                                                                ID:</span>
                                                                            <span class="text-sm text-gray-800"
                                                                                x-text="bookingDetails.order_id"></span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span
                                                                                class="text-sm font-medium text-gray-600">Guest
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
                                                                            <span
                                                                                class="text-sm font-medium text-gray-600">Room:</span>
                                                                            <span class="text-sm text-gray-800"
                                                                                x-text="bookingDetails.room_name"></span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span
                                                                                class="text-sm font-medium text-gray-600">Check-in
                                                                                Date:</span>
                                                                            <span class="text-sm text-gray-800"
                                                                                x-text="bookingDetails.check_in_date"></span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span
                                                                                class="text-sm font-medium text-gray-600">Check-out
                                                                                Date:</span>
                                                                            <span class="text-sm text-gray-800"
                                                                                x-text="bookingDetails.check_out_date"></span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span
                                                                                class="text-sm font-medium text-gray-600">Duration:</span>
                                                                            <span class="text-sm text-gray-800"
                                                                                x-text="bookingDetails.duration"></span>
                                                                        </div>
                                                                        <div class="flex justify-between">
                                                                            <span
                                                                                class="text-sm font-medium text-gray-600">Total
                                                                                Payment:</span>
                                                                            <span class="text-sm text-gray-800"
                                                                                x-text="bookingDetails.total_payment"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- KTP Upload Section -->
                                                                <div>
                                                                    <h3
                                                                        class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                        <svg class="w-5 h-5 mr-2 text-green-600"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                        </svg>
                                                                        Upload KTP
                                                                    </h3>

                                                                    <!-- Upload Area -->
                                                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors duration-200 cursor-pointer"
                                                                        @click="$refs.ktpInput.click()"
                                                                        @drop.prevent="handleKtpDrop($event)"
                                                                        @dragover.prevent @dragenter.prevent
                                                                        :class="{ 'border-green-400 bg-green-50': isDragging }">
                                                                        <input type="file" id="ktp"
                                                                            name="ktp" accept="image/*"
                                                                            class="hidden" x-ref="ktpInput"
                                                                            @change="handleKtpUpload($event)">

                                                                        <div class="space-y-2">
                                                                            <svg class="w-12 h-12 mx-auto text-gray-400"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                </path>
                                                                            </svg>
                                                                            <p class="text-sm text-gray-600">
                                                                                <span
                                                                                    class="font-medium text-green-600">Click
                                                                                    to upload</span> or drag and drop
                                                                            </p>
                                                                            <p class="text-xs text-gray-500">JPG, PNG
                                                                                up to 5MB</p>
                                                                        </div>
                                                                    </div>

                                                                    <!-- KTP Preview -->
                                                                    <div class="mt-4" x-show="ktpPreview">
                                                                        <h4
                                                                            class="text-sm font-medium text-gray-700 mb-2">
                                                                            KTP Preview:</h4>
                                                                        <div
                                                                            class="border border-gray-200 rounded-lg p-2">
                                                                            <img :src="ktpPreview"
                                                                                alt="KTP Preview"
                                                                                class="w-full h-auto max-h-48 object-contain">
                                                                            <div
                                                                                class="mt-2 flex justify-between items-center">
                                                                                <span
                                                                                    class="text-xs text-gray-500">Uploaded
                                                                                    KTP</span>
                                                                                <button type="button"
                                                                                    @click="removeKtp"
                                                                                    class="text-red-500 hover:text-red-700 text-xs">
                                                                                    Remove
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Validation Message -->
                                                                    <div class="mt-3" x-show="!ktpPreview">
                                                                        <p class="text-sm text-red-600">
                                                                            <span class="font-medium">Note:</span> KTP
                                                                            upload is required for check-in.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Modal Footer -->
                                                        <div
                                                            class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between">
                                                            <button type="button" @click="closeModal"
                                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                                Cancel
                                                            </button>
                                                            <button type="button" @click="submitCheckIn"
                                                                :disabled="!ktpPreview"
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
                </div>
                @if ($bookings->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        {{ $bookings->appends(request()->input())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function checkInModal() {
            return {
                isOpen: false,
                isDragging: false,
                ktpPreview: null,
                bookingId: '{{ $booking->order_id }}',
                currentDateTime: new Date().toLocaleString(),
                bookingDetails: {
                    order_id: '',
                    guest_name: '',
                    property_name: '',
                    room_name: '',
                    check_in_date: '',
                    check_out_date: '',
                    duration: '',
                    total_payment: ''
                },
                iinit() {
                    setInterval(() => {
                        this.currentDateTime = new Date().toLocaleString();
                    }, 1000);
                },

                openModal() {
                    this.isOpen = true;
                    this.currentDateTime = new Date().toLocaleString();
                    this.fetchBookingDetails();
                },

                fetchBookingDetails() {
                    console.log("Booking ID:", this.bookingId);

                    $.ajax({
                        url: `/bookings/check-in/${this.bookingId}/details`,
                        type: 'GET',
                        dataType: 'json',
                        success: (data) => {
                            console.log("Fetched Data:", data);

                            this.bookingDetails = {
                                order_id: data.order_id,
                                guest_name: data.transaction?.user_name || 'N/A',
                                property_name: data.property?.name || 'N/A',
                                room_name: data.room?.name || 'N/A',
                                check_in_date: data.check_in_at ? new Date(data.check_in_at)
                                    .toLocaleDateString() : 'Not set',
                                check_out_date: data.check_out_at ? new Date(data.check_out_at)
                                    .toLocaleDateString() : 'Not set',
                                duration: this.calculateDuration(data.check_in_at, data.check_out_at),
                                total_payment: data.transaction?.grandtotal_price ?
                                    `Rp ${Number(data.transaction.grandtotal_price).toLocaleString()}` :
                                    'N/A'
                            };
                        },
                        error: (xhr, status, error) => {
                            console.error('Error fetching booking details:', xhr.responseText || error);
                        }
                    });
                },

                calculateDuration(checkIn, checkOut) {
                    if (!checkIn || !checkOut) return 'N/A';

                    const start = new Date(checkIn);
                    const end = new Date(checkOut);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    return `${diffDays} night${diffDays > 1 ? 's' : ''}`;
                },

                closeModal() {
                    this.isOpen = false;
                    this.resetForm();
                },

                handleKtpDrop(e) {
                    this.isDragging = false;
                    const files = e.dataTransfer.files;
                    if (files.length > 0 && files[0].type.match('image.*')) {
                        this.previewKtp(files[0]);
                    }
                },

                handleKtpUpload(e) {
                    const file = e.target.files[0];
                    if (file && file.type.match('image.*')) {
                        const reader = new FileReader();

                        reader.onload = (e) => {
                            this.ktpPreview = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    }
                },

                previewKtp(file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File size should be less than 5MB');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.ktpPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },

                removeKtp() {
                    this.ktpPreview = null;
                    this.$refs.ktpInput.value = '';
                },

                submitCheckIn() {
                    if (!this.ktpPreview) {
                        alert('Please upload your KTP first');
                        return;
                    }

                    // Kirim data ke server
                    $.ajax({
                        url: `/bookings/checkin/${this.bookingId}`,
                        method: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify({
                            ktp_img: this.ktpPreview                            
                        }),
                        success: function(data) {
                            if (data.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Check-in submitted successfully!',
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: true
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Error: ' + data.message,
                                    showConfirmButton: false,
                                    timer: 1000,
                                    timerProgressBar: true
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alert('An error occurred during check-in');
                        }
                    });
                },

                resetForm() {
                    this.ktpPreview = null;
                    if (this.$refs.ktpInput) {
                        this.$refs.ktpInput.value = '';
                    }
                }
            };
        }

        function showCheckInModal(idrec, orderId) {
            const modal = document.querySelector('[x-data]');
            if (modal && modal.__x) {
                modal.__x.$data.bookingId = orderId;
                modal.__x.$data.openModal();
            }
        }
    </script>
</x-app-layout>
