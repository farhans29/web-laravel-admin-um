<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Guest Check-in Management
                </h1>
                <p class="text-gray-500 mt-2">Manage all property check-ins (hotel, kos, apartment)</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Property Type</label>
                    <select class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Properties</option>
                        <option value="hotel">Hotel</option>
                        <option value="kos">Kos-kosan</option>
                        <option value="apartment">Apartment</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="active">Active Stays</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                    <input type="date"
                        class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative">
                        <input type="text" placeholder="Search guest or order..."
                            class="w-full pl-10 border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 absolute left-3 top-2.5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Container -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Current Stays</h2>
                <div class="flex items-center space-x-4 mt-4 md:mt-0">
                    <span class="text-sm text-gray-600">Total: {{ $bookings->total() }} guests</span>
                    <form method="GET" class="flex items-center">
                        <label for="per_page" class="text-sm text-gray-600 mr-2">Show:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Table Container -->
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->order_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->transactions->user_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->transactions->property_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->transactions->room_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->check_in_at }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->check_out_at }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->status }}</td>
                                @if($booking->status == "Waiting for Check-In")
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right" x-data="{ open: false }">
                                        <!-- Trigger Button -->
                                        <button @click="open = true"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none">
                                            <!-- Heroicon: door-open -->
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 21V3a1 1 0 011-1h5.5a1 1 0 011 1v2m0 0v14m0-14l7 2v14l-7-2"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h1"></path>
                                            </svg>
                                            Check-In
                                        </button>
                                    
                                        <!-- Modal -->
                                        <div x-show="open"
                                            x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 p-4">
                                            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mt-20 text-left">
                                                <h2 class="text-lg font-semibold mb-4 text-gray-800">Confirm Check-In</h2>
                                                <p class="mb-6 text-sm text-gray-600">Are you sure you want to check-in this guest?</p>
                                                <div class="flex justify-end gap-3">
                                                    <button @click="open = false"
                                                            class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                                                        Cancel
                                                    </button>
                                                    <form method="POST" action="{{ route('bookings.checkin', $booking->idrec) }}">
                                                        @csrf
                                                        <button type="submit"
                                                                class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">
                                                            Yes, Check-In
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>                                                               
                                @endif
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
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>

    <!-- Check-in Modal -->
    <div id="checkinModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">New Check-in</h3>
                            <form id="checkinForm">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Property</label>
                                        <select
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Select Property</option>
                                            {{-- @foreach ($properties as $property)
                                                <option value="{{ $property->id }}">{{ $property->name }}
                                                    ({{ $property->type }})</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Room</label>
                                        <select
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">Select Room</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Check-in Date</label>
                                        <input type="datetime-local"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Check-out Date</label>
                                        <input type="datetime-local"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Guest
                                            Information</label>
                                        <input type="text" placeholder="Guest name"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                        <input type="tel"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Process Check-in
                    </button>
                    <button type="button" onclick="closeCheckinModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-out Modal -->
    <div id="checkoutModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <!-- Similar structure to check-in modal but for check-out -->
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <!-- Modal structure for showing booking details -->
    </div>

    <script>
        function openCheckinModal() {
            document.getElementById('checkinModal').classList.remove('hidden');
        }

        function closeCheckinModal() {
            document.getElementById('checkinModal').classList.add('hidden');
        }

        function openCheckoutModal(bookingId) {
            // Implementation for checkout modal
        }

        function openDetailsModal(bookingId) {
            // Implementation for details modal
        }

        function openEditModal(bookingId) {
            // Implementation for edit modal
        }
    </script>
</x-app-layout>
