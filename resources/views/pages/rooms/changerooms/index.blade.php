<x-app-layout>
    <div class="flex flex-col h-full">
        <!-- Main Content -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <div class="container mx-auto">
                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-8">
                            <button id="initiateTab"
                                class="py-4 px-1 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600">
                                Initiate Transfer
                            </button>
                            <button id="historyTab"
                                class="py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Transfer History
                            </button>
                        </nav>
                    </div>

                    <!-- Initiate Transfer Tab Content -->
                    <div id="initiateTransferContent">
                        <form id="roomTransferForm" action="{{ route('changeroom.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" id="formOrderId">
                            <input type="hidden" name="current_property_id" id="formCurrentPropertyId">
                            <input type="hidden" name="check_in" id="formCheckIn">
                            <input type="hidden" name="check_out" id="formCheckOut">
                            <!-- Transfer Controls -->
                            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h1
                                        class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                                        Pindah kamar
                                    </h1>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Guest Selection -->
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h3 class="font-medium text-gray-700 mb-3">Informasi Booking</h3>

                                        <form method="GET" action="{{ route('changerooom.index') }}" class="mb-4">
                                            <div class="relative">
                                                <input type="text" name="search" value="{{ request('search') }}"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                                    placeholder="Search booking...">
                                                <button type="submit">
                                                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                                                </button>
                                            </div>
                                        </form>

                                        <div class="space-y-4">
                                            @include('pages.rooms.changerooms.partials.changeRoom_table',
                                                [
                                                    'bookings' => $bookings,
                                                    'per_page' => request('per_page', 8),
                                                ]
                                            )
                                        </div>

                                        <div class="mt-4">
                                            {{ $bookings->withQueryString()->links() }}
                                        </div>
                                    </div>

                                    <!-- Current Room -->
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h3 class="font-medium text-gray-700 mb-3">Kamar Saat Ini</h3>
                                        <div class="bg-white p-3 rounded border border-gray-200"
                                            id="currentRoomDetails">
                                            <div class="text-center py-10 text-gray-500" id="noBookingSelected">
                                                <p class="text-sm">Pilih pemesanan untuk melihat detailnya</p>
                                            </div>
                                            <div class="space-y-2 hidden" id="bookingDetails">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Nama Tamu:</span>
                                                    <span class="font-medium" id="guestName">-</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">ID Reservasi:</span>
                                                    <span class="font-medium" id="orderId">-</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Properti:</span>
                                                    <span class="font-medium" id="propertyName">-</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Nomor Kamar:</span>
                                                    <span class="font-medium" id="roomNumber">-</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Tipe Kamar:</span>
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
                                                <div class="flex justify-between hidden">
                                                    <span class="text-gray-600">ID Properti:</span>
                                                    <span class="font-medium" id="propertyId">-</span>
                                                </div>
                                                <div class="flex justify-between hidden">
                                                    <span class="text-gray-600">ID Kamar:</span>
                                                    <span class="font-medium" id="roomId">-</span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- New Room Selection -->
                                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <h3 class="font-medium text-gray-700 mb-3">Transfer Ke</h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Ruang Baru <span
                                                        class="text-red-500">*</span></label>
                                                <select name="new_room" id="newRoomSelect"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                                    disabled>
                                                    <option value="" disabled selected>Pilih ruang baru</option>
                                                    @foreach ($availableRooms as $room)
                                                        <option value="{{ $room->idrec }}">
                                                            {{ $room->name }} - {{ $room->type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="bg-white p-3 rounded border border-gray-200">
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Kamar Terpilih:</span>
                                                        <span class="font-medium" id="selectedNewRoom">-</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Status Kamar:</span>
                                                        <span class="font-medium" id="roomAvailability">-</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">
                                                    Alasan Transfer <span class="text-red-500">*</span>
                                                </label>
                                                <select name="reason" id="transferReasonSelect"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                                    disabled required>
                                                    <option value="" disabled selected>Pilih alasan</option>
                                                    <option value="maintenance">Perawatan yang Diperlukan</option>
                                                    <option value="upgrade">Peningkatan yang Diminta Tamu</option>
                                                    <option value="downgrade">Tamu Meminta Downgrade</option>
                                                    <option value="other">Lainnya</option>
                                                </select>
                                                <p id="reasonError" class="text-red-500 text-xs mt-1 hidden">Silakan
                                                    pilih alasan transfer</p>
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-600 mb-1">Catatan</label>
                                                <textarea name="notes" id="transferNotes"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                                    disabled rows="2" placeholder="Additional notes..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                                    <button type="button"
                                        onclick="window.location.href='{{ route('changerooom.index') }}'"
                                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                                        Membatalkan
                                    </button>
                                    <button type="submit" id="submitButton"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 flex items-center">
                                        <i class="fas fa-exchange-alt mr-2"></i>
                                        Transfer Proses
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- History Tab Content -->
                    <div id="historyContent" class="hidden">
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-xl font-semibold text-gray-800">Riwayat Transfer Kamar</h2>
                                    <form method="GET" action="{{ route('changerooom.index') }}" class="flex">
                                        <input type="hidden" name="tab" value="history">
                                        <div class="relative">
                                            <input type="text" name="history_search"
                                                value="{{ request('history_search') }}"
                                                class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                                placeholder="ID Pesanan...">
                                            <button type="submit"
                                                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        @if (request('history_search'))
                                            <a href="{{ route('changerooom.index') }}?tab=history"
                                                class="ml-2 px-3 py-2 text-gray-500 hover:text-gray-700">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID Pesanan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tamu</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Dari Kamar</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Ke Kamar</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal/Waktu</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Alasan</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($transferHistory as $history)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $history['order_id'] }}</div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $history['guest_name'] }}</div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if ($history['previous_room'])
                                                            {{ $history['previous_room']->room->name ?? 'N/A' }}
                                                            ({{ $history['previous_room']->room->type ?? '' }})
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if ($history['current_room'])
                                                            {{ $history['current_room']->room->name ?? 'N/A' }}
                                                            ({{ $history['current_room']->room->type ?? '' }})
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $history['created_at']->format('Y M d') }}
                                                    </div>
                                                    <div class="text-xs text-gray-400">
                                                        {{ $history['created_at']->format('H:i') }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $history['reason'] }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Completed
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $transferHistory->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.getElementById('initiateTab').addEventListener('click', function() {
            document.getElementById('initiateTab').classList.add('border-indigo-500', 'text-indigo-600');
            document.getElementById('initiateTab').classList.remove('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');
            document.getElementById('historyTab').classList.remove('border-indigo-500', 'text-indigo-600');
            document.getElementById('historyTab').classList.add('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');

            document.getElementById('initiateTransferContent').classList.remove('hidden');
            document.getElementById('historyContent').classList.add('hidden');
        });

        document.getElementById('historyTab').addEventListener('click', function() {
            document.getElementById('historyTab').classList.add('border-indigo-500', 'text-indigo-600');
            document.getElementById('historyTab').classList.remove('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');
            document.getElementById('initiateTab').classList.remove('border-indigo-500', 'text-indigo-600');
            document.getElementById('initiateTab').classList.add('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');

            document.getElementById('historyContent').classList.remove('hidden');
            document.getElementById('initiateTransferContent').classList.add('hidden');
        });

        document.addEventListener('DOMContentLoaded', function() {
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

        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Check URL for tab parameter
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'initiate';

            // Activate the correct tab
            if (activeTab === 'history') {
                showHistoryTab();
            } else {
                showInitiateTab();
            }

            // Tab click handlers
            document.getElementById('initiateTab').addEventListener('click', showInitiateTab);
            document.getElementById('historyTab').addEventListener('click', showHistoryTab);
        });

        function showInitiateTab() {
            document.getElementById('initiateTab').classList.add('border-indigo-500', 'text-indigo-600');
            document.getElementById('initiateTab').classList.remove('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');
            document.getElementById('historyTab').classList.remove('border-indigo-500', 'text-indigo-600');
            document.getElementById('historyTab').classList.add('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');

            document.getElementById('initiateTransferContent').classList.remove('hidden');
            document.getElementById('historyContent').classList.add('hidden');
        }

        function showHistoryTab() {
            document.getElementById('historyTab').classList.add('border-indigo-500', 'text-indigo-600');
            document.getElementById('historyTab').classList.remove('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');
            document.getElementById('initiateTab').classList.remove('border-indigo-500', 'text-indigo-600');
            document.getElementById('initiateTab').classList.add('border-transparent', 'text-gray-500',
                'hover:text-gray-700', 'hover:border-gray-300');

            document.getElementById('historyContent').classList.remove('hidden');
            document.getElementById('initiateTransferContent').classList.add('hidden');

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', 'history');
            window.history.pushState({}, '', url);
        }

        document.getElementById('roomTransferForm').addEventListener('submit', function(e) {
            const reasonSelect = document.getElementById('transferReasonSelect');
            const reasonError = document.getElementById('reasonError');

            if (!reasonSelect.value) {
                e.preventDefault(); // Mencegah form submit
                reasonSelect.classList.add('border-red-500');
                reasonError.classList.remove('hidden');

                // Scroll ke field yang error
                reasonSelect.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                reasonSelect.focus();
            }
        });

        // Tambahkan event listener untuk menghilangkan error ketika memilih reason
        document.getElementById('transferReasonSelect').addEventListener('change', function() {
            if (this.value) {
                this.classList.remove('border-red-500');
                document.getElementById('reasonError').classList.add('hidden');
            }
        });

        function selectBooking(element) {
            const reasonSelect = document.getElementById('transferReasonSelect');
            reasonSelect.value = '';
            reasonSelect.classList.remove('border-red-500');
            document.getElementById('reasonError').classList.add('hidden');
            // Reset selected new room and room status
            document.getElementById('selectedNewRoom').textContent = '-';
            document.getElementById('roomAvailability').textContent = '-';

            // Clear transfer notes input
            document.getElementById('transferNotes').value = '';

            document.querySelectorAll('.booking-card').forEach(card => {
                card.classList.remove('border-indigo-500', 'bg-indigo-50');
            });

            // Add active class to selected card
            element.classList.add('border-indigo-500', 'bg-indigo-50');

            // Get booking data from data attribute
            const bookingData = JSON.parse(element.getAttribute('data-booking'));

            const checkInDate = new Date(bookingData.check_in);
            const checkOutDate = new Date(bookingData.check_out);

            const options = {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false // Gunakan format 24 jam
            };

            function formatDateTime(dateStr) {
                const d = new Date(dateStr);
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                const hours = String(d.getHours()).padStart(2, '0');
                const minutes = String(d.getMinutes()).padStart(2, '0');
                return `${year}/${month}/${day} ${hours}:${minutes}`;
            }

            document.getElementById('formOrderId').value = bookingData.order_id;
            document.getElementById('formCurrentPropertyId').value = bookingData.property_id;
            document.getElementById('formCheckIn').value = bookingData.check_in;
            document.getElementById('formCheckOut').value = bookingData.check_out;

            // Update current room details
            document.getElementById('guestName').textContent = bookingData.guest_name;
            document.getElementById('orderId').textContent = bookingData.order_id;
            document.getElementById('propertyName').textContent = bookingData.propertyName;
            document.getElementById('propertyId').textContent = bookingData.property_id;
            document.getElementById('roomId').textContent = bookingData.room_id;
            document.getElementById('roomNumber').textContent = bookingData.room_number;
            document.getElementById('roomType').textContent = bookingData.room_type;
            document.getElementById('checkIn').textContent = formatDateTime(bookingData.check_in);
            document.getElementById('checkIn').style.color = 'green';

            document.getElementById('checkOut').textContent = formatDateTime(bookingData.check_out);
            document.getElementById('checkOut').style.color = 'red';

            // Show booking details and hide placeholder
            document.getElementById('noBookingSelected').classList.add('hidden');
            document.getElementById('bookingDetails').classList.remove('hidden');

            // Enable form controls
            document.getElementById('newRoomSelect').disabled = false;
            document.getElementById('transferReasonSelect').disabled = false;
            document.getElementById('transferNotes').disabled = false;

            // Fetch available rooms for the selected property and dates
            fetchAvailableRooms(
                bookingData.propertyName,
                bookingData.property_id,
                bookingData.room_id,
                bookingData.check_in,
                bookingData.check_out,
                bookingData.room_number // exclude current room
            );
        }

        function fetchAvailableRooms(propertyName, property_id, room_id, checkInDate, checkOutDate, currentRoomNumber) {
            const url = new URL('/rooms/change-room/available-rooms', window.location.origin);
            url.searchParams.append('propertyName', propertyName);
            url.searchParams.append('property_id', property_id);
            url.searchParams.append('room_id', room_id);
            url.searchParams.append('check_in', checkInDate);
            url.searchParams.append('check_out', checkOutDate);
            url.searchParams.append('exclude_room', currentRoomNumber);

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(rooms => {
                    const select = document.getElementById('newRoomSelect');
                    select.innerHTML = '<option value="" disabled selected>Select new room</option>';

                    if (rooms.length === 0) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No available rooms found';
                        option.disabled = true;
                        select.appendChild(option);
                    } else {
                        rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.idrec;
                            option.textContent = `${room.name} - ${room.type}`;
                            select.appendChild(option);
                        });
                    }

                    // Aktifkan select dan dropdown lain
                    select.disabled = false;
                    document.getElementById('transferReasonSelect').disabled = false;
                    document.getElementById('transferNotes').disabled = false;

                    // Tambahkan event listener baru (hindari duplikat)
                    select.removeEventListener('change', updateNewRoomDetails);
                    select.addEventListener('change', function() {
                        updateNewRoomDetails(this);
                    });
                })
                .catch(error => {
                    console.error('Error fetching available rooms:', error);
                    const select = document.getElementById('newRoomSelect');
                    select.innerHTML = '<option value="" disabled selected>Error loading rooms</option>';
                });
        }


        function updateNewRoomDetails(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            document.getElementById('selectedNewRoom').textContent = selectedOption.text;

            const availabilityElement = document.getElementById('roomAvailability');
            availabilityElement.textContent = 'Available';
            availabilityElement.classList.remove('text-red-600');
            availabilityElement.classList.add('text-green-600');
        }
    </script>
</x-app-layout>
