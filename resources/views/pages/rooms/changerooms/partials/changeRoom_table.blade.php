@forelse ($bookings as $booking)
    <div class="bg-white p-4 rounded-lg shadow-sm border-2 border-gray-200 mb-3 cursor-pointer hover:border-indigo-400 hover:shadow-md transition-all duration-200 booking-card"
        data-booking="{{ json_encode([
            'room_number' => $booking->room->name ?? 'N/A',
            'room_type' => $booking->room->type ?? 'N/A',
            'check_in' => $booking->transaction?->check_in ?? $booking->check_in_at,
            'check_out' => $booking->transaction?->check_out ?? $booking->check_out_at,
            'rate' => $booking->room->price ?? 'N/A',
            'guest_name' => $booking->user->username ?? 'N/A',
            'order_id' => $booking->order_id,
            'propertyName' => $booking->property->name ?? 'N/A',
            'property_id' => $booking->property->idrec ?? 'N/A',
            'room_id' => $booking->room->idrec ?? 'N/A',
            'is_checked_in' => $booking->check_in_at ? true : false,
            'booking_status' => $booking->check_in_at ? 'Checked In' : 'Pending',
        ]) }}"
        onclick="selectBooking(this)">

        <!-- Header: Guest Name & Status -->
        <div class="flex items-start justify-between mb-2">
            <div class="flex items-center space-x-2">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    {{ strtoupper(substr($booking->user->username ?? '?', 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-800 text-sm">{{ $booking->user->username ?? 'N/A' }}</div>
                    <div class="text-xs text-gray-500 font-mono">{{ $booking->order_id }}</div>
                </div>
            </div>
            @if ($booking->check_in_at)
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
                    <i class="fas fa-check-circle mr-1"></i>Checked In
                </span>
            @else
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                    <i class="fas fa-clock mr-1"></i>Pending
                </span>
            @endif
        </div>

        <!-- Details Grid -->
        <div class="space-y-1.5 text-xs mt-3">
            <div class="flex items-center justify-between py-1 border-b border-gray-100">
                <span class="text-gray-500 font-medium">
                    <i class="fas fa-building w-4 text-gray-400"></i> Property
                </span>
                <span class="text-gray-800 font-medium text-right">{{ $booking->property->name ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between py-1">
                <span class="text-gray-500 font-medium">
                    <i class="fas fa-door-open w-4 text-gray-400"></i> Room
                </span>
                <span class="text-gray-800 font-medium text-right">
                    {{ $booking->room->name ?? '-' }}
                    <span class="text-gray-500">({{ $booking->room->type ?? '' }})</span>
                </span>
            </div>
        </div>

    </div>
@empty
    <div class="text-center py-10 text-gray-400">
        <i class="fas fa-inbox text-3xl mb-2"></i>
        <p class="text-sm">Tidak ada pemesanan ditemukan.</p>
        @if(request('search'))
            <p class="text-xs mt-1">Coba kata kunci pencarian lain.</p>
        @endif
    </div>
@endforelse
