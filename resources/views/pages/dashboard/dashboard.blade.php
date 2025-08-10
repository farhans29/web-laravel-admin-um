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
                            FRONT DESK DASHBOARD
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
                                <p class="text-blue-200 text-xs mt-1">Coming soon to check in</p>
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
                                <p class="text-yellow-200 text-xs mt-1">Today's Check-Out Schedule</p>
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
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Check-out Section -->
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all hover:shadow-lg">
                    <div
                        class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 17l-4 4m0 0l-4-4m4 4V3" />
                            </svg>
                            <h2 class="font-semibold text-gray-800 text-lg">Today's Check-outs</h2>
                        </div>
                        <a href="{{ route('checkin.index') }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center">
                            View All
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        @include('pages.bookings.checkin.partials.checkin_table', [
                            'checkOuts' => $checkOuts,
                            'per_page' => request('per_page', 4),
                            'type' => 'check-out',
                        ])
                    </div>
                    <div class="px-6 py-3 bg-gray-50 text-sm text-gray-500 border-t border-gray-100">
                        Showing {{ min(4, count($checkOuts)) }} of {{ count($checkOuts) }} upcoming check-outs
                    </div>
                </div>

                <!-- Check-in Section -->
                <div
                    class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden transition-all hover:shadow-lg">
                    <div
                        class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-green-50 to-teal-50">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                            <h2 class="font-semibold text-gray-800 text-lg">Today's Check-ins</h2>
                        </div>
                        <a href="{{ route('newReserv.index') }}"
                            class="text-sm font-medium text-green-600 hover:text-green-800 flex items-center">
                            View All
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        @include('pages.bookings.newreservations.partials.newreserve_table', [
                            'checkIns' => $checkIns,
                            'per_page' => request('per_page', 4),
                            'type' => 'check-in',
                        ])
                    </div>
                    <div class="px-6 py-3 bg-gray-50 text-sm text-gray-500 border-t border-gray-100">
                        Showing {{ min(4, count($checkIns)) }} of {{ count($checkIns) }} upcoming check-ins
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script></script>
</x-app-layout>
