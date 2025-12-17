<div x-data="{ sidebarOpen: false, sidebarExpanded: false, sidebarPersistent: false }" class="flex">
    <div class="min-w-fit">
        <!-- Sidebar backdrop (mobile only) -->
        <div class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
            :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

        <!-- Sidebar -->
        <div id="sidebar"
            class="flex lg:flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar shrink-0 bg-gray-800 dark:bg-gray-900 p-4 transition-all duration-200 ease-in-out border-r border-gray-200 dark:border-gray-700/60"
            :class="sidebarExpanded ? 'w-64' : 'w-20'" x-init="$el.classList.toggle('sidebar-expanded', sidebarExpanded)"
            x-effect="$el.classList.toggle('sidebar-expanded', sidebarExpanded)"
            @mouseenter="if (!sidebarPersistent) sidebarExpanded = true"
            @mouseleave="if (!sidebarPersistent) sidebarExpanded = false">

            <!-- Sidebar header -->
            <div class="flex justify-between mb-8 pr-3 sm:px-2">
                <!-- Close button -->
                <button class="lg:hidden text-gray-300 hover:text-white" @click.stop="sidebarOpen = !sidebarOpen"
                    aria-controls="sidebar" :aria-expanded="sidebarOpen">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                    </svg>
                </button>
                <!-- Logo -->
                <div class="flex flex-row gap-3 items-center" href="{{ route('dashboard') }}">
                    <img src="/images/frist_icon.png" alt="Booking Logo" class='w-10 h-10'>
                    <p class="text-white text-center text-lg font-bold transition-opacity duration-200"
                        :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                        x-show="sidebarExpanded || window.innerWidth < 1024">
                        {{ $globalTitle }}
                    </p>
                </div>
            </div>

            <!-- Links -->
            <div class="space-y-1">
                <!-- Dashboard -->
                <div class="mb-4">
                    <h3 class="text-xs uppercase text-indigo-400 dark:text-indigo-300 font-semibold pl-3 mb-2">
                        <span x-show="!sidebarExpanded && window.innerWidth >= 1024" class="text-center w-6"
                            aria-hidden="true">•••</span>
                        <span x-show="sidebarExpanded || window.innerWidth < 1024"
                            class="transition-opacity duration-200">Management</span>
                    </h3>
                    <ul class="space-y-1">
                        <!-- Dashboard -->
                        @can('view_dashboard')
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group @if (Route::is('dashboard')) bg-indigo-900 @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                    <span class="transition-opacity duration-200 whitespace-nowrap"
                                        :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                        x-show="sidebarExpanded || window.innerWidth < 1024">Dashboard</span>
                                    <!-- Tooltip for collapsed state -->
                                    <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                        class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                        Dashboard
                                    </div>
                                </a>
                            </li>
                        @endcan

                        <!-- Bookings Menu Item -->
                        @can('view_bookings')
                            <li x-data="{ open: false }" x-init="open = window.location.href.includes('checkin') ||
                                window.location.href.includes('checkout') ||
                                window.location.href.includes('bookings') ||
                                window.location.href.includes('pendings') ||
                                window.location.href.includes('newReserv') ||
                                window.location.href.includes('completed') ||
                                window.location.href.includes('change-room')">

                                <!-- Main Menu Button -->
                                <a @click="open = !open"
                                    class="flex items-center justify-between gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer group relative @if (Route::is(
                                            'checkin.index',
                                            'checkout.index',
                                            'bookings.index',
                                            'pendings.index',
                                            'completed.index',
                                            'newReserv.index',
                                            'changerooom.index')) bg-indigo-900 @endif">
                                    <div class="flex items-center gap-3">
                                        <!-- Calendar Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>

                                        <!-- Menu Text -->
                                        <span class="transition-all duration-300 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100 ml-0' : 'lg:opacity-0 lg:ml-[-0.5rem]'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">
                                            Bookings
                                        </span>
                                    </div>

                                    <!-- Chevron Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 transition-all duration-300 flex-shrink-0"
                                        :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" x-show="sidebarExpanded || window.innerWidth < 1024">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>

                                    <!-- Tooltip for Collapsed State -->
                                    <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-x-1"
                                        x-transition:enter-end="opacity-100 translate-x-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-x-0"
                                        x-transition:leave-end="opacity-0 translate-x-1"
                                        class="absolute left-full ml-2 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none z-50 whitespace-nowrap shadow-lg">
                                        Bookings
                                    </div>
                                </a>

                                <!-- Submenu Items -->
                                <div x-show="open && (sidebarExpanded || window.innerWidth < 1024)" x-collapse
                                    x-transition:enter="transition-[height] ease-out duration-300"
                                    x-transition:leave="transition-[height] ease-in duration-200" class="overflow-hidden">

                                    <ul class="pl-8 mt-1 space-y-1">
                                        <!-- All Bookings -->
                                        @can('view_all_bookings')
                                            <li>
                                                <a href="{{ route('bookings.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('bookings.index')) bg-indigo-900 @endif">
                                                    <span class="text-xs transition-all duration-300 hover:translate-x-1">All
                                                        Bookings</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Pending -->
                                        @can('view_pending_bookings')
                                            <li>
                                                <a href="{{ route('pendings.index') }}"
                                                    class="flex items-center justify-between px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('pendings.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Pending</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Confirm Bookings -->
                                        @can('view_confirmed_bookings')
                                            <li>
                                                <a href="{{ route('newReserv.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('newReserv.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Confirmed
                                                        Bookings</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Checked-ins -->
                                        @can('view_checkins')
                                            <li>
                                                <a href="{{ route('checkin.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('checkin.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Checked-ins</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Checked-outs -->
                                        @can('view_checkouts')
                                            <li>
                                                <a href="{{ route('checkout.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('checkout.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Checked-outs</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Completed -->
                                        @can('view_completed_bookings')
                                            <li>
                                                <a href="{{ route('completed.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('completed.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Completed</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Change Room -->
                                        @can('view_change_room')
                                            <li>
                                                <a href="{{ route('changerooom.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('changerooom.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Change
                                                        Room</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan

                        <!-- Properties Menu Item -->
                        @can('view_properties')
                            <li x-data="{ open: false }" x-init="open = window.location.href.includes('m-properties') ||
                                window.location.href.includes('facilityProperty')">

                                <!-- Main Menu Button -->
                                <a @click="open = !open"
                                    class="flex items-center justify-between gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 cursor-pointer group relative @if (Route::is('properties.index', 'facilityProperty.index')) bg-indigo-900 @endif">

                                    <div class="flex items-center gap-3">
                                        <!-- Building Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>

                                        <!-- Menu Text -->
                                        <span class="transition-all duration-300 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100 ml-0' : 'lg:opacity-0 lg:ml-[-0.5rem]'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">
                                            Properties
                                        </span>
                                    </div>

                                    <!-- Chevron Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 transition-all duration-300 flex-shrink-0"
                                        :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" x-show="sidebarExpanded || window.innerWidth < 1024">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>

                                    <!-- Tooltip for Collapsed State -->
                                    <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-x-1"
                                        x-transition:enter-end="opacity-100 translate-x-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-x-0"
                                        x-transition:leave-end="opacity-0 translate-x-1"
                                        class="absolute left-full ml-2 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none z-50 whitespace-nowrap shadow-lg">
                                        Properties
                                    </div>
                                </a>

                                <!-- Submenu Items -->
                                <div x-show="open && (sidebarExpanded || window.innerWidth < 1024)" x-collapse
                                    x-transition:enter="transition-[height] ease-out duration-300"
                                    x-transition:leave="transition-[height] ease-in duration-200" class="overflow-hidden">

                                    <ul class="pl-8 mt-1 space-y-1">
                                        <!-- Master Properties -->
                                        @can('view_properties')
                                            <li>
                                                <a href="{{ route('properties.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-all duration-300 @if (Route::is('properties.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Master
                                                        Properties</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Master Facilities -->
                                        @can('view_property_facilities')
                                            <li>
                                                <a href="{{ route('facilityProperty.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-all duration-300 @if (Route::is('facilityProperty.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Master
                                                        Facilities</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan

                        <!-- Rooms/Units Menu Item -->
                        @can('rooms')
                            <li x-data="{ open: false }" x-init="open = window.location.href.includes('m-rooms') ||
                                window.location.href.includes('facilityRooms')">

                                <!-- Main Menu Button -->
                                <a @click="open = !open"
                                    class="flex items-center justify-between gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 cursor-pointer group relative @if (Route::is('rooms.index', 'facilityRooms.index')) bg-indigo-900 @endif">

                                    <div class="flex items-center gap-3">
                                        <!-- Folder Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>

                                        <!-- Menu Text -->
                                        <span class="transition-all duration-300 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100 ml-0' : 'lg:opacity-0 lg:ml-[-0.5rem]'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">
                                            Rooms/Units
                                        </span>
                                    </div>

                                    <!-- Chevron Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 transition-all duration-300 flex-shrink-0"
                                        :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" x-show="sidebarExpanded || window.innerWidth < 1024">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>

                                    <!-- Tooltip for Collapsed State -->
                                    <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-x-1"
                                        x-transition:enter-end="opacity-100 translate-x-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-x-0"
                                        x-transition:leave-end="opacity-0 translate-x-1"
                                        class="absolute left-full ml-2 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none z-50 whitespace-nowrap shadow-lg">
                                        Rooms/Units
                                    </div>
                                </a>

                                <!-- Submenu Items -->
                                <div x-show="open && (sidebarExpanded || window.innerWidth < 1024)" x-collapse
                                    x-transition:enter="transition-[height] ease-out duration-300"
                                    x-transition:leave="transition-[height] ease-in duration-200" class="overflow-hidden">

                                    <ul class="pl-8 mt-1 space-y-1">
                                        <!-- Master Rooms -->
                                        @can('view_rooms')
                                            <li>
                                                <a href="{{ route('rooms.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-all duration-300 @if (Route::is('rooms.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Master
                                                        Rooms</span>
                                                </a>
                                            </li>
                                        @endcan

                                        <!-- Master Facilities -->
                                        @can('view_room_facilities')
                                            <li>
                                                <a href="{{ route('facilityRooms.index') }}"
                                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-all duration-300 @if (Route::is('facilityRooms.index')) bg-indigo-900 @endif">
                                                    <span
                                                        class="text-xs transition-all duration-300 hover:translate-x-1">Master
                                                        Facilities</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan


                        <!-- Customers -->
                        @can('view_customers')
                            <li>
                                <a href="{{ route('customers.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('customers.*')) bg-indigo-900 @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="transition-opacity duration-200 whitespace-nowrap"
                                        :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                        x-show="sidebarExpanded || window.innerWidth < 1024">Customers</span>
                                    <!-- Tooltip for collapsed state -->
                                    <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                        class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                        Customers
                                    </div>
                                </a>
                            </li>
                        @endcan

                        <!-- Room Availability -->
                        @can('view_room_availability')
                            <li>
                                <a href="{{ route('room-availability.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('room-availability.index')) bg-indigo-900 @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 15l2 2 4-4" />
                                    </svg>
                                    <span class="transition-opacity duration-200 whitespace-nowrap"
                                        :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                        x-show="sidebarExpanded || window.innerWidth < 1024">Room Availability</span>

                                    <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                        class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                        Room Availability
                                    </div>
                                </a>
                            </li>
                        @endcan

                        <!-- Master Vouchers -->
                        <li>
                            <a href="{{ route('vouchers.index') }}"
                                class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('vouchers.*')) bg-indigo-900 @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span class="truncate transition-opacity duration-200"
                                    x-show="sidebarExpanded || window.innerWidth < 1024">Vouchers</span>

                                <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                    class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                    Vouchers
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Financial -->
                @can('financial')
                    <div class="mb-4">
                        <h3 class="text-xs uppercase text-indigo-400 dark:text-indigo-300 font-semibold pl-3 mb-2">
                            <span x-show="!sidebarExpanded && window.innerWidth >= 1024" class="text-center w-6"
                                aria-hidden="true">•••</span>
                            <span x-show="sidebarExpanded || window.innerWidth < 1024"
                                class="transition-opacity duration-200">Financial</span>
                        </h3>
                        <ul class="space-y-1">
                            <!-- Payments -->
                            @can('view_payments')
                                <li>
                                    <a href="{{ route('admin.payments.index') }}"
                                        class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('admin.payments.index')) bg-indigo-900 @endif">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="transition-opacity duration-200 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">Payments</span>
                                        <!-- Tooltip for collapsed state -->
                                        <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                            class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                            Payments
                                        </div>
                                    </a>
                                </li>
                            @endcan

                            <!-- Refunds -->
                            <li>
                                <a href="{{ route('admin.refunds.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('admin.refunds.index')) bg-indigo-900 @endif">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span class="transition-opacity duration-200 whitespace-nowrap"
                                        :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                        x-show="sidebarExpanded || window.innerWidth < 1024">Refunds</span>
                                    <!-- Tooltip for collapsed state -->
                                    <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                        class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                        Refunds
                                    </div>
                                </a>
                            </li>
                            @can('view_refunds')
                            @endcan

                            <!-- Reports -->
                            @can('view_reports')
                                <li>
                                    <a href="{{ route('reports.booking.index') }}"
                                        class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('reports.booking.index')) bg-indigo-900 @endif">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="transition-opacity duration-200 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">Reports</span>
                                        <!-- Tooltip for collapsed state -->
                                        <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                            class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                            Reports
                                        </div>
                                    </a>
                                </li>
                            @endcan

                            <!-- Reports Payments -->
                            @can('view_reports')
                                <li>
                                    <a href="{{ route('reports.payment.index') }}"
                                        class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('reports.payment.*')) bg-indigo-900 @endif">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="transition-opacity duration-200 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">Payment Report</span>
                                        <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                            class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                            Payment Report
                                        </div>
                                    </a>
                                </li>
                            @endcan

                            <!-- Reports Rooms -->
                            @can('view_reports')
                                <li>
                                    <a href="{{ route('reports.rented-rooms.index') }}"
                                        class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('reports.rented-rooms.*')) bg-indigo-900 @endif">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        <span class="transition-opacity duration-200 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">Rented Rooms</span>
                                        <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                            class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                            Rented Rooms
                                        </div>
                                    </a>
                                </li>
                            @endcan

                            
                        </ul>
                    </div>
                @endcan

                <!-- Settings -->
                @can('Settings')
                    <div>
                        <h3 class="text-xs uppercase text-indigo-400 dark:text-indigo-300 font-semibold pl-3 mb-2">
                            <span x-show="!sidebarExpanded && window.innerWidth >= 1024" class="text-center w-6"
                                aria-hidden="true">•••</span>
                            <span x-show="sidebarExpanded || window.innerWidth < 1024"
                                class="transition-opacity duration-200">Settings</span>
                        </h3>
                        <ul class="space-y-1">
                            <!-- Users -->
                            @can('view_users')
                                <li>
                                    <a href="{{ route('users-newManagement') }}"
                                        class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('users-newManagement')) bg-indigo-900 @endif">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span class="transition-opacity duration-200 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">Users</span>
                                        <!-- Tooltip for collapsed state -->
                                        <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                            class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                            Users
                                        </div>
                                    </a>
                                </li>
                            @endcan

                            <!-- Role & Permission -->
                            @can('manage_roles')
                                <li>
                                    <a href="{{ route('users-access-management') }}"
                                        class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('users-access-management')) bg-indigo-900 @endif">
                                        <!-- Icon Gembok -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 11c-1.657 0-3 1.343-3 3v4h6v-4c0-1.657-1.343-3-3-3z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 11V7a5 5 0 10-10 0v4" />
                                            <rect x="6" y="11" width="12" height="10" rx="2" ry="2"
                                                stroke="currentColor" stroke-width="2" fill="none" />
                                        </svg>
                                        <span class="transition-opacity duration-200 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">Role &amp; Permission</span>
                                        <!-- Tooltip for collapsed state -->
                                        <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                            class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                            Role &amp; Permission
                                        </div>
                                    </a>
                                </li>
                            @endcan
                            <!-- Account / Settings -->
                            @can('manage_settings')
                                <li>
                                    <a href=" {{ route('users.show') }} "
                                        class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors group relative @if (Route::is('users.show')) bg-indigo-900 @endif">
                                        <!-- Gear Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="transition-opacity duration-200 whitespace-nowrap"
                                            :class="sidebarExpanded ? 'opacity-100' : 'lg:opacity-0'"
                                            x-show="sidebarExpanded || window.innerWidth < 1024">Settings</span>
                                        <!-- Tooltip for collapsed state -->
                                        <div x-show="!sidebarExpanded && window.innerWidth >= 1024"
                                            class="absolute left-16 bg-gray-900 text-white px-2 py-1 rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">
                                            Settings
                                        </div>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div>
                @endcan
            </div>

            <!-- Expand / collapse button -->
            <div class="pt-3 hidden lg:inline-flex justify-end mt-auto">
                <div class="px-3 py-2">
                    <div class="flex flex-col gap-2">
                        <!-- Persistent Mode Toggle -->
                        <button
                            class="flex items-center justify-center gap-2 px-3 py-2 text-indigo-300 hover:text-white transition-colors rounded-lg border border-indigo-300/30 hover:border-indigo-200/50"
                            @click="sidebarPersistent = !sidebarPersistent"
                            :class="sidebarPersistent ? 'bg-indigo-900/30 text-white border-indigo-400' : ''"
                            title="Toggle persistent sidebar" aria-pressed="false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
                                    x-show="!sidebarPersistent" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 12h14M12 5v14" x-show="sidebarPersistent" />
                            </svg>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
