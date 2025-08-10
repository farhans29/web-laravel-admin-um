<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-in
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-out
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Order ID
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Property/Room
            </th>
            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($bookings as $booking)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    @if ($booking->check_in_at)
                        <div class="text-sm font-medium text-gray-900">
                            {{ $booking->check_in_at->format('Y-m-d') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $booking->check_in_at->format('H:i') }}
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic">Not checked in</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    @if ($booking->check_out_at)
                        <div class="text-sm font-medium text-gray-900">
                            {{ $booking->check_out_at->format('Y M d') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $booking->check_out_at->format('H:i') }}
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic">Not checked out</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="text-sm font-medium text-indigo-600">{{ $booking->order_id }}</div>
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
                                {{ $booking->transaction->user_name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->transaction->user_email }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $booking->property->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }}</div>
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    @php
                        $statusClasses = [
                            'Waiting For Payment' => 'bg-yellow-100 text-yellow-800',
                            'Waiting For Confirmation Payment' => 'bg-orange-100 text-orange-800',
                            'Waiting For Check-In' => 'bg-cyan-100 text-cyan-800',
                            'Checked-In' => 'bg-green-100 text-green-800',
                            'Checked-Out' => 'bg-indigo-100 text-indigo-800',
                            'Canceled' => 'bg-red-100 text-red-800',
                            'Expired' => 'bg-pink-100 text-pink-800',
                            'Unknown' => 'bg-slate-100 text-slate-800',
                        ];

                        // Define status labels if you want to display shorter text
                        $statusLabels = [
                            'Waiting For Payment' => 'Pending Payment',
                            'Waiting For Confirmation Payment' => 'Confirming',
                            'Waiting For Check-In' => 'Waiting Check-In',
                            'Checked-In' => 'Checked-In',
                            'Checked-Out' => 'Checked-Out',
                            'Canceled' => 'Canceled',
                            'Expired' => 'Expired',
                            'Unknown' => 'Unknown',
                        ];
                    @endphp
                    <span
                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$booking->status] ?? $booking->status }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                    No Orders Completed
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
