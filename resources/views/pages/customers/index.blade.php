<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Customer Management
                </h1>
                <p class="text-gray-600 mt-2">Manage customer information and booking history</p>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible mb-6">
            <form method="GET" action="{{ route('customers.index') }}" id="filterForm"
                class="flex flex-col gap-4 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <!-- Search Input -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                            Search
                        </label>
                        <div class="relative">
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Search by name, email, or phone..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm
                                focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Status Filter -->
                    <div>
                        <label for="registration_status" class="block text-sm font-medium text-gray-700 mb-1">
                            Registration Status
                        </label>
                        <select name="registration_status" id="registration_status"
                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                            <option value="all" {{ request('registration_status') == 'all' ? 'selected' : '' }}>All
                                Customers</option>
                            <option value="registered"
                                {{ request('registration_status') == 'registered' ? 'selected' : '' }}>Registered Only
                            </option>
                            <option value="guest" {{ request('registration_status') == 'guest' ? 'selected' : '' }}>
                                Guest Only</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700 mb-1">
                            Show
                        </label>
                        <select name="per_page" id="per_page"
                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200">
                            <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>8 per page</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per page</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15 per page</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 per page</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per page</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Customer Table -->
        <div id="customerTableContainer" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            @include('pages.customers.partials.customer_table', ['customers' => $customers, 'perPage' => $perPage])
        </div>
    </div>

    <!-- Customer Detail Modal -->
    <div x-data="customerModal">
        <!-- Modal backdrop -->
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity" x-show="modalOpen"
            x-transition.opacity aria-hidden="true" x-cloak>
        </div>

        <!-- Modal dialog -->
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-show="modalOpen"
            x-transition x-cloak @keydown.escape.window="modalOpen = false">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-hidden relative z-50"
                @click.outside="modalOpen = false">

                <!-- Header -->
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <span x-text="customerName"></span> - Booking History
                    </h2>
                    <button class="text-gray-500 text-xl hover:text-gray-700 transition-colors"
                        @click="modalOpen = false">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-80px)]">
                    <!-- Loading State -->
                    <div x-show="loading" class="text-center py-8">
                        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <p class="text-gray-600 mt-2">Loading bookings...</p>
                    </div>

                    <!-- Bookings List -->
                    <div x-show="!loading && bookings.length > 0" class="space-y-4">
                        <template x-for="booking in bookings" :key="booking.order_id">
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900" x-text="booking.property_name"></h3>
                                        <p class="text-sm text-gray-600" x-text="booking.room_name"></p>
                                    </div>
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold"
                                        :class="{
                                            'bg-yellow-100 text-yellow-700': booking.transaction_status === 'pending',
                                            'bg-green-100 text-green-700': booking.transaction_status === 'completed',
                                            'bg-blue-100 text-blue-700': booking.transaction_status === 'confirmed',
                                            'bg-red-100 text-red-700': booking.transaction_status === 'cancelled'
                                        }"
                                        x-text="booking.transaction_status">
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500">Order ID</p>
                                        <p class="font-medium" x-text="booking.order_id"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Transaction Date</p>
                                        <p class="font-medium" x-text="booking.transaction_date"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Check-in</p>
                                        <p class="font-medium" x-text="booking.check_in"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Check-out</p>
                                        <p class="font-medium" x-text="booking.check_out"></p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Duration</p>
                                        <p class="font-medium">
                                            <span x-show="booking.booking_days > 0"
                                                x-text="`${booking.booking_days} day(s)`"></span>
                                            <span x-show="booking.booking_months > 0"
                                                x-text="`${booking.booking_months} month(s)`"></span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Total Price</p>
                                        <p class="font-medium text-green-600">Rp <span
                                                x-text="booking.grandtotal_price"></span></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!loading && bookings.length === 0" class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-600 font-medium">No bookings found</p>
                        <p class="text-gray-500 text-sm mt-1">This customer hasn't made any bookings yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif

    <!-- Alpine.js Customer Modal Component -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('customerModal', () => ({
                modalOpen: false,
                customerName: '',
                bookings: [],
                loading: false,
                init() {
                    // Listen for custom event from dynamically loaded content
                    window.addEventListener('open-customer-modal', (event) => {
                        const { identifier, type, name } = event.detail;
                        this.openModal(identifier, type, name);
                    });
                },
                openModal(identifier, type, name) {
                    this.modalOpen = true;
                    this.customerName = name;
                    this.loading = true;
                    this.bookings = [];

                    fetch(`/customers/${identifier}/bookings?type=${type}`)
                        .then(response => response.json())
                        .then(data => {
                            this.customerName = data.customer_name;
                            this.bookings = data.bookings;
                            this.loading = false;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.loading = false;
                        });
                }
            }));
        });
    </script>

    <!-- Live Search Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const registrationStatus = document.getElementById('registration_status');
            const perPageSelect = document.getElementById('per_page');
            let searchTimeout;

            function submitFilter() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const formData = new FormData();
                    formData.append('search', searchInput.value);
                    formData.append('registration_status', registrationStatus.value);
                    formData.append('per_page', perPageSelect.value);

                    const params = new URLSearchParams(formData);

                    fetch(`{{ route('customers.filter') }}?${params.toString()}`)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('customerTableContainer').innerHTML = html;
                        })
                        .catch(error => console.error('Error:', error));
                }, 500);
            }

            searchInput.addEventListener('input', submitFilter);
            registrationStatus.addEventListener('change', submitFilter);
            perPageSelect.addEventListener('change', submitFilter);
        });
    </script>
</x-app-layout>
