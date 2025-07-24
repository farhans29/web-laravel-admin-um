<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Bukti Pembayaran</h1>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b border-gray-200">
                <!-- Search and Filter Section -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Cari order ID, customer, atau property..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute left-3 top-2.5 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full sm:w-48">
                        <select id="statusFilter"
                            class="w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="waiting">Waiting Verification</option>
                            <option value="paid">Paid</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                            <option value="canceled">Canceled</option>
                            <option value="failed">Failed</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Payment Verification Table -->
            <div class="overflow-x-auto" id="transactionTable">
                @include('pages.payment.pay.partials.pay_table', [
                    'payments' => $payments,
                    'per_page' => request('per_page', 8),
                ])
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $payments->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('attachmentModal', () => ({
                isOpen: false,
                isLoading: true,
                attachmentData: '',
                attachmentType: 'unknown',
                orderId: '',

                openModal(base64Data, orderId) {
                    this.isOpen = true;
                    this.isLoading = true;
                    this.orderId = orderId;

                    // Process the attachment data
                    this.$nextTick(() => {
                        this.attachmentData = base64Data;

                        // Try to determine file type (this is a simple check)
                        if (base64Data.startsWith('/9j/') ||
                            base64Data.startsWith('iVBORw0KGgo') ||
                            base64Data.startsWith('R0lGODdh') ||
                            base64Data.startsWith('R0lGODlh')) {
                            this.attachmentType = 'image';
                        } else if (base64Data.startsWith('JVBERi0')) {
                            this.attachmentType = 'pdf';
                        } else {
                            this.attachmentType = 'unknown';
                        }

                        this.isLoading = false;
                    });
                },

                closeModal() {
                    this.isOpen = false;
                    this.attachmentData = '';
                    this.attachmentType = 'unknown';
                    this.orderId = '';
                }
            }));
        });

        function confirmApprove(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pembayaran akan disetujui!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a', // green-600
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approve-form-' + id).submit();
                }
            })
        }

        let currentTransactionId = null;

        function showRejectModal(id) {
            currentTransactionId = id;
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        function validateRejectForm() {
            const note = document.getElementById('rejectNote').value.trim();
            if (!note) {
                alert('Please enter a rejection reason.');
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const perPageSelect = document.getElementById('perPageSelect');

            // Debounce function
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this,
                        args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            }

            // Function to update filters
            function updateFilters() {
                const params = new URLSearchParams();
                const search = searchInput.value;
                const status = statusFilter.value;
                const perPage = perPageSelect ? perPageSelect.value : 8;

                if (search) params.set('search', search);
                if (status && status !== 'all') params.set('status', status);
                if (perPage && perPage !== '8') params.set('per_page', perPage);

                fetchData(params.toString());
            }

            // Function to fetch data
            function fetchData(queryString) {
                const url = `/payment/payments/filter?${queryString}`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('transactionTable').innerHTML = data.html;
                        if (data.pagination) {
                            document.querySelector('.bg-gray-50').innerHTML = data.pagination;
                        }

                        // Update URL without reload
                        window.history.pushState({}, '', `${window.location.pathname}?${queryString}`);
                    });
            }

            // Initialize filters from URL
            function initFiltersFromUrl() {
                const params = new URLSearchParams(window.location.search);

                if (params.has('search')) {
                    searchInput.value = params.get('search');
                }

                if (params.has('status')) {
                    statusFilter.value = params.get('status');
                }

                if (perPageSelect && params.has('per_page')) {
                    perPageSelect.value = params.get('per_page');
                }
            }

            // Event listeners
            searchInput.addEventListener('input', debounce(updateFilters, 300));
            statusFilter.addEventListener('change', updateFilters);
            if (perPageSelect) {
                perPageSelect.addEventListener('change', updateFilters);
            }

            // Handle popstate (back/forward navigation)
            window.addEventListener('popstate', function() {
                initFiltersFromUrl();
                updateFilters();
            });

            // Initialize
            initFiltersFromUrl();
        });
    </script>
</x-app-layout>
