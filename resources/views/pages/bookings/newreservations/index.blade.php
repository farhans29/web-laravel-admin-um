<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    New Reservations
                </h1>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible mb-6">
            <form method="GET" action="{{ route('checkin.filter') }}"
                onsubmit="event.preventDefault(); fetchFilteredBookings();"
                class="flex flex-col gap-4 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <!-- Search Booking -->
                    <div class="md:col-span-1 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search" name="search" placeholder="Order ID or Guest Name"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ request('search') }}">
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <div class="flex-1">
                            <div class="relative z-50">
                                <input type="text" id="date_picker" placeholder="Select date range (Max 30 days)"
                                    data-input
                                    class="w-full min-w-[280px] px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <input type="hidden" id="start_date" name="start_date"
                                    value="{{ request('start_date') }}">
                                <input type="hidden" id="end_date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Show Per Page (aligned to the right) -->
                    <div class="md:col-span-1 md:col-start-5 flex justify-end items-end">
                        <div class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-gray-600">Show:</label>
                            <select name="per_page" id="per_page"
                                class="border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                <option value="8" {{ request('per_page') == 8 ? 'selected' : '' }}>8</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="overflow-x-auto" id="bookingTableContainer">
            @include('pages.bookings.newreservations.partials.newreserve_table', [
                'bookings' => $bookings,
                'per_page' => request('per_page', 8),
            ])
        </div>
        <!-- Pagination -->
        <div class="bg-gray-50 rounded p-4" id="paginationContainer">
            {{ $bookings->appends(request()->input())->links() }}
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('checkInModal', (initialOrderId) => ({
                isOpen: false,
                isDragging: false,
                docPreview: null,
                docPreviewType: null,
                profilePhotoUrl: null,
                profilePhotoUrlDemo: null,
                profilePhotoUrlWeb: null,
                selectedDocType: 'ktp',
                bookingId: initialOrderId,
                isBeforeCheckInTime: false,
                checkInTime: '15:00',
                currentDateTime: new Date().toLocaleString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }),
                bookingDetails: {
                    order_id: '',
                    guest_name: '',
                    property_name: '',
                    room_name: '',
                    duration: '',
                    total_payment: ''
                },

                init() {
                    // Update time every second
                    setInterval(() => {
                        this.currentDateTime = new Date().toLocaleString('en-US', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit',
                            hour12: false
                        });
                    }, 1000);
                },

                async openModal(idrec, orderId) {
                    this.bookingId = orderId;
                    this.isOpen = true;
                    this.currentDateTime = new Date().toLocaleString('en-US', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false
                    });

                    const now = new Date();
                    const checkInTime = new Date();
                    checkInTime.setHours(15, 0, 0, 0); // Set to 3:00 PM

                    this.isBeforeCheckInTime = now < checkInTime;

                    if (!this.isBeforeCheckInTime) {
                        await this.fetchBookingDetails();
                    }
                },

                async fetchBookingDetails() {
                    try {
                        const response = await fetch(
                            `/bookings/check-in/${this.bookingId}/details`);
                        const data = await response.json();

                        this.bookingDetails = {
                            order_id: data.order_id,
                            check_in: data.transaction?.check_in ?
                                new Date(data.transaction.check_in).toLocaleString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                }) : 'N/A',

                            check_out: data.transaction?.check_out ?
                                new Date(data.transaction.check_out).toLocaleString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                }) : 'N/A',
                            guest_name: data.transaction?.user_name || 'N/A',
                            property_name: data.property?.name || 'N/A',
                            room_name: data.room?.name || 'N/A',
                            duration: this.calculateDuration(data.transaction?.check_in, data
                                .transaction?.check_out),
                            total_payment: data.transaction?.grandtotal_price ?
                                this.formatRupiah(data.transaction.grandtotal_price) : 'N/A',

                        };

                        this.profilePhotoUrlDemo = data.user_profile_photo_demo || null;
                        this.profilePhotoUrlWeb = data.user_profile_photo_web || null;
                        this.profilePhotoUrl = this.profilePhotoUrlWeb || this
                            .profilePhotoUrlDemo || null;

                    } catch (error) {
                        console.error('Error fetching booking details:', error);
                        this.showErrorToast('Failed to load booking details');
                    }
                },

                calculateDuration(checkIn, checkOut) {
                    if (!checkIn || !checkOut) return 'N/A';

                    const start = new Date(checkIn);
                    const end = new Date(checkOut);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    return `${diffDays} night${diffDays > 1 ? 's' : ''}`;
                },

                handleDocDrop(e) {
                    this.isDragging = false;
                    const files = e.dataTransfer.files;
                    if (files.length > 0 && (files[0].type.match('image.*') || files[0].type ===
                            'application/pdf')) {
                        this.previewDoc(files[0]);
                    }
                },

                handleDocUpload(e) {
                    const file = e.target.files[0];
                    if (file && (file.type.match('image.*') || file.type === 'application/pdf')) {
                        this.previewDoc(file);
                    }
                },

                previewDoc(file) {
                    if (file.size > 5 * 1024 * 1024) {
                        this.showErrorToast('File size should be less than 5MB');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.docPreview = e.target.result;
                        this.docPreviewType = file.type === 'application/pdf' ? 'pdf' : 'image';
                    };
                    reader.readAsDataURL(file);
                },

                removeDoc() {
                    this.docPreview = null;
                    this.docPreviewType = null;
                    this.$refs.docInput.value = '';
                },

                async submitCheckIn() {
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content');

                        // Prepare the data to send
                        const requestData = {
                            doc_type: this.selectedDocType,
                            has_profile_photo: !!this
                                .profilePhotoUrl // Add flag for profile photo existence
                        };

                        // Include doc_image only if there's no profile photo
                        if (!this.profilePhotoUrl && this.docPreview) {
                            requestData.doc_image = this.docPreview;
                        } else if (!this.profilePhotoUrl && !this.docPreview) {
                            this.showErrorToast('Please upload your identification document first');
                            return;
                        }

                        const response = await fetch(`/bookings/checkin/${this.bookingId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(requestData)
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.showSuccessToast('Check-in submitted successfully!');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            this.showErrorToast(data.message || 'Check-in failed');
                        }
                    } catch (error) {
                        console.error('Error during check-in:', error);
                        this.showErrorToast('An error occurred during check-in');
                    }
                },

                showSuccessToast(message) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                },

                showErrorToast(message) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: message,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                },

                formatRupiah(value) {
                    const numericValue = parseFloat(value);

                    if (isNaN(numericValue)) {
                        return 'Rp 0';
                    }

                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(numericValue);
                },

                closeModal() {
                    this.isOpen = false;
                    this.resetForm();
                },

                resetForm() {
                    this.docPreview = null;
                    this.docPreviewType = null;
                    this.selectedDocType = 'ktp';
                    if (this.$refs.docInput) {
                        this.$refs.docInput.value = '';
                    }
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const defaultStartDate = new Date();
            const defaultEndDate = new Date();
            defaultEndDate.setMonth(defaultEndDate.getMonth() + 1);

            // Initialize Flatpickr with default range
            const datePicker = flatpickr("#date_picker", {
                mode: "range", // Tetap mode range tapi bisa pilih 1 tanggal
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "j F Y",
                allowInput: true,
                static: true,
                monthSelectorType: 'static',
                defaultDate: [defaultStartDate, defaultEndDate],
                minDate: "today",
                maxDate: new Date().fp_incr(365),
                onOpen: function(selectedDates, dateStr, instance) {
                    instance.set('minDate', null);
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const startDate = selectedDates[0];
                        // Jika hanya 1 tanggal yang dipilih, gunakan tanggal itu saja
                        const endDate = selectedDates[1] || selectedDates[0];

                        // Format tanggal ke YYYY-MM-DD
                        document.getElementById('start_date').value = formatDate(startDate);
                        document.getElementById('end_date').value = formatDate(endDate);
                        fetchFilteredBookings();
                    }
                },
                onClose: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 0) {
                        document.getElementById('start_date').value = '';
                        document.getElementById('end_date').value = '';
                        fetchFilteredBookings();
                    }
                }
            });

            // Set initial hidden input values
            document.getElementById('start_date').value = formatDate(defaultStartDate);
            document.getElementById('end_date').value = formatDate(defaultEndDate);

            // Fungsi format tanggal
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Set initial values if they exist
            @if (request('start_date') && request('end_date'))
                const startDate = new Date('{{ request('start_date') }}');
                const endDate = new Date('{{ request('end_date') }}');

                // Jika start_date dan end_date sama, set hanya 1 tanggal
                if (formatDate(startDate) === formatDate(endDate)) {
                    datePicker.setDate(startDate);
                } else {
                    datePicker.setDate([startDate, endDate]);
                }
            @endif

            // Get all filter elements
            const searchInput = document.getElementById('search');
            const perPageSelect = document.getElementById('per_page');

            // Debounce function for search
            const debounce = (func, delay) => {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), delay);
                };
            };

            // Event listeners
            searchInput.addEventListener('input', debounce(fetchFilteredBookings, 300));
            perPageSelect.addEventListener('change', fetchFilteredBookings);

            // Function to fetch filtered bookings
            function fetchFilteredBookings() {
                // Collect all filter values
                const params = new URLSearchParams();

                // Get search value
                const search = document.getElementById('search').value;
                if (search) params.append('search', search);

                // Get date range values
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                // Get per page value
                const perPage = document.getElementById('per_page').value;
                params.append('per_page', perPage);

                // Show loading state
                const tableContainer = document.querySelector('.overflow-x-auto');
                tableContainer.innerHTML = `
                                                <div class="flex justify-center items-center h-64">
                                                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                                                </div>
                                            `;

                // Make AJAX request to the filter endpoint
                fetch(`{{ route('checkin.filter') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        document.querySelector('.overflow-x-auto').innerHTML = data.table;
                        document.getElementById('paginationContainer').innerHTML = data.pagination;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableContainer.innerHTML = `
                                                        <div class="text-center py-8 text-red-500">
                                                            Error loading data. Please try again.
                                                        </div>
                                                    `;
                    });
            }
        });
    </script>
</x-app-layout>
