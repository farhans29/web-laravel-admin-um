<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                {{ __('ui.parking_payments') }}
            </h1>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <!-- Filter Section -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <form id="filterForm">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="w-full md:w-1/4">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md sm:text-sm"
                                    placeholder="{{ __('ui.search_parking_placeholder') }}">
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('ui.status') }}:</span>
                            <select name="status" id="statusFilter"
                                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md text-sm">
                                <option value="">{{ __('ui.all_status') }}</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('ui.pending') }}</option>
                                <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>{{ __('ui.waiting_verification') }}</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('ui.paid') }}</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('ui.rejected') }}</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>{{ __('ui.canceled') }}</option>
                            </select>
                        </div>

                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('ui.parking_type') }}:</span>
                            <select name="parking_type" id="parkingTypeFilter"
                                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md text-sm">
                                <option value="">{{ __('ui.all') }}</option>
                                <option value="car" {{ request('parking_type') == 'car' ? 'selected' : '' }}>{{ __('ui.car') }}</option>
                                <option value="motorcycle" {{ request('parking_type') == 'motorcycle' ? 'selected' : '' }}>{{ __('ui.motorcycle') }}</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600 dark:text-gray-400">{{ __('ui.items_per_page') }}</label>
                            <select name="per_page" id="perPageSelect"
                                class="border-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg text-sm">
                                <option value="8" {{ request('per_page', 8) == 8 ? 'selected' : '' }}>8</option>
                                <option value="25" {{ request('per_page', 8) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 8) == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto" id="tableContainer">
                @include('pages.payment.parking.partials.parking-payment_table', ['parkingTransactions' => $parkingTransactions])
            </div>

            @if($parkingTransactions instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="bg-gray-50 dark:bg-gray-800 rounded p-4" id="paginationContainer">
                {{ $parkingTransactions->appends(request()->input())->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Proof + Konfirmasi Modal --}}
    <div id="proofModal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeParkingProofModal()"></div>

        {{-- Dialog --}}
        <div class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl max-h-[95vh] flex flex-col"
                onclick="event.stopPropagation()">

                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-700">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ __('ui.payment_proof') }} - {{ __('ui.order_id') }} #<span id="proofOrderId"></span>
                    </h3>
                    <button onclick="closeParkingProofModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="overflow-y-auto flex-1 p-6" id="proofContent">
                    <div class="flex justify-center items-center h-64">
                        <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Footer with Approve/Reject buttons (shown for waiting status) --}}
                <div id="proofFooter" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-between items-center hidden">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span>{{ __('ui.press_esc_to_close') }}</span>
                    </div>
                    <div class="flex space-x-3">
                        {{-- Approve --}}
                        <button type="button" id="btnApproveParking"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium transition-colors"
                            onclick="confirmApproveParkingPayment()">
                            {{ __('ui.approve') }}
                        </button>
                        {{-- Reject --}}
                        <button type="button" id="btnRejectParking"
                            class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium transition-colors"
                            onclick="openRejectParkingModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>{{ __('ui.reject') }}</span>
                        </button>
                    </div>
                </div>

                {{-- View-only footer (shown for paid/verified status) --}}
                <div id="proofFooterViewOnly" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex justify-end hidden">
                    <button type="button" onclick="closeParkingProofModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                        {{ __('ui.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Reason Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeRejectParkingModal()"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full" onclick="event.stopPropagation()">
                <div class="px-6 py-4 border-b dark:border-gray-700 bg-gradient-to-r from-red-50 to-pink-50 dark:from-gray-700 dark:to-gray-700 rounded-t-xl flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-200">{{ __('ui.reject') }} {{ __('ui.parking_payments') }}</h3>
                    <button onclick="closeRejectParkingModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <form id="rejectForm" class="max-w-full overflow-hidden">
                    <input type="hidden" id="rejectTransactionId">
                    <div class="p-6 space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('ui.confirm_reject_parking_msg') }}
                            <strong class="text-gray-900 dark:text-gray-100" id="rejectOrderIdLabel"></strong>?
                        </p>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                {{ __('ui.rejection_reason') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea id="rejectNotes" rows="4" required minlength="10"
                                class="w-full px-3 py-2 text-sm text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                placeholder="{{ __('ui.enter_rejection_reason') }}"></textarea>
                            <p class="text-xs text-gray-500 mt-1">{{ __('ui.min_10_chars') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-6 space-x-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
                        <button type="button" onclick="closeRejectParkingModal()"
                            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                            {{ __('ui.cancel') }}
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('ui.confirm_reject') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentProofTransactionId = null;
        let currentProofOrderId = null;

        // Open proof modal with images loaded via AJAX
        function openParkingProofModal(id, orderId, viewOnly = false) {
            currentProofTransactionId = id;
            currentProofOrderId = orderId;

            const modal = document.getElementById('proofModal');
            const content = document.getElementById('proofContent');
            const footer = document.getElementById('proofFooter');
            const footerViewOnly = document.getElementById('proofFooterViewOnly');

            modal.classList.remove('hidden');
            document.getElementById('proofOrderId').textContent = orderId;
            document.body.style.overflow = 'hidden';

            // Show appropriate footer
            if (viewOnly) {
                footer.classList.add('hidden');
                footerViewOnly.classList.remove('hidden');
            } else {
                footer.classList.remove('hidden');
                footerViewOnly.classList.add('hidden');
            }

            // Loading spinner
            content.innerHTML = `
                <div class="flex justify-center items-center h-64">
                    <svg class="animate-spin h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>`;

            fetch(`/payment/parking/proof/${id}`, { headers: { 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.images.length > 0) {
                        content.innerHTML = data.images.map(img => {
                            const imageSignatures = ['/9j/', 'iVBORw0KGgo', 'R0lGODdh', 'R0lGODlh', 'UklGR', 'Qk02'];
                            let isPdf = img.image.startsWith('JVBERi0') || img.image_type === 'pdf';
                            let isImage = imageSignatures.some(sig => img.image.startsWith(sig)) || ['jpeg','jpg','png','gif','webp'].includes(img.image_type);

                            if (isPdf) {
                                return `<div class="mb-4 h-[70vh] w-full">
                                    <iframe src="data:application/pdf;base64,${img.image}" class="w-full h-full border border-gray-200 rounded-lg" frameborder="0"></iframe>
                                    ${img.description ? `<p class="text-sm text-gray-500 mt-1">${img.description}</p>` : ''}
                                </div>`;
                            } else if (isImage) {
                                const src = `data:image/${img.image_type || 'jpeg'};base64,${img.image}`;
                                return `<div class="mb-4">
                                    <img src="${src}" alt="{{ __('ui.payment_proof') }}" class="mx-auto max-h-[70vh] max-w-full object-contain rounded-lg shadow" />
                                    ${img.description ? `<p class="text-sm text-gray-500 mt-1 text-center">${img.description}</p>` : ''}
                                </div>`;
                            } else {
                                return `<div class="text-center py-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-4 text-lg font-medium text-gray-900">{{ __('ui.unsupported_file') }}</h3>
                                </div>`;
                            }
                        }).join('');
                    } else {
                        content.innerHTML = `<div class="text-center py-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-4 text-gray-500">{{ __('ui.no_proof_available') }}</p>
                        </div>`;
                    }
                })
                .catch(() => {
                    content.innerHTML = `<div class="text-center py-10 text-red-500">{{ __('ui.error_loading') }}</div>`;
                });
        }

        function closeParkingProofModal() {
            document.getElementById('proofModal').classList.add('hidden');
            document.body.style.overflow = '';
            currentProofTransactionId = null;
            currentProofOrderId = null;
        }

        // Approve with SweetAlert confirm
        function confirmApproveParkingPayment() {
            if (!currentProofTransactionId) return;
            const id = currentProofTransactionId;

            Swal.fire({
                title: '{{ __("ui.confirm_approve") }}',
                text: '{{ __("ui.confirm_approve_msg") }}',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: '{{ __("ui.yes_approve") }}',
                cancelButtonText: '{{ __("ui.cancel") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const btn = document.getElementById('btnApproveParking');
                    const origHTML = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{ __('ui.processing') }}`;

                    fetch(`/payment/parking/approve/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            closeParkingProofModal();
                            Swal.fire({ title: '{{ __("ui.success") }}', text: data.message, icon: 'success', confirmButtonColor: '#16a34a' })
                                .then(() => applyFilters());
                        } else {
                            throw new Error(data.message || '{{ __("ui.error_loading") }}');
                        }
                    })
                    .catch(error => {
                        Swal.fire({ title: '{{ __("ui.failed") }}', text: error.message, icon: 'error', confirmButtonColor: '#dc2626' });
                        btn.innerHTML = origHTML;
                        btn.disabled = false;
                    });
                }
            });
        }

        // Open reject reason modal (from proof modal footer)
        function openRejectParkingModal() {
            document.getElementById('rejectTransactionId').value = currentProofTransactionId;
            document.getElementById('rejectOrderIdLabel').textContent = currentProofOrderId;
            document.getElementById('rejectNotes').value = '';
            document.getElementById('rejectModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('rejectNotes').focus(), 150);
        }

        function closeRejectParkingModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Reject form submit
        document.getElementById('rejectForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('rejectTransactionId').value;
            const notes = document.getElementById('rejectNotes').value.trim();

            if (notes.length < 10) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: '{{ __("ui.min_10_chars") }}', showConfirmButton: false, timer: 3000 });
                return;
            }

            Swal.fire({
                title: '{{ __("ui.confirm_reject") }}',
                text: '{{ __("ui.confirm_reject_msg") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: '{{ __("ui.yes_reject") }}',
                cancelButtonText: '{{ __("ui.cancel") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const submitBtn = document.querySelector('#rejectForm button[type="submit"]');
                    const origHTML = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<svg class="animate-spin h-4 w-4 mr-1 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{ __('ui.processing') }}`;

                    fetch(`/payment/parking/reject/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ notes: notes })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            closeRejectParkingModal();
                            closeParkingProofModal();
                            Swal.fire({ title: '{{ __("ui.success") }}', text: data.message, icon: 'success', confirmButtonColor: '#16a34a' })
                                .then(() => applyFilters());
                        } else {
                            throw new Error(data.message || '{{ __("ui.error_loading") }}');
                        }
                    })
                    .catch(error => {
                        Swal.fire({ title: '{{ __("ui.failed") }}', text: error.message, icon: 'error', confirmButtonColor: '#dc2626' });
                        submitBtn.innerHTML = origHTML;
                        submitBtn.disabled = false;
                    });
                }
            });
        });

        // AJAX filter using POST /filter endpoint
        function applyFilters() {
            const search = document.getElementById('searchInput')?.value || '';
            const status = document.getElementById('statusFilter')?.value || '';
            const parkingType = document.getElementById('parkingTypeFilter')?.value || '';
            const perPage = document.getElementById('perPageSelect')?.value || '8';

            fetch('/payment/parking/filter', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ search, status, parking_type: parkingType, per_page: perPage })
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('tableContainer').innerHTML = data.html;
                const pag = document.getElementById('paginationContainer');
                if (pag) pag.innerHTML = data.pagination || '';

                const urlParams = new URLSearchParams();
                if (search) urlParams.append('search', search);
                if (status) urlParams.append('status', status);
                if (parkingType) urlParams.append('parking_type', parkingType);
                if (perPage) urlParams.append('per_page', perPage);
                window.history.pushState({}, '', `${window.location.pathname}?${urlParams.toString()}`);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            let t;
            document.getElementById('searchInput')?.addEventListener('input', () => { clearTimeout(t); t = setTimeout(applyFilters, 500); });
            document.getElementById('statusFilter')?.addEventListener('change', applyFilters);
            document.getElementById('parkingTypeFilter')?.addEventListener('change', applyFilters);
            document.getElementById('perPageSelect')?.addEventListener('change', applyFilters);
        });

        // ESC key handler
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!document.getElementById('rejectModal').classList.contains('hidden')) {
                    closeRejectParkingModal();
                } else if (!document.getElementById('proofModal').classList.contains('hidden')) {
                    closeParkingProofModal();
                }
            }
        });
    </script>
</x-app-layout>
