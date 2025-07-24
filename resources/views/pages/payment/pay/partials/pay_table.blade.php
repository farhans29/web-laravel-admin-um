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
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200" id="transactionTableBody">
        @foreach ($payments as $payment)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="text-sm font-medium text-indigo-600">{{ $payment->order_id }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $payment->transaction->user->username ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $payment->transaction->user->email ?? '-' }}
                            </div>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $payment->transaction->property->name ?? 'N/A' }}</div>
                    <span class="text-xs text-gray-400">{{ $payment->transaction->room->name ?? '-' }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    Rp{{ number_format($payment->grandtotal_price, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $payment->transaction->transaction_type ?? '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    @if ($payment->transaction->paid_at)
                        <div>{{ $payment->transaction->paid_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $payment->transaction->paid_at->format('H:i') }}
                        </div>
                    @elseif($payment && $payment->verified_at)
                        <div>{{ $payment->verified_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-400">
                            {{ $payment->verified_at->format('H:i') }}</div>
                    @else
                        -
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    @php
                        $status = $payment->transaction->transaction_status;
                        $statusStyles = [
                            'pending' => 'bg-yellow-100 text-yellow-800', 
                            'waiting' => 'bg-orange-100 text-orange-800', 
                            'paid' => 'bg-blue-100 text-blue-800', 
                            'completed' => 'bg-green-100 text-green-800', 
                            'rejected' => 'bg-red-200 text-red-900', 
                            'canceled' => 'bg-pink-100 text-pink-800', 
                            'failed' => 'bg-rose-100 text-rose-800', 
                            'expired' => 'bg-gray-300 text-gray-800', 
                        ];

                        $badgeStyle = $statusStyles[$status] ?? 'bg-gray-100 text-gray-800';
                        $hasTooltip = $status === 'rejected' && isset($payment->notes);
                    @endphp


                    <div class="relative inline-block group">
                        <span
                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeStyle }} {{ $hasTooltip ? 'cursor-help' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </span>

                        @if ($hasTooltip)
                            <div
                                class="absolute z-10 w-64 p-2 mt-1 text-xs text-gray-600 bg-white border border-gray-300 rounded shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform -translate-x-1/2 left-1/2">
                                {{ $payment->notes }}
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    @if (in_array($payment->transaction->transaction_status, ['waiting']))
                        <div x-data="attachmentModal()" class="relative group">
                            <button type="button"
                                class="flex items-center gap-2 text-white bg-blue-600 hover:bg-blue-700 border border-blue-600 px-4 py-2 rounded-lg transition-all duration-200 ease-in-out shadow-sm hover:shadow-md"
                                @click="openModal('{{ $payment->transaction->attachment }}', '{{ $payment->order_id }}')"
                                title="Confirm Payment">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6L9 17l-5-5" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                <span class="text-sm font-semibold">Confirm</span>
                            </button>
                            <!-- Modal backdrop -->
                            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 transition-opacity"
                                x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-out duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                aria-hidden="true" x-cloak>
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
                                        <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
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
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </template>

                                        <template x-if="!isLoading && attachmentType === 'image'">
                                            <img :src="'data:image/jpeg;base64,' + attachmentData" alt="Payment Proof"
                                                class="mx-auto max-h-[70vh] max-w-full object-contain">
                                        </template>

                                        <template x-if="!isLoading && attachmentType === 'pdf'">
                                            <div class="h-[70vh] w-full">
                                                <iframe :src="'data:application/pdf;base64,' + attachmentData"
                                                    class="w-full h-full border border-gray-200"
                                                    frameborder="0"></iframe>
                                            </div>
                                        </template>

                                        <template x-if="!isLoading && attachmentType === 'unknown'">
                                            <div class="text-center py-10">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-16 w-16 mx-auto text-gray-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
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
                                            <form id="approve-form-{{ $payment->idrec }}"
                                                action="{{ route('admin.payments.approve', $payment->idrec) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium"
                                                    onclick="confirmApprove({{ $payment->idrec }})">
                                                    Approve
                                                </button>
                                            </form>

                                            <!-- Reject Button with Note -->
                                            <button type="button"
                                                class="flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium"
                                                onclick="showRejectModal({{ $payment->idrec }})">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
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
                                                        <h3 class="text-xl font-semibold text-gray-900">
                                                            Reject Payment
                                                        </h3>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form id="reject-form-{{ $payment->idrec }}"
                                                        action="{{ route('admin.payments.reject', $payment->idrec) }}"
                                                        method="POST" onsubmit="return validateRejectForm()">
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
                                                            <button type="button" onclick="hideRejectModal()"
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
                    @elseif ($payment->transaction->transaction_status === 'pending')
                        <span class="text-yellow-500 font-xs">
                            Waiting upload proof
                        </span>
                    @else
                        <span class="text-green-500 text-xs">
                            Verified by:<br>
                            @if ($payment->payment && $payment->payment->verified_by)
                                {{ $payment->payment->verifiedBy->name ?? 'Admin' }}
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
