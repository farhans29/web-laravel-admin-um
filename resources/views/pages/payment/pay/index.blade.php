<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Bagian Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Bukti Pembayaran
                </h1>
            </div>
        </div>
        <!-- Bagian Pencarian dan Filter -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible mb-6">
            <form method="GET" action="{{ route('admin.payments.filter') }}"
                onsubmit="event.preventDefault(); fetchFilteredBookings();"
                class="flex flex-col gap-4 px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    <!-- Pencarian Booking -->
                    <div class="md:col-span-1 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search" name="search" placeholder="ID Pesanan atau Nama Tamu"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ request('search') }}">
                    </div>

                    <!-- Status -->
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        <option value="all">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting
                            Verification</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled
                        </option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>

                    <div class="md:col-span-2 flex gap-2">
                        <div class="flex-1">
                            <div class="relative z-10">
                                <input type="text" id="date_picker" placeholder="Pilih rentang tanggal (Maks 30 hari)"
                                    data-input
                                    class="w-full min-w-[280px] px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <input type="hidden" id="start_date" name="start_date"
                                    value="{{ request('start_date') }}">
                                <input type="hidden" id="end_date" name="end_date" value="{{ request('end_date') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Tampilkan Per Halaman (rata kanan) -->
                    <div class="md:col-span-1 flex justify-end items-end">
                        <div class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-gray-600">Tampilkan:</label>
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

        <!-- Tabel Verifikasi Pembayaran -->
        <div class="overflow-x-auto" id="transactionTable">
            @include('pages.payment.pay.partials.pay_table', [
                'payments' => $payments,
                'per_page' => request('per_page', 8),
            ])
        </div>

        <!-- Paginasi -->        
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $payments->appends(request()->except('page'))->links() }}
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

                    // Proses data lampiran
                    this.$nextTick(() => {
                        this.attachmentData = base64Data;

                        // Cek tipe file (cek sederhana)
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
                alert('Silakan masukkan alasan penolakan.');
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const defaultStartDate = '{{ $startDate ?? now()->format('Y-m-d') }}';
            const defaultEndDate = '{{ $endDate ?? now()->addMonth()->format('Y-m-d') }}';

            // Inisialisasi Flatpickr dengan rentang default
            const datePicker = flatpickr("#date_picker", {
                mode: "range",
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
                        const endDate = selectedDates[1] || selectedDates[0];

                        // Hitung selisih hari (inklusif)
                        const diffInDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

                        // Batasi maksimal 30 hari
                        if (diffInDays > 31) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'warning',
                                title: 'Rentang tanggal maksimal adalah 30 hari',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });

                            instance.clear();
                            document.getElementById('start_date').value = '';
                            document.getElementById('end_date').value = '';
                            return;
                        }

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

            // Set nilai awal input tersembunyi
            document.getElementById('start_date').value = defaultStartDate;
            document.getElementById('end_date').value = defaultEndDate;

            // Fungsi format tanggal
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Set nilai awal jika ada
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

            // Ambil semua elemen filter
            const searchInput = document.getElementById('search');
            const statusSelect = document.getElementById('status');
            const perPageSelect = document.getElementById('per_page');

            // Fungsi debounce untuk pencarian
            const debounce = (func, delay) => {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), delay);
                };
            };

            // Event listener
            searchInput.addEventListener('input', debounce(fetchFilteredBookings, 300));
            statusSelect.addEventListener('change', fetchFilteredBookings);
            perPageSelect.addEventListener('change', fetchFilteredBookings);

            // Fungsi untuk mengambil data booking terfilter
            function fetchFilteredBookings() {
                // Ambil semua nilai filter
                const params = new URLSearchParams();

                // Ambil nilai pencarian
                const search = document.getElementById('search').value;
                if (search) params.append('search', search);

                // Ambil nilai status
                const status = document.getElementById('status').value;
                if (status && status !== 'all') params.append('status', status);

                // Ambil nilai rentang tanggal
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                // Ambil nilai per halaman
                const perPage = document.getElementById('per_page').value;
                params.append('per_page', perPage);

                // Tampilkan loading
                const tableContainer = document.getElementById('transactionTable');
                tableContainer.innerHTML = `
        <div class="flex justify-center items-center h-64">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
    `;

                // Request AJAX ke endpoint filter
                fetch(`{{ route('admin.payments.filter') }}?${params.toString()}`, {
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
                        document.getElementById('transactionTable').innerHTML = data.html;
                        // Update paginasi jika ada
                        const paginationContainer = document.querySelector('.bg-gray-50');
                        if (paginationContainer && data.pagination) {
                            paginationContainer.innerHTML = data.pagination;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableContainer.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    Gagal memuat data. Silakan coba lagi.
                </div>
            `;
                    });
            }
        });
    </script>
</x-app-layout>
