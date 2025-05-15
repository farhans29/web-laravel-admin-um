<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-gray-800 dark:bg-gray-900 p-4 transition-all duration-200 ease-in-out {{ $variant === 'v2' ? 'border-r border-gray-200 dark:border-gray-700/60' : 'rounded-r-2xl shadow-xs' }}">

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
            <a class="flex flex-row gap-3 items-center" href="{{ route('dashboard') }}">
                <img src="/images/frist_icon.png" alt="Booking Logo" class='w-10 h-10'>
                <p class="text-white text-center text-lg font-bold hidden lg:block lg:sidebar-expanded:block">
                    {{ $globalTitle }}</p>
            </a>
        </div>

        <!-- Links -->
        <div class="space-y-1">
            <!-- Dashboard -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-indigo-400 dark:text-indigo-300 font-semibold pl-3 mb-2">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Management</span>
                </h3>
                <ul class="space-y-1">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors @if (Route::is('dashboard')) bg-indigo-900 @endif">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Dashboard</span>
                        </a>
                    </li>

                    <!-- Bookings -->
                    <li x-data="{ open: @json(Route::is('checkin.index', 'checkout.index')) }">
                        <a @click="open = !open"
                            class="flex items-center justify-between gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer @if (Route::is('checkin.index', 'checkout.index')) bg-indigo-900 @endif">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="hidden lg:block lg:sidebar-expanded:block">Bookings</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                        <ul x-show="open" class="pl-8 mt-1 space-y-1">
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">All Bookings</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">New Reservations</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('checkin.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('checkin.index')) bg-indigo-900 @endif">
                                    <span class="text-xs">Check-ins</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('checkout.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors @if (Route::is('checkout.index')) bg-indigo-900 @endif">
                                    <span class="text-xs">Check-outs</span>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <!-- Properties -->
                    <li x-data="{ open: false }">
                        <a @click="open = !open"
                            class="flex items-center justify-between gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span class="hidden lg:block lg:sidebar-expanded:block">Properties</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                        <ul x-show="open" class="pl-8 mt-1 space-y-1">
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">All Properties</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">Houses</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">Hotels</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">Apartments</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">Villas</span>
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-2 text-indigo-200 rounded-lg hover:bg-indigo-700/50 transition-colors">
                                    <span class="text-xs">Add New</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Rooms/Units -->
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Rooms/Units</span>
                        </a>
                    </li>

                    <!-- Customers -->
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Customers</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Financial -->
            <div class="mb-4">
                <h3 class="text-xs uppercase text-indigo-400 dark:text-indigo-300 font-semibold pl-3 mb-2">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Financial</span>
                </h3>
                <ul class="space-y-1">
                    <!-- Payments -->
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Payments</span>
                        </a>
                    </li>

                    <!-- Invoices -->
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Invoices</span>
                        </a>
                    </li>

                    <!-- Reports -->
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Reports</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Settings -->
            <div>
                <h3 class="text-xs uppercase text-indigo-400 dark:text-indigo-300 font-semibold pl-3 mb-2">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Settings</span>
                </h3>
                <ul class="space-y-1">
                    <!-- Users -->
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Users</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="hidden lg:block lg:sidebar-expanded:block">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="px-3 py-2">
                <button class="text-indigo-300 hover:text-white transition-colors flex items-center gap-2"
                    @click="sidebarExpanded = !sidebarExpanded">
                    <span class="hidden lg:block lg:sidebar-expanded:block text-sm">Collapse</span>
                    <svg class="shrink-0 fill-current text-indigo-300 sidebar-expanded:rotate-180"
                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
