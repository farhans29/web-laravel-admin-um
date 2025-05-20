<x-app-layout>
    <div class="flex flex-col h-full">

        <!-- Main Content -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <div class="container mx-auto">
                    <!-- Transfer Controls -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold text-gray-800">Initiate Room Transfer</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Guest Selection -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="font-medium text-gray-700 mb-3">Guest Information</h3>

                                <form method="GET" action="{{ route('changerooom.index') }}" class="mb-4">
                                    <div class="relative">
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                            placeholder="Search guest...">
                                        <button type="submit">
                                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                                        </button>
                                    </div>
                                </form>

                                <div class="space-y-4">
                                    @forelse ($bookings as $booking)
                                        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 mb-5 cursor-pointer hover:border-indigo-300 booking-card"
                                            data-booking="{{ json_encode([
                                                'room_number' => $booking->room->name ?? 'N/A',
                                                'room_type' => $booking->room->type ?? 'N/A',
                                                'check_in' => $booking->transaction->check_in,
                                                'check_out' => $booking->transaction->check_out,
                                                'rate' => $booking->room->price ?? 'N/A',
                                                'guest_name' => $booking->user->username ?? 'N/A',
                                                'order_id' => $booking->order_id,
                                                'property' => $booking->property->name ?? 'N/A',
                                            ]) }}"
                                            onclick="selectBooking(this)">
                                            <div class="flex items-center space-x-3 text-sm">
                                                <!-- Avatar -->
                                                <div
                                                    class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                                    {{ strtoupper(substr($booking->user->username ?? '?', 0, 1)) }}
                                                </div>

                                                <!-- Details -->
                                                <div class="flex-1">
                                                    <div class="flex justify-between">
                                                        <span
                                                            class="font-semibold text-gray-800">{{ $booking->user->username ?? 'N/A' }}</span>
                                                        <span class="text-gray-500">{{ $booking->order_id }}</span>
                                                    </div>
                                                    <div class="text-gray-600 mt-1 space-y-0.5">
                                                        <div><strong>Property:</strong>
                                                            {{ $booking->property->name ?? '-' }}</div>
                                                        <div><strong>Room:</strong> {{ $booking->room->name ?? '-' }}
                                                            ({{ $booking->room->type ?? '' }})
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @empty
                                        <div class="text-center py-10 text-gray-500">
                                            <p class="text-sm">No bookings found.</p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="mt-4">
                                    {{ $bookings->withQueryString()->links() }}
                                </div>
                            </div>

                            <!-- Current Room -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="font-medium text-gray-700 mb-3">Current Room</h3>
                                <div class="bg-white p-3 rounded border border-gray-200" id="currentRoomDetails">
                                    <div class="text-center py-10 text-gray-500" id="noBookingSelected">
                                        <p class="text-sm">Select a booking to view details</p>
                                    </div>
                                    <div class="space-y-2 hidden" id="bookingDetails">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Guest Name:</span>
                                            <span class="font-medium" id="guestName">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Reservation ID:</span>
                                            <span class="font-medium" id="orderId">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Property:</span>
                                            <span class="font-medium" id="property">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Room Number:</span>
                                            <span class="font-medium" id="roomNumber">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Room Type:</span>
                                            <span class="font-medium" id="roomType">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Check-in:</span>
                                            <span class="font-medium" id="checkIn">-</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Check-out:</span>
                                            <span class="font-medium" id="checkOut">-</span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- New Room Selection -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="font-medium text-gray-700 mb-3">Transfer To</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">New Room</label>
                                        <select name="new_room" id="newRoomSelect"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                            disabled>
                                            <option value="" disabled selected>Select new room</option>
                                            @foreach ($availableRooms as $room)
                                                <option value="{{ $room->idrec }}">
                                                    {{ $room->name }} - {{ $room->type }} -
                                                    {{ $room->descriptions }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="bg-white p-3 rounded border border-gray-200">
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Selected Room:</span>
                                                <span class="font-medium" id="selectedNewRoom">-</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Room Status:</span>
                                                <span class="font-medium" id="roomAvailability">-</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Rate Difference:</span>
                                                <span class="font-medium text-blue-600" id="rateDifference">-</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Transfer Reason</label>
                                        <select name="reason" id="transferReasonSelect"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                            disabled>
                                            <option value="" disabled selected>Select reason</option>
                                            <option value="maintenance">Maintenance Required</option>
                                            <option value="upgrade">Guest Requested Upgrade</option>
                                            <option value="downgrade">Guest Requested Downgrade</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 mb-1">Notes</label>
                                        <textarea name="notes" id="transferNotes"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                            disabled rows="2" placeholder="Additional notes..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                            <button
                                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                                Cancel
                            </button>
                            <button
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 flex items-center">
                                <i class="fas fa-exchange-alt mr-2"></i>
                                Process Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            const searchResults = document.getElementById('searchResults');
            const paginationLinks = document.getElementById('paginationLinks');
            let timer;

            // Auto-search functionality
            searchInput.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    performSearch(searchInput.value);
                }, 500); // 500ms delay after typing stops
            });

            // Handle form submission (prevent default and use AJAX)
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch(searchInput.value);
            });

            function performSearch(query) {
                const url = new URL('{{ route('changerooom.index') }}');
                url.searchParams.append('search', query);

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Create a temporary DOM element to parse the response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Extract the bookings and pagination from the response
                        const newResults = doc.querySelector('#searchResults')?.innerHTML ||
                            '<div class="text-center py-10 text-gray-500"><p class="text-sm">No bookings found.</p></div>';
                        const newPagination = doc.querySelector('#paginationLinks')?.innerHTML || '';

                        // Update the DOM
                        searchResults.innerHTML = newResults;
                        paginationLinks.innerHTML = newPagination;

                        // Re-attach click handlers to new booking cards
                        attachBookingCardHandlers();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function attachBookingCardHandlers() {
                document.querySelectorAll('.booking-card').forEach(card => {
                    card.addEventListener('click', function() {
                        selectBooking(this);
                    });
                });
            }

            // Initial attachment of handlers
            attachBookingCardHandlers();
        });

        function selectBooking(element) {
            // Remove active class from all booking cards
            document.querySelectorAll('.booking-card').forEach(card => {
                card.classList.remove('border-indigo-500', 'bg-indigo-50');
            });

            // Add active class to selected card
            element.classList.add('border-indigo-500', 'bg-indigo-50');

            // Get booking data from data attribute
            const bookingData = JSON.parse(element.getAttribute('data-booking'));

            // Update current room details
            document.getElementById('guestName').textContent = bookingData.guest_name;
            document.getElementById('orderId').textContent = bookingData.order_id;
            document.getElementById('property').textContent = bookingData.property;
            document.getElementById('roomNumber').textContent = bookingData.room_number;
            document.getElementById('roomType').textContent = bookingData.room_type;
            document.getElementById('checkIn').textContent = new Date(bookingData.check_in).toLocaleDateString();
            document.getElementById('checkOut').textContent = new Date(bookingData.check_out).toLocaleDateString();

            // Show booking details and hide placeholder
            document.getElementById('noBookingSelected').classList.add('hidden');
            document.getElementById('bookingDetails').classList.remove('hidden');

            // Enable form controls
            document.querySelector('select[name="new_room"]').disabled = false;
            document.querySelector('select[name="reason"]').disabled = false;
            document.querySelector('textarea[name="notes"]').disabled = false;

            // Fetch available rooms for the selected property
            fetchAvailableRooms(bookingData.property);
        }

        function fetchAvailableRooms(propertyName) {
            // You might need to adjust this URL based on your routes
            fetch(`/rooms/change-room/available-rooms?property=${encodeURIComponent(propertyName)}`)
                .then(response => response.json())
                .then(rooms => {
                    const select = document.getElementById('newRoomSelect');
                    select.innerHTML = '<option value="" disabled selected>Select new room</option>';

                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.idrec;
                        option.textContent = `${room.name} - ${room.type} - ${room.descriptions}`;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching available rooms:', error);
                });
        }
    </script>
</x-app-layout>
