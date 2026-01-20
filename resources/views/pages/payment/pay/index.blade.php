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
                                <input type="text" id="date_picker"
                                    placeholder="Pilih rentang tanggal (Maks 30 hari)" data-input
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

    <script src="{{ asset('js/date-filter-persistence.js') }}"></script>
    <script>
        // Alpine.js Component untuk Attachment Modal
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

                    // Disable body scroll ketika modal terbuka
                    document.body.style.overflow = 'hidden';

                    this.$nextTick(() => {
                        this.attachmentData = base64Data;

                        // Deteksi tipe file berdasarkan signature base64
                        if (this.isImage(base64Data)) {
                            this.attachmentType = 'image';
                        } else if (this.isPDF(base64Data)) {
                            this.attachmentType = 'pdf';
                        } else {
                            this.attachmentType = 'unknown';
                        }

                        // Simulasi loading untuk UX yang lebih baik
                        setTimeout(() => {
                            this.isLoading = false;
                        }, 500);
                    });
                },

                closeModal() {
                    this.isOpen = false;
                    this.attachmentData = '';
                    this.attachmentType = 'unknown';
                    this.orderId = '';
                    // Enable body scroll kembali
                    document.body.style.overflow = '';
                },

                isImage(base64Data) {
                    const imageSignatures = {
                        '/9j/': 'JPEG',
                        'iVBORw0KGgo': 'PNG',
                        'R0lGODdh': 'GIF',
                        'R0lGODlh': 'GIF',
                        'UklGR': 'WEBP',
                        'Qk02': 'BMP'
                    };

                    return Object.keys(imageSignatures).some(signature =>
                        base64Data.startsWith(signature)
                    );
                },

                isPDF(base64Data) {
                    return base64Data.startsWith('JVBERi0');
                }
            }));
        });

        // Fungsi untuk Approve Payment
        function confirmApprove(paymentId) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: "Apakah Anda yakin ingin menyetujui pembayaran ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'mr-2',
                    cancelButton: 'ml-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading state
                    const approveBtn = document.querySelector(`#approve-form-${paymentId} button`);
                    const originalText = approveBtn.innerHTML;
                    approveBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memproses...
            `;
                    approveBtn.disabled = true;

                    // Submit via AJAX
                    const form = document.getElementById(`approve-form-${paymentId}`);
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#16a34a'
                            }).then(() => {
                                // Reload hanya tabel
                                fetchFilteredBookings();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Gagal!',
                            text: error.message || 'Terjadi kesalahan saat menyetujui pembayaran',
                            icon: 'error',
                            confirmButtonColor: '#dc2626'
                        });
                        // Restore button
                        approveBtn.innerHTML = originalText;
                        approveBtn.disabled = false;
                    });
                }
            });
        }

        // Fungsi untuk menampilkan Reject Modal
        function showRejectModal(paymentId) {
            const modal = document.getElementById(`rejectModal-${paymentId}`);
            if (modal) {
                // Show modal
                modal.classList.remove('hidden');
                modal.style.display = 'block';

                // Focus ke textarea ketika modal terbuka
                setTimeout(() => {
                    const textarea = document.getElementById(`rejectNote-${paymentId}`);
                    if (textarea) {
                        textarea.focus();
                    }
                }, 150);

                // Disable body scroll
                document.body.style.overflow = 'hidden';
            }
        }

        // Fungsi untuk menyembunyikan Reject Modal
        function hideRejectModal(paymentId) {
            const modal = document.getElementById(`rejectModal-${paymentId}`);
            if (modal) {
                // Hide modal
                modal.classList.add('hidden');
                modal.style.display = 'none';

                // Reset form
                const textarea = document.getElementById(`rejectNote-${paymentId}`);
                if (textarea) {
                    textarea.value = '';
                }

                // Reset form validation
                const form = document.getElementById(`reject-form-${paymentId}`);
                if (form) {
                    form.reset();
                }

                // Enable body scroll kembali
                document.body.style.overflow = '';
            }
        }

        // Fungsi validasi form reject
        function validateRejectForm(event, paymentId) {
            event.preventDefault(); // ✅ cegah submit langsung

            const note = document.getElementById(`rejectNote-${paymentId}`).value.trim();

            if (!note) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Silakan masukkan alasan penolakan.',
                    icon: 'warning',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Mengerti'
                });
                return false;
            }

            if (note.length < 10) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Alasan penolakan minimal 10 karakter.',
                    icon: 'warning',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Mengerti'
                });
                return false;
            }

            Swal.fire({
                title: 'Konfirmasi Penolakan',
                text: 'Apakah Anda yakin ingin menolak pembayaran ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    const submitBtn = document.querySelector(`#reject-form-${paymentId} button[type="submit"]`);
                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin h-4 w-4 mr-1 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;

                    // Submit via AJAX
                    const form = document.getElementById(`reject-form-${paymentId}`);
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide modal
                            hideRejectModal(paymentId);

                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#16a34a'
                            }).then(() => {
                                // Reload hanya tabel
                                fetchFilteredBookings();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Gagal!',
                            text: error.message || 'Terjadi kesalahan saat menolak pembayaran',
                            icon: 'error',
                            confirmButtonColor: '#dc2626'
                        });
                        // Restore button
                        submitBtn.innerHTML = originalHTML;
                        submitBtn.disabled = false;
                    });
                }
            });

            return false;
        }

        // Event listener untuk klik di luar modal reject
        document.addEventListener('click', function(event) {
            // Cek semua modal reject yang sedang terbuka
            const openModals = document.querySelectorAll('[id^="rejectModal-"]:not(.hidden)');

            openModals.forEach(modal => {
                if (event.target === modal) {
                    const paymentId = modal.id.replace('rejectModal-', '');
                    hideRejectModal(paymentId);
                }
            });
        });

        // Event listener untuk ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // Tutup semua modal reject yang terbuka
                const openModals = document.querySelectorAll('[id^="rejectModal-"]:not(.hidden)');
                openModals.forEach(modal => {
                    const paymentId = modal.id.replace('rejectModal-', '');
                    hideRejectModal(paymentId);
                });

                // Tutup juga modal attachment jika terbuka
                const attachmentModals = document.querySelectorAll('[x-data="attachmentModal()"]');
                attachmentModals.forEach(modal => {
                    const alpineComponent = Alpine.$data(modal);
                    if (alpineComponent && alpineComponent.isOpen) {
                        alpineComponent.closeModal();
                    }
                });
            }
        });

        // Fungsi utilitas untuk mendapatkan payment ID dari element
        function getPaymentIdFromElement(element) {
            let currentElement = element;
            while (currentElement && !currentElement.id?.startsWith('rejectModal-')) {
                currentElement = currentElement.parentElement;
            }
            return currentElement ? currentElement.id.replace('rejectModal-', '') : null;
        }

        document.addEventListener('DOMContentLoaded', function() {
             // Fungsi untuk mengambil data booking terfilter
            window.fetchFilteredBookings = function() {
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
            };
            
            // local reference for convenience
            const fetchFilteredBookings = window.fetchFilteredBookings;

            const defaultStartDate = new Date('{{ $startDate ?? now()->format('Y-m-d') }}');
            const defaultEndDate = new Date('{{ $endDate ?? now()->addMonth()->format('Y-m-d') }}');

            // Initialize Flatpickr with persistence
            const datePicker = DateFilterPersistence.initFlatpickr('payment-pay', {
                defaultStartDate: defaultStartDate,
                defaultEndDate: defaultEndDate,
                maxRangeDays: 31,
                onChange: function(selectedDates, dateStr, instance) {
                    fetchFilteredBookings();
                },
                onClose: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 0) {
                        fetchFilteredBookings();
                    }
                }
            });

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

        });



        // Fungsi menampilkan modal pembatalan (improved)
        function showCancelModal(paymentId) {
            const modal = document.getElementById(`cancelModal-${paymentId}`);

            if (modal) {
                // Show modal
                modal.classList.remove('hidden');
                modal.style.display = 'block';

                // Fokus ke select
                setTimeout(() => {
                    const select = document.getElementById(`cancelReason-${paymentId}`);
                    if (select) select.focus();
                }, 150);

                // Disable body scroll
                document.body.style.overflow = 'hidden';
            }
        }

        // Fungsi menyembunyikan modal pembatalan (improved)
        function hideCancelModal(paymentId) {
            const modal = document.getElementById(`cancelModal-${paymentId}`);

            if (modal) {
                // Hide modal
                modal.classList.add('hidden');
                modal.style.display = 'none';

                // Reset form
                const form = document.getElementById(`cancel-form-${paymentId}`);
                if (form) {
                    form.reset();
                }

                // Reset custom reason container
                const customContainer = document.getElementById(`customReasonContainer-${paymentId}`);
                if (customContainer) {
                    customContainer.classList.add('hidden');
                }

                const customReason = document.getElementById(`customCancelReason-${paymentId}`);
                if (customReason) {
                    customReason.required = false;
                }

                // Enable body scroll
                document.body.style.overflow = '';
            }
        }

        // Fungsi untuk toggle input alasan custom
        function toggleCustomReason(paymentId) {
            const reasonSelect = document.getElementById(`cancelReason-${paymentId}`);
            const customContainer = document.getElementById(`customReasonContainer-${paymentId}`);
            const customReason = document.getElementById(`customCancelReason-${paymentId}`);

            if (reasonSelect.value === 'other') {
                customContainer.classList.remove('hidden');
                customReason.required = true;

                // Focus ke textarea
                setTimeout(() => {
                    customReason.focus();
                }, 100);
            } else {
                customContainer.classList.add('hidden');
                customReason.required = false;
                customReason.value = '';
            }
        }

        // Validasi form pembatalan
        function validateCancelForm(event, paymentId) {
            event.preventDefault();

            const reasonSelect = document.getElementById(`cancelReason-${paymentId}`);
            const customReason = document.getElementById(`customCancelReason-${paymentId}`);
            const refundAmount = document.getElementById(`refundAmount-${paymentId}`);

            if (!reasonSelect.value) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Silakan pilih alasan pembatalan.',
                    icon: 'warning',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Mengerti'
                });
                reasonSelect.focus();
                return false;
            }

            if (reasonSelect.value === 'other' && !customReason.value.trim()) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Silakan jelaskan alasan pembatalan.',
                    icon: 'warning',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Mengerti'
                });
                customReason.focus();
                return false;
            }

            // Validasi jumlah refund
            const refundValue = refundAmount.value.replace(/[^\d]/g, '');
            if (!refundValue || parseInt(refundValue) <= 0) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Silakan masukkan jumlah pengembalian dana yang valid.',
                    icon: 'warning',
                    confirmButtonColor: '#3b82f6',
                    confirmButtonText: 'Mengerti'
                });
                refundAmount.focus();
                return false;
            }

            // Konfirmasi akhir
            Swal.fire({
                title: 'Konfirmasi Pembatalan',
                text: 'Apakah Anda yakin ingin membatalkan booking ini? Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    const submitBtn = document.querySelector(`#cancel-form-${paymentId} button[type="submit"]`);
                    const originalHTML = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin h-4 w-4 mr-1 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    `;

                    // Submit via AJAX
                    const form = document.getElementById(`cancel-form-${paymentId}`);
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Hide modal
                            hideCancelModal(paymentId);

                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#16a34a'
                            }).then(() => {
                                // Reload hanya tabel
                                fetchFilteredBookings();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Gagal!',
                            text: error.message || 'Terjadi kesalahan saat membatalkan booking',
                            icon: 'error',
                            confirmButtonColor: '#dc2626'
                        });
                        // Restore button
                        submitBtn.innerHTML = originalHTML;
                        submitBtn.disabled = false;
                    });
                }
            });

            return false;
        }

        // Event listener untuk klik di luar modal cancel
        document.addEventListener('click', function(event) {
            // Cek semua modal cancel yang sedang terbuka
            const openModals = document.querySelectorAll('[id^="cancelModal-"]:not(.hidden)');

            openModals.forEach(modal => {
                if (event.target === modal) {
                    const paymentId = modal.id.replace('cancelModal-', '');
                    hideCancelModal(paymentId);
                }
            });
        });

        // Event listener untuk ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // Tutup semua modal cancel yang terbuka
                const openModals = document.querySelectorAll('[id^="cancelModal-"]:not(.hidden)');
                openModals.forEach(modal => {
                    const paymentId = modal.id.replace('cancelModal-', '');
                    hideCancelModal(paymentId);
                });

                // Tutup modal edit payment date
                const editPaymentModals = document.querySelectorAll('[id^="editPaymentDateModal-"]:not(.hidden)');
                editPaymentModals.forEach(modal => {
                    const paymentId = modal.id.replace('editPaymentDateModal-', '');
                    hideEditPaymentDateModal(paymentId);
                });

                // Tutup modal edit check-in/out
                const editCheckInOutModals = document.querySelectorAll('[id^="editCheckInOutModal-"]:not(.hidden)');
                editCheckInOutModals.forEach(modal => {
                    const paymentId = modal.id.replace('editCheckInOutModal-', '');
                    hideEditCheckInOutModal(paymentId);
                });
            }
        });

        // Fungsi untuk menampilkan modal edit payment date
        function showEditPaymentDateModal(paymentId, currentDate, checkInDate) {
            const modal = document.getElementById(`editPaymentDateModal-${paymentId}`);
            const input = document.getElementById(`payment_date-${paymentId}`);

            if (modal && input) {
                // Set current value
                input.value = currentDate;

                // Set max to check-in date (backdate from check-in)
                if (checkInDate) {
                    input.max = checkInDate;
                }

                // Show modal
                modal.classList.remove('hidden');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';

                // Focus input
                setTimeout(() => input.focus(), 150);
            }
        }

        // Fungsi untuk menyembunyikan modal edit payment date
        function hideEditPaymentDateModal(paymentId) {
            const modal = document.getElementById(`editPaymentDateModal-${paymentId}`);
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Fungsi untuk menampilkan modal edit check-in/check-out
        function showEditCheckInOutModal(paymentId, checkIn, checkOut) {
            const modal = document.getElementById(`editCheckInOutModal-${paymentId}`);
            const checkInInput = document.getElementById(`check_in-${paymentId}`);
            const checkOutInput = document.getElementById(`check_out-${paymentId}`);

            if (modal && checkInInput) {
                // Set current values
                checkInInput.value = checkIn;
                if (checkOut && checkOutInput) {
                    checkOutInput.value = checkOut;
                }

                // Set max to current check-in date (backdate from current date)
                // Check-out can be freely selected without restriction
                if (checkIn) {
                    checkInInput.max = checkIn;
                }

                // Show modal
                modal.classList.remove('hidden');
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';

                // Focus input
                setTimeout(() => checkInInput.focus(), 150);
            }
        }

        // Fungsi untuk menyembunyikan modal edit check-in/out
        function hideEditCheckInOutModal(paymentId) {
            const modal = document.getElementById(`editCheckInOutModal-${paymentId}`);
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        // Event listener untuk klik di luar modal
        document.addEventListener('click', function(event) {
            // Edit payment date modals
            const editPaymentModals = document.querySelectorAll('[id^="editPaymentDateModal-"]:not(.hidden)');
            editPaymentModals.forEach(modal => {
                if (event.target === modal) {
                    const paymentId = modal.id.replace('editPaymentDateModal-', '');
                    hideEditPaymentDateModal(paymentId);
                }
            });

            // Edit check-in/out modals
            const editCheckInOutModals = document.querySelectorAll('[id^="editCheckInOutModal-"]:not(.hidden)');
            editCheckInOutModals.forEach(modal => {
                if (event.target === modal) {
                    const paymentId = modal.id.replace('editCheckInOutModal-', '');
                    hideEditCheckInOutModal(paymentId);
                }
            });
        });
    </script>
</x-app-layout>
