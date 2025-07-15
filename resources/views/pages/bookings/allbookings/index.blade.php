<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Guest Bookings
                </h1>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow overflow-hidden">

            <!-- Search and Filter Section -->
            <div class="p-4 border-b border-gray-200">
                <form method="GET" action="{{ route('bookings.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search
                                Booking</label>
                            <input type="text" id="search" name="search" placeholder="Order ID or Guest Name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ request('search') }}">
                        </div>
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Check-in
                                Date</label>
                            <input type="date" id="date" name="date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ request('date') }}">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Status</option>
                                <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting
                                    for Check-In</option>
                                <option value="checked-in" {{ request('status') == 'checked-in' ? 'selected' : '' }}>
                                    Checked-In</option>
                                <option value="checked-out" {{ request('status') == 'checked-out' ? 'selected' : '' }}>
                                    Checked-Out</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Apply Filters
                        </button>
                        <a href="{{ route('bookings.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="overflow-x-auto">
                @include('pages.bookings.allbookings.partials.allbookings_table', [
                    'bookings' => $bookings,
                    'per_page' => request('per_page', 8),
                ])
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 rounded p-4" id="paginationContainer">
                {{ $bookings->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    <script>
        // Add this script to handle per_page changes if you have a select dropdown for items per page
        document.addEventListener('DOMContentLoaded', function() {
            // If you have a per_page selector
            const perPageSelector = document.getElementById('per_page');
            if (perPageSelector) {
                perPageSelector.addEventListener('change', function() {
                    const form = document.querySelector('form');
                    const perPageInput = document.createElement('input');
                    perPageInput.type = 'hidden';
                    perPageInput.name = 'per_page';
                    perPageInput.value = this.value;
                    form.appendChild(perPageInput);
                    form.submit();
                });
            }
        });
    </script>
</x-app-layout>
