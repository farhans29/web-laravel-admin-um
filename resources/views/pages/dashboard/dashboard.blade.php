<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard Banner -->
        <div class="relative bg-cover bg-center p-4 sm:p-6 rounded-xl overflow-hidden mb-8 shadow-lg"
            style="background-image: url('{{ asset('images/0fd3416c.jpeg') }}')">
            <!-- Overlay -->
            <div class="absolute inset-0 bg-gray-900/70"></div>

            <!-- Content -->
            <div class="relative">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-2xl md:text-3xl text-white font-bold mb-1">
                            MANAGEMENT DASHBOARD
                        </h1>
                        <p class="text-blue-100 font-medium">Welcome back, {{ Auth::user()->first_name }}
                            {{ Auth::user()->last_name }}</p>
                    </div>

                    <div class="mt-4 md:mt-0 flex items-center bg-white/10 backdrop-blur-sm rounded-lg p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-300 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-white text-sm font-medium">Last updated:
                            {{ now()->format('M d, Y H:i') }}</span>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Confirm Booking (Upcoming) -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-blue-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Confirm Booking (Upcoming)</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['upcoming'] }}</h3>
                                <p class="text-blue-200 text-xs mt-1">Needs confirmation</p>
                            </div>
                            <div class="bg-blue-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Booking (Today) -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-purple-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Confirm Booking (Today)</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['today'] }}</h3>
                                <p class="text-purple-200 text-xs mt-1">For today's arrival</p>
                            </div>
                            <div class="bg-purple-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Check-In -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-green-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Check-In</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['checkin'] }}</h3>
                                <p class="text-green-200 text-xs mt-1">Currently staying</p>
                            </div>
                            <div class="bg-green-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Check-Out -->
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border-l-4 border-yellow-300 hover:bg-white/15 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-100 text-sm font-medium">Check-Out</p>
                                <h3 class="text-white text-2xl font-bold mt-1">{{ $stats['checkout'] }}</h3>
                                <p class="text-yellow-200 text-xs mt-1">Completed stays</p>
                            </div>
                            <div class="bg-yellow-500/20 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-200" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Recent Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="font-semibold text-gray-800">Recent Bookings</h2>
                        <a href="{{ route('bookings.index') }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        @include('pages.dashboard.partials.booking_table', [
                            'bookings' => $bookings,
                            'per_page' => request('per_page', 4),
                        ])

                        <!-- Pagination -->
                        <div class="px-5 py-4 border-t border-gray-200">
                            {{ $bookings->links() }}
                        </div>

                    </div>
                </div>

                <!-- Room Availability -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Room Availability (Next 7 Days)</h2>
                    </div>
                    <div class="p-5">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                @foreach ($roomAvailability as $room)
                                    <div class="swiper-slide">
                                        <div class="border rounded-lg p-4 w-[250px]">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="font-medium text-gray-800">{{ $room['type'] }}</h3>
                                                    <p class="text-sm text-gray-500">Total: {{ $room['total'] }} rooms
                                                    </p>
                                                </div>
                                                @if ($room['is_popular'] ?? false)
                                                    <span
                                                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Popular</span>
                                                @elseif($room['is_luxury'] ?? false)
                                                    <span
                                                        class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded">Luxury</span>
                                                @endif
                                            </div>
                                            <div class="mt-4">
                                                <div class="flex justify-between text-sm mb-1">
                                                    <span class="text-gray-600">Available</span>
                                                    <span class="font-medium">{{ $room['available'] }}</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    @php
                                                        $color = match (true) {
                                                            $room['percentage'] >= 70 => 'bg-green-600',
                                                            $room['percentage'] >= 40 => 'bg-blue-600',
                                                            $room['percentage'] >= 20 => 'bg-yellow-600',
                                                            default => 'bg-purple-600',
                                                        };
                                                    @endphp
                                                    <div class="{{ $color }} h-2 rounded-full"
                                                        style="width: {{ $room['percentage'] }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Quick Actions</h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-4">
                            <a href="#"
                                class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="bg-blue-100 p-3 rounded-full mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">New Booking</span>
                            </a>
                            <a href="{{ route('checkin.index') }}"
                                class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="bg-green-100 p-3 rounded-full mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Check In</span>
                            </a>
                            <a href="{{ route('checkout.index') }}"
                                class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="bg-yellow-100 p-3 rounded-full mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Check Out</span>
                            </a>
                            <a href="#"
                                class="flex flex-col items-center justify-center p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="bg-purple-100 p-3 rounded-full mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Add Room</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Reviews -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-800">Recent Guest Reviews</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        <!-- Review 1 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium">JD</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-gray-300 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                    <div class="ml-2 text-sm text-gray-500">2 days ago</div>
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-900">John Doe</div>
                                <div class="mt-1 text-sm text-gray-600">"The Deluxe Suite was amazing! Great view
                                    and
                                    excellent service."</div>
                            </div>
                        </div>

                        <!-- Review 2 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                    <span class="text-pink-600 font-medium">SM</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="flex items-center">
                                    <div class="flex items-center">
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg class="text-yellow-400 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                    <div class="ml-2 text-sm text-gray-500">1 week ago</div>
                                </div>
                                <div class="mt-1 text-sm font-medium text-gray-900">Sarah Miller</div>
                                <div class="mt-1 text-sm text-gray-600">"Absolutely perfect stay! The staff went
                                    above
                                    and beyond to make our anniversary special."</div>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-gray-200 text-center">
                        <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">View all
                            reviews</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar & Upcoming Events -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Calendar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 lg:col-span-3">
                <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-semibold text-gray-800">Booking Calendar</h2>
                    <div class="flex items-center space-x-2">
                        <button class="p-1 rounded-lg hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                        <span class="text-sm font-medium text-gray-700">May 2023</span>
                        <button class="p-1 rounded-lg hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-7 gap-1 text-center text-sm font-medium text-gray-500 mb-2">
                        <div class="py-1">Sun</div>
                        <div class="py-1">Mon</div>
                        <div class="py-1">Tue</div>
                        <div class="py-1">Wed</div>
                        <div class="py-1">Thu</div>
                        <div class="py-1">Fri</div>
                        <div class="py-1">Sat</div>
                    </div>
                    <div class="grid grid-cols-7 gap-1">
                        <!-- Calendar days would be generated dynamically in a real app -->
                        <div class="py-2 text-center text-gray-400">30</div>
                        <div class="py-2 text-center">1</div>
                        <div class="py-2 text-center">2</div>
                        <div class="py-2 text-center">3</div>
                        <div class="py-2 text-center">4</div>
                        <div class="py-2 text-center">5</div>
                        <div class="py-2 text-center">6</div>
                        <div class="py-2 text-center">7</div>
                        <div class="py-2 text-center">8</div>
                        <div class="py-2 text-center">9</div>
                        <div class="py-2 text-center">10</div>
                        <div class="py-2 text-center">11</div>
                        <div class="py-2 text-center">12</div>
                        <div class="py-2 text-center">13</div>
                        <div class="py-2 text-center">14</div>
                        <div class="py-2 text-center">15</div>
                        <div class="py-2 text-center">16</div>
                        <div class="py-2 text-center">17</div>
                        <div class="py-2 text-center">18</div>
                        <div class="py-2 text-center">19</div>
                        <div class="py-2 text-center">20</div>
                        <div class="py-2 text-center">21</div>
                        <div class="py-2 text-center">22</div>
                        <div class="py-2 text-center">23</div>
                        <div class="py-2 text-center">24</div>
                        <div class="py-2 text-center">25</div>
                        <div class="py-2 text-center">26</div>
                        <div class="py-2 text-center">27</div>
                        <div class="py-2 text-center">28</div>
                        <div class="py-2 text-center">29</div>
                        <div class="py-2 text-center">30</div>
                        <div class="py-2 text-center">31</div>
                        <div class="py-2 text-center text-gray-400">1</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new Swiper(".mySwiper", {
                slidesPerView: 1,
                spaceBetween: 16,
                breakpoints: {
                    640: {
                        slidesPerView: 2
                    },
                    768: {
                        slidesPerView: 3
                    },
                    1024: {
                        slidesPerView: 4
                    },
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                loop: false,
            });
        });
    </script>

</x-app-layout>
