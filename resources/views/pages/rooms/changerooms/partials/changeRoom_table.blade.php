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
             'propertyName' => $booking->property->name ?? 'N/A',
             'property_id' => $booking->property->idrec ?? 'N/A',
             'room_id' => $booking->room->idrec ?? 'N/A',
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
                     <span class="font-semibold text-gray-800">{{ $booking->user->username ?? 'N/A' }}</span>
                     <span class="text-gray-500">{{ $booking->order_id }}</span>
                 </div>
                 <div class="text-gray-600 mt-1 space-y-0.5">
                     <div class="flex justify-between">
                         <strong>Property:</strong>
                         <span class="text-right">{{ $booking->property->name ?? '-' }}</span>
                     </div>
                     <div class="flex justify-between">
                         <strong>Room:</strong>
                         <span class="text-right">
                             {{ $booking->room->name ?? '-' }}
                             ({{ $booking->room->type ?? '' }})
                         </span>
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
