<div>

    <div class="p-4 text-green-700 font-semibold bg-green-100 rounded">
        Livewire Component Loaded
    </div>
    
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                <select wire:model="propertyType" class="w-full border-gray-200 rounded-lg">
                    <option value="">All Properties</option>
                    <option value="apartment">Apartment</option>
                    <option value="hotel">Hotel</option>
                    <option value="house">House</option>
                    <option value="villa">Villa</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model="status" class="w-full border-gray-200 rounded-lg">
                    <option value="">All Statuses</option>
                    <option value="waiting">Waiting for Check-In</option>
                    <option value="checkin">Checked-In</option>
                    <option value="checkout">Checked-Out</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                <input type="date" wire:model="checkInDate" class="w-full border-gray-200 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" wire:model.debounce.500ms="search" placeholder="Search guest or order..." class="w-full border-gray-200 rounded-lg">
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Order ID</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Guest</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Property</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Room</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Check-in</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Check-out</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-indigo-600">{{ $booking->order_id }}</div>
                            <div class="text-sm text-gray-500">ID: {{ $booking->transaction->user_id }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $booking->transaction->user_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->transaction->user_email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->transaction->property_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->transaction->room_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($booking->check_in_at)
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->check_in_at)->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->check_in_at)->format('H:i') }}
                                </div>
                            @else
                                <div class="text-sm text-gray-500 italic">Not Checked-In Yet</div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($booking->check_out_at)
                                <div class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($booking->check_out_at)->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->check_out_at)->format('H:i') }}
                                </div>
                            @else
                                <div class="text-sm text-gray-500 italic">Not Checked-Out Yet</div>
                            @endif
                        </td>                                    

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->status }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right" x-data="{ open: false }">
                            @if (is_null($booking->check_in_at) && is_null($booking->check_out_at))
                                <!-- Trigger Button -->
                                <button @click="open = true"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none">
                                    <!-- Heroicon: door-open -->
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 21V3a1 1 0 011-1h5.5a1 1 0 011 1v2m0 0v14m0-14l7 2v14l-7-2">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h1">
                                        </path>
                                    </svg>
                                    Check-In
                                </button>

                                <!-- Modal -->
                                <div x-show="open" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
                                    <div
                                        class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mt-20 text-left">
                                        <h2 class="text-lg font-semibold mb-4 text-gray-800">Confirm Check-In
                                        </h2>
                                        <p class="mb-6 text-sm text-gray-600">Are you sure you want to check-in
                                            this guest?</p>
                                        <div class="flex justify-end gap-3">
                                            <button @click="open = false"
                                                class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                                Cancel
                                            </button>
                                            <form method="POST"
                                                action="{{ route('bookings.checkin', $booking->idrec) }}">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                                                    Yes, Check-In
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            No bookings found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="py-4">
        {{ $bookings->links() }}
    </div>

    <div class="text-sm text-gray-500">
        Debug: {{ $status }}, {{ $propertyType }}, {{ $checkInDate }}
    </div>
</div>
