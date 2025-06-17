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
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Date</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="transactionTableBody">
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaction->order_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->user_name }}<br>
                                    <span class="text-xs text-gray-400">{{ $transaction->user_email }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->property_name }}<br>
                                    <span class="text-xs text-gray-400">{{ $transaction->room_name }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp{{ number_format($transaction->grandtotal_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ ucfirst($transaction->payment_method) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                    @if ($transaction->paid_at)
                                        <div>{{ $transaction->paid_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $transaction->paid_at->format('H:i') }}
                                        </div>
                                    @elseif($transaction->payment && $transaction->payment->verified_at)
                                        <div>{{ $transaction->payment->verified_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">
                                            {{ $transaction->payment->verified_at->format('H:i') }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @php
                                        $status = $transaction->transaction_status;
                                        $statusStyles = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'waiting' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'canceled' => 'bg-red-100 text-red-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'expired' => 'bg-gray-200 text-gray-700',
                                        ];
                                        $badgeStyle = $statusStyles[$status] ?? 'bg-gray-100 text-gray-800';
                                        $hasTooltip = $status === 'rejected' && isset($transaction->payment->notes);
                                    @endphp

                                    <div class="relative inline-block group">
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeStyle }} {{ $hasTooltip ? 'cursor-help' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>

                                        @if ($hasTooltip)
                                            <div
                                                class="absolute z-10 w-64 p-2 mt-1 text-xs text-gray-600 bg-white border border-gray-300 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform -translate-x-1/2 left-1/2">
                                                {{ $transaction->payment->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    @if (in_array($transaction->transaction_status, ['waiting']))
                                        <div x-data="attachmentModal()" class="relative group">
                                            <button type="button"
                                                class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded"
                                                @click="openModal('{{ $transaction->attachment }}', '{{ $transaction->order_id }}')"
                                                title="View Attachment">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>

                                            </button>

                                            <!-- Modal backdrop -->
                                            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 transition-opacity"
                                                x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-out duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak>
                                            </div>

                                            <!-- Modal dialog -->
                                            <div class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center p-4"
                                                role="dialog" aria-modal="true" x-show="isOpen"
                                                x-transition:enter="transition ease-in-out duration-300"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in-out duration-200"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95" x-cloak>

                                                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-4xl max-h-[95vh] flex flex-col"
                                                    @click.outside="closeModal" @keydown.escape.window="closeModal">

                                                    <!-- Modal header -->
                                                    <div
                                                        class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                                                        <h3 class="text-lg font-semibold text-gray-800">
                                                            Payment Proof for Order #<span x-text="orderId"></span>
                                                        </h3>
                                                        <button @click="closeModal"
                                                            class="text-gray-500 hover:text-gray-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                                fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Modal content -->
                                                    <div class="overflow-y-auto flex-1 p-6">
                                                        <template x-if="isLoading">
                                                            <div class="flex justify-center items-center h-64">
                                                                <svg class="animate-spin h-12 w-12 text-blue-500"
                                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                    </path>
                                                                </svg>
                                                            </div>
                                                        </template>

                                                        <template x-if="!isLoading && attachmentType === 'image'">
                                                            <img :src="'data:image/jpeg;base64,' + attachmentData"
                                                                alt="Payment Proof"
                                                                class="mx-auto max-h-[70vh] max-w-full object-contain">
                                                        </template>

                                                        <template x-if="!isLoading && attachmentType === 'pdf'">
                                                            <div class="h-[70vh] w-full">
                                                                <iframe
                                                                    :src="'data:application/pdf;base64,' + attachmentData"
                                                                    class="w-full h-full border border-gray-200"
                                                                    frameborder="0"></iframe>
                                                            </div>
                                                        </template>

                                                        <template x-if="!isLoading && attachmentType === 'unknown'">
                                                            <div class="text-center py-10">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-16 w-16 mx-auto text-gray-400"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <h3 class="mt-4 text-lg font-medium text-gray-900">
                                                                    Unsupported File Type</h3>
                                                                <p class="mt-2 text-sm text-gray-500">This file type
                                                                    cannot be previewed. Please re-upload.</p>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div
                                                        class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                                                        <div class="text-sm text-gray-500">
                                                            <span>Press ESC or click outside to close</span>
                                                        </div>
                                                        <div class="flex space-x-3">
                                                            <!-- Tombol Approve -->
                                                            <form id="approve-form-{{ $transaction->idrec }}"
                                                                action="{{ route('admin.payments.approve', $transaction->idrec) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="button"
                                                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium"
                                                                    onclick="confirmApprove({{ $transaction->idrec }})">
                                                                    Approve
                                                                </button>
                                                            </form>

                                                            <!-- Reject Button with Note -->
                                                            <button type="button"
                                                                class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium"
                                                                onclick="showRejectModal({{ $transaction->idrec }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-5 w-5 mr-1" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor"
                                                                    stroke-width="2">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                <span>Reject</span>
                                                            </button>
                                                        </div>

                                                        <!-- Reject Modal -->
                                                        <div id="rejectModal"
                                                            class="hidden fixed inset-0 backdrop-blur-sm bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50">
                                                            <div class="relative mx-auto p-5 w-full max-w-md">
                                                                <div class="relative bg-white rounded-lg shadow">
                                                                    <!-- Modal header -->
                                                                    <div class="px-6 py-4 border-b rounded-t">
                                                                        <h3
                                                                            class="text-xl font-semibold text-gray-900">
                                                                            Reject Payment
                                                                        </h3>
                                                                    </div>

                                                                    <!-- Modal body -->
                                                                    <form id="reject-form-{{ $transaction->idrec }}"
                                                                        action="{{ route('admin.payments.reject', $transaction->idrec) }}"
                                                                        method="POST"
                                                                        onsubmit="return validateRejectForm()">
                                                                        @csrf
                                                                        <div class="p-6 space-y-4">
                                                                            <p class="text-gray-600">
                                                                                Are you sure you want to reject this
                                                                                payment?
                                                                            </p>
                                                                            <div>
                                                                                <textarea id="rejectNote" name="rejectNote" rows="4"
                                                                                    class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                                                    placeholder="Enter rejection reason..." required></textarea>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Modal footer -->
                                                                        <div
                                                                            class="flex items-center justify-end p-6 space-x-3 border-t rounded-b">
                                                                            <button type="button"
                                                                                onclick="hideRejectModal()"
                                                                                class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                                Cancel
                                                                            </button>
                                                                            <button type="submit"
                                                                                class="px-5 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                                                Confirm Reject
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($transaction->transaction_status === 'pending')
                                        <span class="text-yellow-500 font-xs">
                                            Waiting upload proof
                                        </span>
                                    @else
                                        <span class="text-green-500 text-xs">
                                            Verified by:<br>
                                            @if ($transaction->payment && $transaction->payment->verified_by)
                                                {{ $transaction->payment->verifiedBy->name ?? 'Admin' }}
                                            @else
                                                System
                                            @endif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    {{ $transactions->links() }}
                </div>
            @endif
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
            let searchTimer;
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tableBody = document.getElementById('transactionTableBody');

            // Function to render transactions
            function renderTransactions(transactions) {
                let html = '';

                transactions.forEach(transaction => {
                    // Status badge styling
                    const statusStyles = {
                        'pending': 'bg-yellow-100 text-yellow-800',
                        'waiting': 'bg-yellow-100 text-yellow-800',
                        'paid': 'bg-blue-100 text-blue-800',
                        'completed': 'bg-green-100 text-green-800',
                        'rejected': 'bg-red-100 text-red-800',
                        'canceled': 'bg-red-100 text-red-800',
                        'failed': 'bg-red-100 text-red-800',
                        'expired': 'bg-gray-200 text-gray-700'
                    };

                    const badgeStyle = statusStyles[transaction.transaction_status] ||
                        'bg-gray-100 text-gray-800';
                    const hasTooltip = transaction.transaction_status === 'rejected' && transaction.payment
                        ?.notes;

                    html += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${transaction.order_id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${transaction.user_name}<br>
                                        <span class="text-xs text-gray-400">${transaction.user_email}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${transaction.property_name}<br>
                                        <span class="text-xs text-gray-400">${transaction.room_name}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp${new Intl.NumberFormat('id-ID').format(transaction.grandtotal_price)}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${transaction.payment_method.charAt(0).toUpperCase() + transaction.payment_method.slice(1)}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                        ${transaction.paid_at ? `
                                                <div>${new Date(transaction.paid_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</div>
                                                <div class="text-xs text-gray-400">${new Date(transaction.paid_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
                                            ` : (transaction.payment?.verified_at ? `
                                                <div>${new Date(transaction.payment.verified_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</div>
                                                <div class="text-xs text-gray-400">${new Date(transaction.payment.verified_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
                                            ` : '-')}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        <div class="relative inline-block group">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${badgeStyle} ${hasTooltip ? 'cursor-help' : ''}">
                                                ${transaction.transaction_status.charAt(0).toUpperCase() + transaction.transaction_status.slice(1).replace('_', ' ')}
                                            </span>
                                            ${hasTooltip ? `
                                                    <div class="absolute z-10 w-64 p-2 mt-1 text-xs text-gray-600 bg-white border border-gray-300 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform -translate-x-1/2 left-1/2">
                                                        ${transaction.payment.notes}
                                                    </div>
                                                ` : ''}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        ${transaction.transaction_status === 'waiting' ? `
                                                <div x-data="attachmentModal()" class="relative group">
                                                    <button type="button"
                                                        class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded"
                                                        @click="openModal('${transaction.attachment}', '${transaction.order_id}')"
                                                        title="View Attachment">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            ` : transaction.transaction_status === 'pending' ? `
                                                <span class="text-yellow-500 font-xs">
                                                    Waiting upload proof
                                                </span>
                                            ` : `
                                                <span class="text-green-500 text-xs">
                                                    Verified by:<br>
                                                    ${transaction.payment?.verified_by ? (transaction.payment.verifiedBy?.name || 'Admin') : 'System'}
                                                </span>
                                            `}
                                    </td>
                                </tr>
                            `;
                });

                tableBody.innerHTML = html;
            }

            // Function to fetch data
            function fetchData() {
                const search = searchInput.value;
                const status = statusFilter.value;

                $.ajax({
                    url: "{{ route('admin.payments.filter') }}",
                    type: "GET",
                    data: {
                        search: search,
                        status: status
                    },
                    success: function(response) {
                        renderTransactions(response.transactions.data);
                        $('#paginationContainer').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }

            // Initial load
            fetchData();

            // Search input event with debounce
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(fetchData, 500);
            });

            // Status filter change event
            statusFilter.addEventListener('change', fetchData);

            // Handle pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    type: "GET",
                    data: {
                        search: searchInput.value,
                        status: statusFilter.value
                    },
                    success: function(response) {
                        renderTransactions(response.transactions.data);
                        $('#paginationContainer').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>
