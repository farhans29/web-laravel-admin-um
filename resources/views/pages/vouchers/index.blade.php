<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1
                class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-indigo-500 dark:from-blue-400 dark:to-indigo-400">
                Master Vouchers
            </h1>
            <div class="mt-4 md:mt-0">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200"
                    type="button" onclick="openCreateModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Voucher
                </button>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cari Voucher
                    </label>
                    <input type="text" id="search" placeholder="Cari kode atau nama voucher..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select id="status_filter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Per Page Selector -->
                <div class="md:col-start-3">
                    <div class="flex items-center justify-end gap-3">
                        <label for="per_page"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            Tampilkan :
                        </label>
                        <select id="per_page"
                            class="min-w-[120px] border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            <option value="8">8</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div id="vouchers-table-container">
            @include('pages.vouchers.partials.voucher_table')
        </div>
    </div>

    <!-- Voucher Modal -->
    <div id="voucherModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-800 dark:text-white">Tambah Voucher</h3>
                    <button type="button" onclick="closeModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="voucherForm" class="p-6">
                    <input type="hidden" id="voucher_id" name="voucher_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Kode Voucher <span class="text-red-500">*</span>
                                <span class="text-xs text-gray-500">(4-12 karakter)</span>
                            </label>
                            <input type="text" id="code" name="code" required minlength="4" maxlength="12"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white uppercase"
                                placeholder="Contoh: HPNY2026" style="text-transform: uppercase;">
                        </div>

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Voucher <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Contoh: New Year Special">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="description" name="description" rows="2"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Contoh: Get 10% off"></textarea>
                        </div>

                        <!-- Discount Percentage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Diskon (%) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="discount_percentage" name="discount_percentage" required
                                min="0" max="100" step="0.01"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="10">
                        </div>

                        <!-- Max Discount Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Maksimal Diskon (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="max_discount_amount" name="max_discount_amount" required
                                min="0" step="1000"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="100000">
                        </div>

                        <!-- Max Total Usage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Maksimal Penggunaan Total <span class="text-gray-500 text-xs">(0 = unlimited)</span>
                            </label>
                            <input type="number" id="max_total_usage" name="max_total_usage" min="0"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="10" value="0">
                        </div>

                        <!-- Max Usage Per User -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Maksimal Penggunaan Per User <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="max_usage_per_user" name="max_usage_per_user" required
                                min="1"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="1" value="1">
                        </div>

                        <!-- Date Range Picker -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Periode Berlaku <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="date_range" name="date_range" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Pilih tanggal berlaku" readonly>
                            <input type="hidden" id="valid_from" name="valid_from">
                            <input type="hidden" id="valid_to" name="valid_to">
                        </div>

                        <!-- Min Transaction Amount -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Minimal Transaksi (Rp)
                            </label>
                            <input type="number" id="min_transaction_amount" name="min_transaction_amount"
                                min="0" step="1000"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="500000" value="0">
                        </div>

                        <!-- Scope Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Scope Type <span class="text-red-500">*</span>
                            </label>
                            <select id="scope_type" name="scope_type" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="global">Global</option>
                                <option value="property">Property</option>
                                <option value="room">Room</option>
                            </select>
                        </div>

                        <!-- Status Toggle (Only visible on edit) -->
                        <div id="status_toggle_container" style="display: none;">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <div class="flex items-center space-x-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="status_toggle" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                </label>
                                <span id="status_label"
                                    class="text-sm font-medium text-gray-900 dark:text-white">Active</span>
                            </div>
                        </div>

                        <!-- Status Hidden Input -->
                        <input type="hidden" id="status" name="status" value="active">
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Re-load dependencies after body scripts -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <script>
            // Toast notification helper
            function showToast(message, type = 'success') {
                const bgColor = type === 'success' ? 'linear-gradient(to right, #00b09b, #96c93d)' :
                    type === 'error' ? 'linear-gradient(to right, #ff5f6d, #ffc371)' :
                    type === 'info' ? 'linear-gradient(to right, #4facfe, #00f2fe)' :
                    'linear-gradient(to right, #f857a6, #ff5858)';

                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: bgColor,
                    },
                    stopOnFocus: true,
                    close: true,
                }).showToast();
            }

            // Initialize date range picker
            function initDateRangePicker() {
                // Check if daterangepicker is available
                if (typeof $.fn.daterangepicker === 'undefined') {
                    console.error('Daterangepicker library not loaded');
                    console.log('Available jQuery plugins:', Object.keys($.fn));
                    return false;
                }

                if (typeof moment === 'undefined') {
                    console.error('Moment.js library not loaded');
                    return false;
                }

                try {
                    $('#date_range').daterangepicker({
                        timePicker: true,
                        timePicker24Hour: true,
                        timePickerIncrement: 30,
                        startDate: moment().startOf('day'),
                        endDate: moment().add(7, 'days').endOf('day'),
                        minDate: moment(),
                        showDropdowns: true,
                        autoApply: false,
                        linkedCalendars: true,
                        showCustomRangeLabel: true,
                        alwaysShowCalendars: true,
                        opens: 'center',
                        drops: 'auto',
                        ranges: {
                            'Hari Ini': [moment().startOf('day'), moment().endOf('day')],
                            'Besok': [moment().add(1, 'days').startOf('day'), moment().add(1, 'days').endOf('day')],
                            '7 Hari Kedepan': [moment().startOf('day'), moment().add(6, 'days').endOf('day')],
                            '14 Hari Kedepan': [moment().startOf('day'), moment().add(13, 'days').endOf('day')],
                            '1 Bulan Kedepan': [moment().startOf('day'), moment().add(1, 'months').endOf('day')],
                            '3 Bulan Kedepan': [moment().startOf('day'), moment().add(3, 'months').endOf('day')],
                        },
                        locale: {
                            format: 'DD/MM/YYYY HH:mm',
                            separator: ' - ',
                            applyLabel: 'Terapkan',
                            cancelLabel: 'Batal',
                            fromLabel: 'Dari',
                            toLabel: 'Sampai',
                            customRangeLabel: 'Pilih Tanggal',
                            weekLabel: 'M',
                            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                                'September', 'Oktober', 'November', 'Desember'
                            ],
                            firstDay: 1
                        },
                        autoUpdateInput: false
                    }, function(start, end, label) {
                        console.log('Periode dipilih: ' + start.format('DD/MM/YYYY HH:mm') + ' sampai ' + end.format(
                            'DD/MM/YYYY HH:mm'));
                    });

                    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                        $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format(
                            'DD/MM/YYYY HH:mm'));
                        $('#valid_from').val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
                        $('#valid_to').val(picker.endDate.format('YYYY-MM-DD HH:mm:ss'));
                        showToast('Periode berlaku berhasil dipilih', 'info');
                    });

                    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#valid_from').val('');
                        $('#valid_to').val('');
                    });

                    // Show picker on click
                    $('#date_range').on('click', function() {
                        $(this).data('daterangepicker').show();
                    });

                    console.log('Daterangepicker initialized successfully');
                    return true;
                } catch (error) {
                    console.error('Error initializing daterangepicker:', error);
                    return false;
                }
            }

            // Initialize immediately since scripts are loaded in correct order now
            $(document).ready(function() {
                console.log('Document ready, initializing daterangepicker...');
                console.log('jQuery version:', $.fn.jquery);
                console.log('Moment available:', typeof moment !== 'undefined');
                console.log('Daterangepicker available:', typeof $.fn.daterangepicker !== 'undefined');

                initDateRangePicker();

                // Status toggle handler
                $('#status_toggle').on('change', function() {
                    const isActive = $(this).is(':checked');
                    const status = isActive ? 'active' : 'inactive';
                    $('#status').val(status);
                    $('#status_label').text(isActive ? 'Active' : 'Inactive');
                    $('#status_label').toggleClass('text-blue-600', isActive);
                    $('#status_label').toggleClass('text-red-600', !isActive);
                });
            });

            // Filter functionality
            let debounceTimer;
            $('#search, #status_filter, #per_page').on('change keyup', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(filterVouchers, 300);
            });

            function filterVouchers() {
                const search = $('#search').val();
                const status = $('#status_filter').val();
                const perPage = $('#per_page').val();

                $.ajax({
                    url: '{{ route('vouchers.filter') }}',
                    method: 'GET',
                    data: {
                        search: search,
                        status: status,
                        per_page: perPage
                    },
                    success: function(response) {
                        $('#vouchers-table-container').html(response.html);
                    },
                    error: function(xhr) {
                        console.error('Filter error:', xhr);
                    }
                });
            }

            // Reload table only without full page refresh
            function reloadTable() {
                filterVouchers();
            }

            // Modal functions
            function openCreateModal() {
                $('#modalTitle').text('Tambah Voucher');
                $('#voucherForm')[0].reset();
                $('#voucher_id').val('');
                $('#date_range').val('');
                $('#valid_from').val('');
                $('#valid_to').val('');
                $('#status').val('active'); // Set default to active
                $('#status_toggle_container').hide(); // Hide status toggle on create
                $('#voucherModal').removeClass('hidden').show();
            }

            function openEditModal(id) {
                $.ajax({
                    url: `/vouchers/${id}`,
                    method: 'GET',
                    success: function(response) {
                        const voucher = response.data;
                        $('#modalTitle').text('Edit Voucher');
                        $('#voucher_id').val(voucher.idrec);
                        $('#code').val(voucher.code);
                        $('#name').val(voucher.name);
                        $('#description').val(voucher.description);
                        $('#discount_percentage').val(voucher.discount_percentage);
                        $('#max_discount_amount').val(voucher.max_discount_amount);
                        $('#max_total_usage').val(voucher.max_total_usage);
                        $('#max_usage_per_user').val(voucher.max_usage_per_user);

                        // Set date range picker values
                        const startDate = moment(voucher.valid_from);
                        const endDate = moment(voucher.valid_to);
                        $('#date_range').data('daterangepicker').setStartDate(startDate);
                        $('#date_range').data('daterangepicker').setEndDate(endDate);
                        $('#date_range').val(startDate.format('DD/MM/YYYY HH:mm') + ' - ' + endDate.format(
                            'DD/MM/YYYY HH:mm'));
                        $('#valid_from').val(voucher.valid_from);
                        $('#valid_to').val(voucher.valid_to);

                        $('#min_transaction_amount').val(voucher.min_transaction_amount);
                        $('#scope_type').val(voucher.scope_type);

                        // Set status toggle
                        $('#status').val(voucher.status);
                        const isActive = voucher.status === 'active';
                        $('#status_toggle').prop('checked', isActive);
                        $('#status_label').text(isActive ? 'Active' : 'Inactive');
                        $('#status_label').removeClass('text-blue-600 text-red-600');
                        $('#status_label').addClass(isActive ? 'text-blue-600' : 'text-red-600');
                        $('#status_toggle_container').show(); // Show status toggle on edit

                        $('#voucherModal').removeClass('hidden').show();
                    },
                    error: function(xhr) {
                        showToast('Gagal mengambil data voucher', 'error');
                    }
                });
            }

            function closeModal() {
                $('#voucherModal').addClass('hidden').hide();
            }

            // Form submission
            $('#voucherForm').on('submit', function(e) {
                e.preventDefault();
                const voucherId = $('#voucher_id').val();
                const url = voucherId ? `/vouchers/${voucherId}` : '{{ route('vouchers.store') }}';
                const method = voucherId ? 'PUT' : 'POST';

                const formData = {
                    code: $('#code').val(),
                    name: $('#name').val(),
                    description: $('#description').val(),
                    discount_percentage: $('#discount_percentage').val(),
                    max_discount_amount: $('#max_discount_amount').val(),
                    max_total_usage: $('#max_total_usage').val(),
                    max_usage_per_user: $('#max_usage_per_user').val(),
                    valid_from: $('#valid_from').val(),
                    valid_to: $('#valid_to').val(),
                    min_transaction_amount: $('#min_transaction_amount').val(),
                    scope_type: $('#scope_type').val(),
                    status: $('#status').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            closeModal();
                            showToast(response.message, 'success');
                            setTimeout(() => reloadTable(), 500);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Gagal menyimpan voucher';
                        showToast(errorMsg, 'error');

                        // Show validation errors if any
                        if (xhr.responseJSON?.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(errors => {
                                errors.forEach(error => showToast(error, 'error'));
                            });
                        }
                    }
                });
            });

            // Delete voucher
            function deleteVoucher(id) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus voucher ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/vouchers/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    showToast(response.message, 'success');
                                    setTimeout(() => reloadTable(), 500);
                                }
                            },
                            error: function(xhr) {
                                showToast('Gagal menghapus voucher', 'error');
                            }
                        });
                    }
                });
            }

            // Toggle status
            function toggleStatus(id, currentStatus) {
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                const statusText = newStatus === 'active' ? 'mengaktifkan' : 'menonaktifkan';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin ${statusText} voucher ini?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('vouchers.toggle-status') }}',
                            method: 'POST',
                            data: {
                                id: id,
                                status: newStatus,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    showToast(response.message || 'Status berhasil diubah', 'success');
                                    setTimeout(() => reloadTable(), 500);
                                }
                            },
                            error: function(xhr) {
                                showToast('Gagal mengubah status', 'error');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
