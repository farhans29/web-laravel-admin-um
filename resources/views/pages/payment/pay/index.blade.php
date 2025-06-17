<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Bukti Pembayaran</h1>
        </div>

        <!-- Payment Verification Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
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
                                Payment Proof</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Payment Date</th>
                            <th
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $transaction->order_id }}</td>
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
                                    @if ($transaction->attachment)
                                        <a href="{{ route('admin.payments.view-proof', $transaction->idrec) }}"
                                            target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">
                                            View Proof
                                        </a>
                                    @else
                                        <span class="text-gray-400">No proof uploaded</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->transaction_status === 'pending' || $transaction->transaction_status === 'waiting_payment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $transaction->transaction_status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->transaction_status === 'failed' || $transaction->transaction_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $transaction->transaction_status)) }}
                                    </span>

                                    @if ($transaction->payment)
                                        <div class="mt-1 text-xs text-gray-500">
                                            Payment Method: {{ ucfirst($transaction->payment_method) }}
                                        </div>
                                    @endif
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

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    @if (in_array($transaction->transaction_status, ['pending', 'waiting_payment']))
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
                                                                    cannot be previewed. Please download it to view.</p>
                                                                <a :href="'data:application/octet-stream;base64,' + attachmentData"
                                                                    download="payment_proof_#orderId"
                                                                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                                                    Download File
                                                                </a>
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
                                                            <a :href="'data:image/jpeg;base64,' + attachmentData"
                                                                download="payment_proof_#orderId.jpg"
                                                                class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                                                :class="{ 'opacity-50 cursor-not-allowed': !attachmentData }"
                                                                :disabled="!attachmentData">
                                                                Download
                                                            </a>

                                                            <!-- Tombol Approve -->                                                          
                                                            <form action="{{ route('admin.payments.approve', $transaction->idrec) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium"
                                                                :class="{ 'opacity-50 cursor-not-allowed': !
                                                                    attachmentData, 'hover:bg-green-600': !
                                                                        attachmentData }"
                                                                :disabled="!attachmentData"
                                                                @click="!attachmentData ? null : confirm('Approve this payment?')">
                                                                Approve
                                                            </button>
                                                            </form>
                
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500 text-xs">
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
    </script>
</x-app-layout>
