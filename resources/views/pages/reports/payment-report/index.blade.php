<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-emerald-600">
                    Laporan Keuangan Pembayaran
                </h1>
                <p class="text-gray-600 mt-1">Laporan pemasukan berdasarkan tanggal pembayaran diterima</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                <button onclick="printReport()"
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <button onclick="exportReport()"
                    class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Ekspor ke Excel
                </button>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <form id="filterForm" class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <!-- Search -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-9"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search" name="search" placeholder="Order ID, Kode Transaksi, Nama..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <!-- Date Range -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Tanggal Pembayaran</label>
                        <div class="relative">
                            <input type="text" id="date_picker" placeholder="Pilih rentang tanggal" data-input
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                            <input type="hidden" id="start_date" name="start_date" value="{{ $startDate }}">
                            <input type="hidden" id="end_date" name="end_date" value="{{ $endDate }}">
                        </div>
                    </div>

                    <!-- Property Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Properti</label>
                        <select id="property_id" name="property_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">Semua Properti</option>
                            @foreach ($properties as $property)
                                <option value="{{ $property->idrec }}"
                                    {{ $propertyId == $property->idrec ? 'selected' : '' }}>
                                    {{ $property->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Per Page -->
                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-2">
                        <label for="per_page" class="text-sm text-gray-600">Show:</label>
                        <select name="per_page" id="per_page"
                            class="border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm">
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm text-gray-600">entries</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Report Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No.</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Payment Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order ID</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Property & Room</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tenant Info</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Period</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Room Price</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Service Fee</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Grand Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Payment Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Verification By</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Verification Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Notes</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Transaction Code</th>
                        </tr>
                    </thead>
                    <tbody id="reportTableBody" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Loading payment report data...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                <!-- Pagination will be inserted here -->
            </div>
        </div>
    </div>

    <script src="{{ asset('js/date-filter-persistence.js') }}"></script>
    <script>
        let currentPage = 1;
        let searchTimeout;

        document.addEventListener('DOMContentLoaded', function() {
            const defaultStartDate = new Date('{{ $startDate }}');
            const defaultEndDate = new Date('{{ $endDate }}');

            // Initialize Flatpickr with persistence
            const datePicker = DateFilterPersistence.initFlatpickr('payment-report', {
                defaultStartDate: defaultStartDate,
                defaultEndDate: defaultEndDate,
                onChange: function(selectedDates, dateStr, instance) {
                    currentPage = 1;
                    fetchReportData();
                }
            });

            // Auto-load data on page load
            fetchReportData();

            // Listen for select filter changes (instant)
            ['property_id', 'per_page'].forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('change', function() {
                        currentPage = 1;
                        fetchReportData();
                    });
                }
            });

            // Search input with debounce (300ms delay)
            document.getElementById('search').addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    fetchReportData();
                }, 300);
            });

            // Search on Enter key (immediate)
            document.getElementById('search').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    currentPage = 1;
                    fetchReportData();
                }
            });
        });

        function fetchReportData(page = 1) {
            currentPage = page;

            const formData = new FormData();
            formData.append('start_date', document.getElementById('start_date').value);
            formData.append('end_date', document.getElementById('end_date').value);
            formData.append('property_id', document.getElementById('property_id').value);
            formData.append('search', document.getElementById('search').value);
            formData.append('per_page', document.getElementById('per_page').value);
            formData.append('page', page);

            const tbody = document.getElementById('reportTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex justify-center items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Loading data...
                        </div>
                    </td>
                </tr>
            `;

            fetch('{{ route('reports.payment.data') }}?' + new URLSearchParams(Object.fromEntries(formData)))
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderTable(data.data);
                        renderPagination(data.pagination);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="14" class="px-4 py-8 text-center text-red-500">
                                Error loading data. Please try again.
                            </td>
                        </tr>
                    `;
                });
        }

        function renderTable(data) {
            const tbody = document.getElementById('reportTableBody');

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="14" class="px-4 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>No payment records found</span>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = data.map(row => {
                const refundClass = row.is_refund ? 'bg-red-50' : '';
                const refundBadge = row.is_refund ?
                    '<span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full font-semibold">REFUND</span>' : '';

                return `
                <tr class="hover:bg-gray-50 transition-colors ${refundClass}">
                    <td class="px-4 py-3 text-sm text-gray-900">${row.no}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${row.payment_date}</td>
                    <td class="px-4 py-3 text-sm font-medium text-blue-600">${row.order_id}${refundBadge}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">
                        <div class="font-medium">${row.property_name}</div>
                        <div class="text-gray-600">${row.room_name || row.room_number}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">
                        <div class="font-medium">${row.tenant_name}</div>
                        <div class="text-gray-600 text-xs">${row.mobile_number}</div>
                        <div class="text-gray-600 text-xs">${row.email}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        <div class="text-xs">
                            <div><span class="font-medium">In:</span> ${row.check_in}</div>
                            <div><span class="font-medium">Out:</span> ${row.check_out}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">${row.room_price}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${row.service_fee}</td>
                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">${row.grand_total}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${row.payment_status}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${row.verified_by}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${row.verified_at}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${row.notes || '-'}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${row.transaction_code}</td>
                </tr>
            `}).join('');
        }

        function renderPagination(pagination) {
            const container = document.getElementById('paginationContainer');

            if (pagination.last_page <= 1) {
                container.innerHTML = `
                    <div class="text-sm text-gray-700">
                        Showing ${pagination.total} ${pagination.total === 1 ? 'entry' : 'entries'}
                    </div>
                `;
                return;
            }

            let paginationHTML = `
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing page ${pagination.current_page} of ${pagination.last_page} (${pagination.total} total entries)
                    </div>
                    <div class="flex gap-2">
            `;

            // Previous button
            if (pagination.current_page > 1) {
                paginationHTML += `
                    <button onclick="fetchReportData(${pagination.current_page - 1})"
                        class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">
                        Previous
                    </button>
                `;
            }

            // Page numbers
            for (let i = 1; i <= pagination.last_page; i++) {
                if (
                    i === 1 ||
                    i === pagination.last_page ||
                    (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)
                ) {
                    const activeClass = i === pagination.current_page ?
                        'bg-green-600 text-white' :
                        'border border-gray-300 hover:bg-gray-50';
                    paginationHTML += `
                        <button onclick="fetchReportData(${i})"
                            class="px-3 py-1 rounded-md text-sm ${activeClass}">
                            ${i}
                        </button>
                    `;
                } else if (
                    i === pagination.current_page - 3 ||
                    i === pagination.current_page + 3
                ) {
                    paginationHTML += `<span class="px-2 py-1 text-gray-500">...</span>`;
                }
            }

            // Next button
            if (pagination.current_page < pagination.last_page) {
                paginationHTML += `
                    <button onclick="fetchReportData(${pagination.current_page + 1})"
                        class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">
                        Next
                    </button>
                `;
            }

            paginationHTML += `
                    </div>
                </div>
            `;

            container.innerHTML = paginationHTML;
        }

        function printReport() {
            const reportTitle = 'Laporan Keuangan Pembayaran';

            // Get filter info
            let filterInfo = '';

            // Date range
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            if (startDate && endDate) {
                filterInfo += `<p><strong>Periode:</strong> ${formatDate(startDate)} - ${formatDate(endDate)}</p>`;
            }

            // Property filter
            const propertySelect = document.getElementById('property_id');
            const selectedProperty = propertySelect.options[propertySelect.selectedIndex].text;
            if (propertySelect.value) {
                filterInfo += `<p><strong>Properti:</strong> ${selectedProperty}</p>`;
            }

            // Search filter
            const searchValue = document.getElementById('search').value;
            if (searchValue) {
                filterInfo += `<p><strong>Pencarian:</strong> ${searchValue}</p>`;
            }

            // Get table content
            const tableContent = document.getElementById('reportTableBody').innerHTML;

            // Create print window
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>${reportTitle}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 20px;
                            color: #333;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 20px;
                            border-bottom: 2px solid #059669;
                            padding-bottom: 10px;
                        }
                        .header h1 {
                            margin: 0;
                            color: #059669;
                            font-size: 24px;
                        }
                        .header p {
                            margin: 5px 0;
                            color: #666;
                        }
                        .filter-info {
                            background: #F3F4F6;
                            padding: 15px;
                            border-radius: 8px;
                            margin-bottom: 20px;
                        }
                        .filter-info p {
                            margin: 5px 0;
                            font-size: 14px;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 20px;
                            font-size: 10px;
                        }
                        th {
                            background-color: #059669;
                            color: white;
                            padding: 10px 6px;
                            text-align: left;
                            font-size: 9px;
                            font-weight: 600;
                            text-transform: uppercase;
                            border: 1px solid #047857;
                        }
                        td {
                            padding: 8px 6px;
                            border: 1px solid #E5E7EB;
                            font-size: 10px;
                        }
                        tr:nth-child(even) {
                            background-color: #F9FAFB;
                        }
                        tr.bg-red-50 {
                            background-color: #FEE2E2 !important;
                        }
                        .footer {
                            margin-top: 30px;
                            text-align: center;
                            font-size: 12px;
                            color: #666;
                            border-top: 1px solid #E5E7EB;
                            padding-top: 15px;
                        }
                        @media print {
                            body {
                                margin: 0;
                                padding: 10px;
                            }
                            .filter-info {
                                background: #F9FAFB !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            th {
                                background-color: #059669 !important;
                                color: white !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            tr:nth-child(even) {
                                background-color: #F9FAFB !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                            tr.bg-red-50 {
                                background-color: #FEE2E2 !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>${reportTitle}</h1>
                        <p>Dicetak pada: ${new Date().toLocaleString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</p>
                    </div>
                    ${filterInfo ? '<div class="filter-info"><strong>Filter yang Diterapkan:</strong>' + filterInfo + '</div>' : ''}
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tgl Bayar</th>
                                <th>Order ID</th>
                                <th>Property & Room</th>
                                <th>Tenant Info</th>
                                <th>Period</th>
                                <th>Room Price</th>
                                <th>Service Fee</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Verified By</th>
                                <th>Verified At</th>
                                <th>Notes</th>
                                <th>Kode Trx</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableContent}
                        </tbody>
                    </table>
                    <div class="footer">
                        <p>Booking Management System - Laporan Keuangan Pembayaran</p>
                    </div>
                </body>
                </html>
            `);
            printWindow.document.close();

            // Wait for content to load then print
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
            };
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        function exportReport() {
            const params = new URLSearchParams({
                start_date: document.getElementById('start_date').value,
                end_date: document.getElementById('end_date').value,
                property_id: document.getElementById('property_id').value,
                search: document.getElementById('search').value
            });

            window.location.href = '{{ route('reports.payment.export') }}?' + params.toString();

            // Show success message
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Exporting payment report...',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }
    </script>
</x-app-layout>
