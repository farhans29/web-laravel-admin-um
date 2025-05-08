<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-gray-800 dark:bg-gray-900 p-4 transition-all duration-200 ease-in-out {{ $variant === 'v2' ? 'border-r border-gray-200 dark:border-gray-700/60' : 'rounded-r-2xl shadow-xs' }}"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false">

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen"
                aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            <a class="flex flex-row gap-3 items-center" href="{{ route('dashboard') }}">
                <img src="/images/Logo.png" alt="" class='w-10 h-10'>
                <p class="text-white text-center"><b>{{ $globalTitle }}</b></p>
            </a>
        </div>

        <!-- Links -->
        <div class="space-y-8">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Pages</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['dashboard'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                        x-data="{ open: {{ in_array(Request::segment(1), ['dashboard']) ? 1 : 0 }} }">
                        <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['dashboard'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                            href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['dashboard'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                        <path
                                            d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                    </svg>
                                    <span
                                        class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 @if (in_array(Request::segment(1), ['dashboard'])) text-white @endif">Dashboard</span>

                                </div>
                                <!-- Icon -->
                                <div
                                    class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['dashboard'])) {{ 'rotate-180' }} @endif"
                                        :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                    </svg>
                                </div>
                            </div>
                        </a>
                        <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                            <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['dashboard'])) {{ 'hidden' }} @endif"
                                :class="open ? 'block!' : 'hidden'">
                                <li class="mb-1 last:mb-0">
                                    <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('dashboard')) {{ 'text-violet-500!' }} @endif"
                                        href="{{ route('dashboard') }}">
                                        <span
                                            class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Main</span>
                                    </a>
                                </li>

                        </div>
                    </li>

                    <!-- Purchasing -->
                    @can('purchasing')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['purchasing'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['purchasing']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['purchasing'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['purchasing'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M9 6.855A3.502 3.502 0 0 0 8 0a3.5 3.5 0 0 0-1 6.855v1.656L5.534 9.65a3.5 3.5 0 1 0 1.229 1.578L8 10.267l1.238.962a3.5 3.5 0 1 0 1.229-1.578L9 8.511V6.855ZM6.5 3.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Zm4.803 8.095c.005-.005.01-.01.013-.016l.012-.016a1.5 1.5 0 1 1-.025.032ZM3.5 11c.474 0 .897.22 1.171.563l.013.016.013.017A1.5 1.5 0 1 1 3.5 11Z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 @if (in_array(Request::segment(1), ['purchasing'])) text-white @endif">Purchasing</span>
                                    </div>
                                    <div
                                        class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['purchasing'])) {{ 'rotate-180' }} @endif"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['purchasing'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_purchase_request')
                                        <li class="mb-1 last:mb-0" x-data="{ openPR: {{ in_array(Request::segment(2), ['purchase-request']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openPR = !openPR">
                                                <span class="text-sm font-medium @if (Route::is('index.pr', 'indexNew.pr', 'formNew.pr', 'indexEdit.pr', 'indexDelete.pr')) {{ 'text-white' }} @endif">Purchase Request</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['purchase-request'])) {{ 'hidden' }} @endif"
                                                :class="openPR ? 'block!' : 'hidden'">
                                                @can('list_purchase_request')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.pr')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.pr') }}">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_purchase_request')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexNew.pr', 'formNew.pr')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('indexNew.pr') }}">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_purchase_request')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexEdit.pr')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('indexEdit.pr') }}">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('submit_purchase_request')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexDelete.pr')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('indexDelete.pr') }}">
                                                            <span class="text-sm font-medium">Submit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Sales -->
                    @can('sales')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['sales'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['sales']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['sales'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['sales'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="m4,9h6v3h-6v-3Zm0,7h2v-2h-2v2Zm4,0h2v-2h-2v2Zm-4,4h2v-2h-2v2Zm4,0h2v-2h-2v2ZM24,6.5v12.094l-3.126-1.9-2.874,1.807-2-1.257v-2.432l2,1.254,2.801-1.756,1.199.729V6.5c0-2.481-2.019-4.5-4.5-4.5-2.254,0-4.109,1.671-4.433,3.836.572.547.933,1.312.933,2.164v16H0V8c0-1.105.608-2.062,1.5-2.583v-.417C1.5,2.243,3.743,0,6.5,0h11c3.584,0,6.5,2.916,6.5,6.5Zm-12,1.5c0-.552-.448-1-1-1H3c-.552,0-1,.448-1,1v14h10v-14Zm-.823-2.982c.271-1.154.848-2.191,1.643-3.018h-6.321c-1.654,0-3,1.346-3,3h7.5c.061,0,.117.014.177.018Z" />
                                        </svg>
                                        <span class="text-sm font-medium ml-4">Sales</span>
                                    </div>
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['sales'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_promotions')
                                        <li class="mb-1 last:mb-0" x-data="{ openPromotion: {{ in_array(Request::segment(2), ['promotion']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openPromotion = !openPromotion">
                                                <span class="text-sm font-medium">Promotion</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['promotion'])) {{ 'hidden' }} @endif"
                                                :class="openPromotion ? 'block!' : 'hidden'">
                                                @can('list_promotions')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_promotions')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_promotions')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_promotions')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Warehouse -->
                    @can('warehouse')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['warehouse'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['warehouse']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['warehouse'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['warehouse'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"
                                            viewBox="0 0 24 24" width="16" height="16">
                                            <path
                                                d="M21,24c-1.654,0-3-1.346-3-3V13c0-.551-.448-1-1-1H7c-.552,0-1,.449-1,1v8c0,1.654-1.346,3-3,3s-3-1.346-3-3V9.724c0-1.665,.824-3.214,2.203-4.145L9.203,.855c1.699-1.146,3.895-1.146,5.594,0l7,4.724c1.379,.931,2.203,2.48,2.203,4.145v11.276c0,1.654-1.346,3-3,3ZM7,10h10c1.654,0,3,1.346,3,3v8c0,.551,.448,1,1,1s1-.449,1-1V9.724c0-.999-.494-1.929-1.322-2.487L13.678,2.513c-1.02-.688-2.336-.688-3.355,0L3.322,7.237c-.828,.558-1.322,1.488-1.322,2.487v11.276c0,.551,.448,1,1,1s1-.449,1-1V13c0-1.654,1.346-3,3-3Zm4,13v-2c0-.552-.447-1-1-1h-1c-.553,0-1,.448-1,1v2c0,.552,.447,1,1,1h1c.553,0,1-.448,1-1Zm0-6v-2c0-.552-.447-1-1-1h-1c-.553,0-1,.448-1,1v2c0,.552,.447,1,1,1h1c.553,0,1-.448,1-1Zm5,6v-2c0-.552-.447-1-1-1h-1c-.553,0-1,.448-1,1v2c0,.552,.447,1,1,1h1c.553,0,1-.448,1-1Z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 @if (in_array(Request::segment(1), ['warehouse'])) text-white @endif">Warehouse</span>
                                    </div>
                                    <div
                                        class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['warehouse'])) {{ 'rotate-180' }} @endif"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['warehouse'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_inventory')
                                        <li class="mb-1 last:mb-0" x-data="{ openInventory: {{ in_array(Request::segment(2), ['inventory']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openInventory = !openInventory">
                                                <span
                                                    class="text-sm font-medium @if (Route::is(
                                                            'index.inventory',
                                                            'indexNew.inventory',
                                                            'inventory.create',
                                                            'indexEdit.inventory',
                                                            'indexDelete.inventory')) {{ 'text-white' }} @endif">Inventory</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['inventory'])) {{ 'hidden' }} @endif"
                                                :class="openInventory ? 'block!' : 'hidden'">
                                                @can('view_inventory')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.inventory')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.inventory') }}">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('list_inventory')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexNew.inventory', 'inventory.create')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('indexNew.inventory') }}">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_inventory')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexEdit.inventory')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('indexEdit.inventory') }}">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_inventory')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexDelete.inventory')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('indexDelete.inventory') }}">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Accounting -->
                    @can('accounting')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['accounting'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['accounting']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['accounting'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['accounting'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="m21,0H6.499c-1.929,0-3.499,1.571-3.499,3.5v3.5c0,.552.448,1,1,1s1-.448,1-1v-3.5c0-.827.673-1.5,1.5-1.5s1.5.673,1.5,1.5c0,1.378,1.122,2.5,2.5,2.5h8.5v13c0,1.654-1.346,3-3,3h-2c-.552,0-1,.448-1,1s.448,1,1,1h2c2.757,0,5-2.243,5-5V6h.5c1.378,0,2.5-1.122,2.5-2.5v-.5c0-1.654-1.346-3-3-3Zm1,3.5c0,.276-.224.5-.5.5h-11c-.276,0-.5-.224-.5-.5,0-.537-.122-1.045-.338-1.5h11.338c.551,0,1,.449,1,1v.5Zm-13.5,6.5H3.5c-1.93,0-3.5,1.57-3.5,3.5v7c0,1.93,1.57,3.5,3.5,3.5h5c1.93,0,3.5-1.57,3.5-3.5v-7c0-1.93-1.57-3.5-3.5-3.5Zm1.5,10.5c0,.827-.673,1.5-1.5,1.5H3.5c-.827,0-1.5-.673-1.5-1.5v-7c0-.827.673-1.5,1.5-1.5h5c.827,0,1.5.673,1.5,1.5v7Zm-1-6.5c0,.552-.448,1-1,1h-4c-.552,0-1-.448-1-1s.448-1,1-1h4c.552,0,1,.448,1,1Zm0,3c0,.552-.448,1-1,1h-.5c-.552,0-1-.448-1-1s.448-1,1-1h.5c.552,0,1,.448,1,1Zm-3.5,0c0,.552-.448,1-1,1h-.5c-.552,0-1-.448-1-1s.448-1,1-1h.5c.552,0,1,.448,1,1Zm3.5,3c0,.552-.448,1-1,1h-.5c-.552,0-1-.448-1-1s.448-1,1-1h.5c.552,0,1,.448,1,1Zm-3.5,0c0,.552-.448,1-1,1h-.5c-.552,0-1-.448-1-1s.448-1,1-1h.5c.552,0,1,.448,1,1Z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Accounting</span>
                                    </div>
                                    <div
                                        class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['accounting'])) {{ 'rotate-180' }} @endif"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['accounting'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_accounts')
                                        <li class="mb-1 last:mb-0" x-data="{ openAccounts: {{ in_array(Request::segment(2), ['accounts']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openAccounts = !openAccounts">
                                                <span class="text-sm font-medium">Accounts</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['accounts'])) {{ 'hidden' }} @endif"
                                                :class="openAccounts ? 'block!' : 'hidden'">
                                                @can('list_accounts')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_accounts')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_accounts')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_accounts')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan

                                            </ul>
                                        </li>
                                    @endcan
                                    @can('view_invoice')
                                        <li class="mb-1 last:mb-0" x-data="{ openInvoices: {{ in_array(Request::segment(2), ['invoices']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openInvoices = !openInvoices">
                                                <span class="text-sm font-medium">Invoices</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['invoices'])) {{ 'hidden' }} @endif"
                                                :class="openInvoices ? 'block!' : 'hidden'">
                                                @can('list_invoice')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_invoice')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_invoice')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_invoice')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Finance -->
                    @can('finance')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['finance'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['finance']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['finance'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['finance'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M6 0a6 6 0 0 0-6 6c0 1.077.304 2.062.78 2.912a1 1 0 1 0 1.745-.976A3.945 3.945 0 0 1 2 6a4 4 0 0 1 4-4c.693 0 1.344.194 1.936.525A1 1 0 1 0 8.912.779 5.944 5.944 0 0 0 6 0Z" />
                                            <path
                                                d="M10 4a6 6 0 1 0 0 12 6 6 0 0 0 0-12Zm-4 6a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Finance</span>
                                    </div>
                                    <div
                                        class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['finance'])) {{ 'rotate-180' }} @endif"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['finance'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    <!-- Payment -->
                                    @can('view_payment')
                                        <li class="mb-1 last:mb-0" x-data="{ openPayment: {{ in_array(Request::segment(2), ['payment']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openPayment = !openPayment">
                                                <span class="text-sm font-medium">Payment</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['payment'])) {{ 'hidden' }} @endif"
                                                :class="openPayment ? 'block!' : 'hidden'">
                                                @can('list_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                    <!-- Reports -->
                                    @can('view_reports')
                                        <li class="mb-1 last:mb-0" x-data="{ openReports: {{ in_array(Request::segment(2), ['reports']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openReports = !openReports">
                                                <span class="text-sm font-medium">Reports</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['reports'])) {{ 'hidden' }} @endif"
                                                :class="openReports ? 'block!' : 'hidden'">
                                                @can('list_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Tax -->
                    @can('tax')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['tax'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['tax']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['tax'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['tax'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="m9.3,17.6v6.4h-1.6v-6.4h-1.9v-1.6h5.5v1.6h-2Zm7.153.027l1.447,6.373h-1.609l-.325-1.4h-2.829l-.33,1.4h-1.607l1.475-6.418c.126-.58.697-1.581,1.883-1.581s1.748.959,1.894,1.627Zm-.859,3.373l-.701-3.019c-.047-.212-.135-.381-.334-.381s-.292.193-.319.321l-.726,3.079h2.081Zm8.406-5h-1.812l-1.088,2.182-1.088-2.182h-1.812l1.994,4-1.994,4h1.812l1.088-2.182,1.088,2.182h1.812l-1.994-4,1.994-4ZM2,3c0-.551.448-1,1-1h16c.552,0,1,.449,1,1v11h2V3c0-1.654-1.346-3-3-3H3C1.346,0,0,1.346,0,3v21h6v-2H2V3Zm16,7H4v-6h14v6Zm-2-4H6v2h10v-2ZM4,14h2v-2h-2v2Zm6,0v-2h-2v2h2Zm4,0v-2h-2v2h2Zm4,0v-2h-2v2h2Z" />
                                        </svg>
                                        <span class="text-sm font-medium ml-4">Tax</span>
                                    </div>
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['tax'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_tax_payment')
                                        <li class="mb-1 last:mb-0" x-data="{ openPayments: {{ in_array(Request::segment(2), ['payment']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openPayments = !openPayments">
                                                <span class="text-sm font-medium">Payment</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['payment'])) {{ 'hidden' }} @endif"
                                                :class="openPayments ? 'block!' : 'hidden'">
                                                @can('list_tax_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_tax_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_tax_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_tax_payments')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                    @can('view_tax_reports')
                                        <li class="mb-1 last:mb-0" x-data="{ openReports: {{ in_array(Request::segment(2), ['reports']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openReports = !openReports">
                                                <span class="text-sm font-medium">Reports</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['reports'])) {{ 'hidden' }} @endif"
                                                :class="openReports ? 'block!' : 'hidden'">
                                                @can('list_tax_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_tax_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_tax_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_tax_reports')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Human Resource -->
                    @can('human_resource')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['human-resource'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['human-resource']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['human-resource'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['human-resource'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M6.753 2.659a1 1 0 0 0-1.506-1.317L2.451 4.537l-.744-.744A1 1 0 1 0 .293 5.207l1.5 1.5a1 1 0 0 0 1.46-.048l3.5-4ZM6.753 10.659a1 1 0 1 0-1.506-1.317l-2.796 3.195-.744-.744a1 1 0 0 0-1.414 1.414l1.5 1.5a1 1 0 0 0 1.46-.049l3.5-4ZM8 4.5a1 1 0 0 1 1-1h6a1 1 0 1 1 0 2H9a1 1 0 0 1-1-1ZM9 11.5a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2H9Z" />
                                        </svg>
                                        <span class="text-sm font-medium ml-4">Human Resource</span>
                                    </div>
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['human-resource'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_employees')
                                        <li class="mb-1 last:mb-0" x-data="{ openEmployees: {{ in_array(Request::segment(2), ['employees']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openEmployees = !openEmployees">
                                                <span class="text-sm font-medium">Employees</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['employees'])) {{ 'hidden' }} @endif"
                                                :class="openEmployees ? 'block!' : 'hidden'">
                                                @can('list_employees')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_employees')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_employees')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_employees')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                    @can('view_payroll')
                                        <li class="mb-1 last:mb-0" x-data="{ openPayroll: {{ in_array(Request::segment(2), ['payroll']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openPayroll = !openPayroll">
                                                <span class="text-sm font-medium">Payroll</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['payroll'])) {{ 'hidden' }} @endif"
                                                :class="openPayroll ? 'block!' : 'hidden'">
                                                @can('list_payroll')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_payroll')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_payroll')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_payroll')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- General Affairs -->
                    @can('general_affairs')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['general-affairs'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['general-affairs']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['general-affairs'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['general-affairs'])) {{ 'text-violet-500' }} @else {{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M3,3c0-1.105,.895-2,2-2s2,.895,2,2-.895,2-2,2-2-.895-2-2Zm9,2c1.381,0,2.5-1.119,2.5-2.5s-1.119-2.5-2.5-2.5-2.5,1.119-2.5,2.5,1.119,2.5,2.5,2.5Zm7,0c1.105,0,2-.895,2-2s-.895-2-2-2-2,.895-2,2,.895,2,2,2Zm-6.999,19s0,0,0,0c0,0,0,0,0,0,0,0,0,0,0,0s0,0,0,0c0,0,0,0,0,0,0,0,0,0,0,0-6.252,0-11.389-4.691-11.95-10.91-.049-.55,.356-1.036,.906-1.086,.563-.048,1.037,.356,1.086,.906,.065,.725,.213,1.422,.419,2.09h3.44c-.159-.616-.283-1.249-.347-1.903-.054-.55,.349-1.039,.898-1.092,.553-.05,1.039,.349,1.092,.898,.07,.722,.227,1.422,.434,2.097h8.044c.207-.675,.364-1.375,.434-2.097,.053-.55,.54-.949,1.092-.898,.55,.053,.952,.542,.898,1.092-.063,.654-.188,1.287-.347,1.903h3.44c.207-.668,.354-1.365,.419-2.09,.05-.55,.526-.956,1.086-.906,.55,.05,.956,.536,.906,1.086-.561,6.219-5.698,10.909-11.95,10.91Zm-3.223-7c1.023,2.079,2.409,3.723,3.221,4.586,.813-.863,2.198-2.507,3.221-4.586h-6.443Zm.627,4.657c-.944-1.169-2.037-2.769-2.829-4.657H3.332c1.308,2.283,3.48,3.972,6.073,4.657Zm11.263-4.657h-3.244c-.792,1.889-1.885,3.488-2.829,4.657,2.593-.685,4.765-2.374,6.073-4.657Zm-12.019-7.063c.518,.193,1.094-.067,1.288-.586,.303-.808,1.132-1.351,2.063-1.351s1.761,.543,2.063,1.351c.193,.524,.784,.777,1.288,.586,.517-.194,.779-.771,.585-1.287-.594-1.585-2.176-2.649-3.937-2.649s-3.343,1.064-3.937,2.649c-.194,.517,.068,1.093,.585,1.287Zm9.703-3.889c-.546,.082-.923,.591-.841,1.138,.082,.546,.589,.915,1.137,.841,1.011-.15,2.074,.413,2.416,1.324,.193,.524,.784,.777,1.288,.586,.517-.194,.779-.771,.585-1.287-.594-1.585-2.176-2.649-3.937-2.649-.22,0-.437,.017-.648,.048ZM1.649,9.937c.504,.192,1.094-.062,1.288-.586,.341-.912,1.405-1.479,2.416-1.324,.546,.075,1.055-.295,1.137-.841,.082-.547-.294-1.056-.841-1.138-.211-.031-.428-.048-.648-.048-1.761,0-3.343,1.064-3.937,2.649-.194,.517,.068,1.093,.585,1.287Z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">General
                                            Affairs</span>
                                    </div>
                                    <div
                                        class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['general-affairs'])) {{ 'rotate-180' }} @endif"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['general-affairs'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_office_supplies')
                                        <li class="mb-1 last:mb-0" x-data="{ openOffice: {{ in_array(Request::segment(2), ['office-supplies']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openOffice = !openOffice">
                                                <span class="text-sm font-medium">Office Supplies</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['office-supplies'])) {{ 'hidden' }} @endif"
                                                :class="openOffice ? 'block!' : 'hidden'">
                                                @can('list_office_supplies')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_office_supplies')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_office_supplies')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_office_supplies')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                    @can('view_facilities')
                                        <li class="mb-1 last:mb-0" x-data="{ openFacilities: {{ in_array(Request::segment(2), ['facilities']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openFacilities = !openFacilities">
                                                <span class="text-sm font-medium">Facilities</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['facilities'])) {{ 'hidden' }} @endif"
                                                :class="openFacilities ? 'block!' : 'hidden'">
                                                @can('list_facilities')
                                                    <li>
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_facilities')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_facilities')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_facilities')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Logistics -->
                    @can('logistics')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['logistics'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['logistics']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['logistics'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['logistics'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="m23,6c0-1.654-1.346-3-3-3h-10c-1.654,0-3,1.346-3,3v10h16V6Zm-9-1h2v3h-2v-3Zm7,9h-12V6c0-.551.448-1,1-1h2v5h6v-5h2c.552,0,1,.449,1,1v8Zm-15,4.038c-.552,0-1-.448-1-1V3.039C5,1.384,3.654.039,2,.039H0v2h2c.552,0,1,.449,1,1v14c0,1.654,1.346,3,3,3v.5c0,1.93,1.57,3.5,3.5,3.5s3.5-1.57,3.5-3.5v-.5h3v.5c0,1.93,1.57,3.5,3.5,3.5s3.5-1.57,3.5-3.5v-.5h1v-2H6Zm5,2.5c0,.827-.673,1.5-1.5,1.5s-1.5-.673-1.5-1.5v-.5h3v.5Zm10,0c0,.827-.673,1.5-1.5,1.5s-1.5-.673-1.5-1.5v-.5h3v.5Z" />
                                        </svg>
                                        <span class="text-sm font-medium ml-4">Logistics</span>
                                    </div>
                                    <div class="flex shrink-0 ml-2">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['logistics'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_shipping')
                                        <li class="mb-1 last:mb-0" x-data="{ openShipping: {{ in_array(Request::segment(2), ['shipping']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openShipping = !openShipping">
                                                <span class="text-sm font-medium">Shipping</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['shipping'])) {{ 'hidden' }} @endif"
                                                :class="openShipping ? 'block!' : 'hidden'">
                                                @can('list_shipping')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_shipping')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_shipping')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_shipping')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                            href="">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan

                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
                    <!-- Archive -->
                    @can('aju')
                        <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['archive', 'document'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                            x-data="{ open: {{ in_array(Request::segment(1), ['archive', 'document']) ? 1 : 0 }} }">
                            <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['archive', 'document'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['archive', 'document'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                            xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"
                                            width="16" height="16" viewBox="0 0 24 24">
                                            <path
                                                d="M19,2h-6.528c-.154,0-.31-.037-.447-.105L8.869,.316c-.415-.207-.878-.316-1.341-.316h-2.528C2.243,0,0,2.243,0,5v12c0,2.757,2.243,5,5,5h3c.552,0,1-.447,1-1s-.448-1-1-1h-3c-1.654,0-3-1.346-3-3V8H22v1c0,.552,.447,1,1,1s1-.448,1-1v-2c0-2.757-2.243-5-5-5ZM2,5c0-1.654,1.346-3,3-3h2.528c.154,0,.31,.037,.447,.105l3.156,1.578c.415,.207,.878,.316,1.341,.316h6.528c1.302,0,2.402,.839,2.816,2H2v-1Zm18.5,12c0,.553-.447,1-1,1h-1.5v1.5c0,.553-.447,1-1,1s-1-.447-1-1v-1.5h-1.5c-.553,0-1-.447-1-1s.447-1,1-1h1.5v-1.5c0-.553,.447-1,1-1s1,.447,1,1v1.5h1.5c.553,0,1,.447,1,1Zm-3.5-7c-3.859,0-7,3.141-7,7s3.141,7,7,7,7-3.141,7-7-3.141-7-7-7Zm0,12c-2.757,0-5-2.243-5-5s2.243-5,5-5,5,2.243,5,5-2.243,5-5,5Z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 @if (in_array(Request::segment(1), ['archive', 'document'])) text-white @endif">Archive</span>
                                    </div>
                                    <div
                                        class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                        <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['archive', 'document'])) {{ 'rotate-180' }} @endif"
                                            :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                            <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['archive', 'document'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_aju')
                                        <li class="mb-1 last:mb-0" x-data="{ openArchive: {{ in_array(Request::segment(2), ['aju']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openArchive = !openArchive">
                                                <span
                                                    class="text-sm font-medium @if (Route::is(
                                                            'index.aju',
                                                            'index.formNew',
                                                            'index.formNew.GetData',
                                                            'index.newaju',
                                                            'index.editaju',
                                                            'index.formUpdate.GetData',
                                                            'index.deleteaju')) {{ 'text-white' }} @endif">AJU</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['aju'])) {{ 'hidden' }} @endif"
                                                :class="openArchive ? 'block!' : 'hidden'">
                                                @can('list_aju')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.aju')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.aju') }}">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_aju')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.newaju', 'index.formNew', 'index.formNew.GetData')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.newaju') }}">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_aju')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.editaju', 'index.formUpdate.GetData')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.editaju') }}">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_aju')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.deleteaju')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.deleteaju') }}">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                            <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['archive', 'document'])) {{ 'hidden' }} @endif"
                                    :class="open ? 'block!' : 'hidden'">
                                    @can('view_document')
                                        <li class="mb-1 last:mb-0" x-data="{ openDocument: {{ in_array(Request::segment(2), ['document']) ? 1 : 0 }} }">
                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                href="#0" @click.prevent="openDocument = !openDocument">
                                                <span
                                                    class="text-sm font-medium @if (Route::is(
                                                            'index.document',
                                                            'index.newDocument',
                                                            'index.form',
                                                            'index.editDocument',
                                                            'index.formEdit',
                                                            'index.DeleteDocument',
                                                            'index.formDelete')) {{ 'text-white' }} @endif">Document</span>
                                            </a>
                                            <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['document'])) {{ 'hidden' }} @endif"
                                                :class="openDocument ? 'block!' : 'hidden'">
                                                @can('list_document')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.document')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.document') }}">
                                                            <span class="text-sm font-medium">List</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('create_document')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.newDocument', 'index.form')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.newDocument') }}">
                                                            <span class="text-sm font-medium">New</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('edit_document')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.editDocument', 'index.formEdit')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.editDocument') }}">
                                                            <span class="text-sm font-medium">Edit</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('delete_document')
                                                    <li class="mb-1 last:mb-0">
                                                        <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.DeleteDocument', 'index.formDelete')) {{ 'text-violet-500!' }} @endif"
                                                            href="{{ route('index.DeleteDocument') }}">
                                                            <span class="text-sm font-medium">Delete</span>
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </li>
                    @endcan
            </div>
            <!-- Master group -->
            @can('master')
                <div>
                    <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                        <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                            aria-hidden="true">•••</span>
                        <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Management</span>
                    </h3>
                    <ul class="mt-3">
                        @can('master')
                            <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['master'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                                x-data="{ open: {{ in_array(Request::segment(1), ['master']) ? 1 : 0 }} }">
                                <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['master'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                    href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['master'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"
                                                viewBox="0 0 24 24" width="16" height="16">
                                                <path
                                                    d="M24,18v6H0v-6c0-1.654,1.346-3,3-3h2.139l2.046,2H3c-.552,0-1,.448-1,1v4H22v-4c0-.552-.448-1-1-1h-4.185l2.046-2h2.139c1.654,0,3,1.346,3,3Zm0-15.5v5.5h-4v2c0,1.103-.897,2-2,2h-5v3.446s1.795-1.794,1.795-1.794l1.414,1.414-2.888,2.889c-1.903,2.012-4.252-1.915-5.526-2.889l1.414-1.414,1.792,1.792v-3.445H6c-1.103,0-2-.897-2-2v-2H0V2.5C0,1.122,1.121,0,2.5,0H7.5c1.379,0,2.5,1.122,2.5,2.5v5.5H6v2h12v-2h-4V2.5c0-1.378,1.121-2.5,2.5-2.5h5c1.379,0,2.5,1.122,2.5,2.5ZM2,6h6V2.5c0-.276-.225-.5-.5-.5H2.5c-.275,0-.5,.224-.5,.5v3.5ZM22,2.5c0-.276-.225-.5-.5-.5h-5c-.275,0-.5,.224-.5,.5v3.5h6V2.5Z" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 @if (in_array(Request::segment(1), ['master'])) text-white @endif">Master</span>
                                        </div>
                                        <div
                                            class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['master'])) {{ 'rotate-180' }} @endif"
                                                :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                                <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                                <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                    <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['master'])) {{ 'hidden' }} @endif"
                                        :class="open ? 'block!' : 'hidden'">
                                        @can('view_master_department')
                                            <li class="mb-1 last:mb-0" x-data="{ openVendor: {{ in_array(Request::segment(2), ['inventory-assets']) ? 1 : 0 }} }">
                                                <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                    href="#0" @click.prevent="openVendor = !openVendor">
                                                    <span
                                                        class="text-sm font-medium @if (Route::is('indexCatalogue.inventory-assets', 'index.inventory-assets', 'indexNew.inventory-assets', 'indexEdit.vendor', 'indexDelete.vendor')) {{ 'text-white' }} @endif">Asset
                                                        Code</span>
                                                </a>
                                                <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['inventory-assets'])) {{ 'hidden' }} @endif"
                                                    :class="openVendor ? 'block!' : 'hidden'">
                                                     @can('list_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexCatalogue.inventory-assets')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexCatalogue.inventory-assets') }}">
                                                                <span class="text-sm font-medium">Catalogue</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('list_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.inventory-assets')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('index.inventory-assets') }}">
                                                                <span class="text-sm font-medium">List</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('create_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexNew.inventory-assets')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexNew.inventory-assets') }}">
                                                                <span class="text-sm font-medium">New</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('edit_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexEdit.vendor')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexEdit.vendor') }}">
                                                                <span class="text-sm font-medium">Edit</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('delete_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexDelete.vendor')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexDelete.vendor') }}">
                                                                <span class="text-sm font-medium">Delete</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                                <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                    <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['master'])) {{ 'hidden' }} @endif"
                                        :class="open ? 'block!' : 'hidden'">
                                        @can('view_master_department')
                                            <li class="mb-1 last:mb-0" x-data="{ openDepartment: {{ in_array(Request::segment(2), ['department']) ? 1 : 0 }} }">
                                                <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                    href="#0" @click.prevent="openDepartment = !openDepartment">
                                                    <span
                                                        class="text-sm font-medium @if (Route::is('index.department', 'indexNew.department', 'indexEdit.department', 'indexDelete.department')) {{ 'text-white' }} @endif">Department</span>
                                                </a>
                                                <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['department'])) {{ 'hidden' }} @endif"
                                                    :class="openDepartment ? 'block!' : 'hidden'">
                                                    @can('list_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.department')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('index.department') }}">
                                                                <span class="text-sm font-medium">List</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('create_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexNew.department')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexNew.department') }}">
                                                                <span class="text-sm font-medium">New</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('edit_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexEdit.department')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexEdit.department') }}">
                                                                <span class="text-sm font-medium">Edit</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('delete_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexDelete.department')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexDelete.department') }}">
                                                                <span class="text-sm font-medium">Delete</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                                <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                    <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['master'])) {{ 'hidden' }} @endif"
                                        :class="open ? 'block!' : 'hidden'">
                                        @can('view_master_department')
                                            <li class="mb-1 last:mb-0" x-data="{ openDoc_type: {{ in_array(Request::segment(2), ['documentType']) ? 1 : 0 }} }">
                                                <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                    href="#0" @click.prevent="openDoc_type = !openDoc_type">
                                                    <span
                                                        class="text-sm font-medium @if (Route::is('index.documentType', 'indexNew.documentType', 'indexEdit.documentType', 'indexDelete.documentType')) {{ 'text-white' }} @endif">Document
                                                        Type</span>
                                                </a>
                                                <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['documentType'])) {{ 'hidden' }} @endif"
                                                    :class="openDoc_type ? 'block!' : 'hidden'">
                                                    @can('list_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.documentType')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('index.documentType') }}">
                                                                <span class="text-sm font-medium">List</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('create_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexNew.documentType')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexNew.documentType') }}">
                                                                <span class="text-sm font-medium">New</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('edit_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexEdit.documentType')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexEdit.documentType') }}">
                                                                <span class="text-sm font-medium">Edit</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('delete_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexDelete.documentType')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexDelete.documentType') }}">
                                                                <span class="text-sm font-medium">Delete</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                                <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                    <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['master'])) {{ 'hidden' }} @endif"
                                        :class="open ? 'block!' : 'hidden'">
                                        @can('view_master_department')
                                            <li class="mb-1 last:mb-0" x-data="{ openVendor: {{ in_array(Request::segment(2), ['vendor']) ? 1 : 0 }} }">
                                                <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                    href="#0" @click.prevent="openVendor = !openVendor">
                                                    <span
                                                        class="text-sm font-medium @if (Route::is('index.vendor', 'indexNew.vendor', 'indexEdit.vendor', 'indexDelete.vendor')) {{ 'text-white' }} @endif">Vendor</span>
                                                </a>
                                                <ul class="pl-4 mt-1 @if (!in_array(Request::segment(2), ['vendor'])) {{ 'hidden' }} @endif"
                                                    :class="openVendor ? 'block!' : 'hidden'">
                                                    @can('list_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('index.vendor')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('index.vendor') }}">
                                                                <span class="text-sm font-medium">List</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('create_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexNew.vendor')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexNew.vendor') }}">
                                                                <span class="text-sm font-medium">New</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('edit_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexEdit.vendor')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexEdit.vendor') }}">
                                                                <span class="text-sm font-medium">Edit</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('delete_master_department')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('indexDelete.vendor')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('indexDelete.vendor') }}">
                                                                <span class="text-sm font-medium">Delete</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcan

            <!-- More group -->
            @can('account_settings')
                <div>
                    <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                        <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                            aria-hidden="true">•••</span>
                        <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">More</span>
                    </h3>
                    <ul class="mt-3">
                        <!-- Settings -->
                        @can('account_settings')
                            <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 bg-linear-to-r @if (in_array(Request::segment(1), ['settings'])) {{ 'from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }} @endif"
                                x-data="{ open: {{ in_array(Request::segment(1), ['settings']) ? 1 : 0 }} }">
                                <a class="block text-gray-400 hover:text-white truncate transition @if (!in_array(Request::segment(1), ['settings'])) {{ 'hover:text-gray-900 dark:hover:text-white' }} @endif"
                                    href="#0" @click.prevent="open = !open; sidebarExpanded = true">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="shrink-0 fill-current @if (in_array(Request::segment(1), ['settings'])) {{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }} @endif"
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M10.5 1a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2h-1.145a3.502 3.502 0 0 1-6.71 0H1a1 1 0 0 1 0-2h6.145A3.502 3.502 0 0 1 10.5 1ZM9 4.5a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM5.5 9a3.502 3.502 0 0 1 3.355 2.5H15a1 1 0 1 1 0 2H8.855a3.502 3.502 0 0 1-6.71 0H1a1 1 0 1 1 0-2h1.145A3.502 3.502 0 0 1 5.5 9ZM4 12.5a1.5 1.5 0 1 0 3 0 1.5 1.5 0 0 0-3 0Z"
                                                    fill-rule="evenodd" />
                                            </svg>
                                            <span
                                                class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 @if (in_array(Request::segment(1), ['settings'])) text-white @endif">Account
                                                Settings</span>
                                        </div>
                                        <!-- Icon -->
                                        <div
                                            class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                            <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-gray-400 dark:text-gray-500 @if (in_array(Request::segment(1), ['settings'])) {{ 'rotate-180' }} @endif"
                                                :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                                <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                                <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                                    <ul class="pl-8 mt-1 @if (!in_array(Request::segment(1), ['settings'])) {{ 'hidden' }} @endif"
                                        :class="open ? 'block!' : 'hidden'">
                                        @can('view_os_menu')
                                            <li class="mb-1 last:mb-0">
                                                <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('users-access-management')) {{ 'text-violet-500!' }} @endif"
                                                    href="{{ route('users-access-management') }}">
                                                    <span
                                                        class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">OS
                                                        Menu List
                                                    </span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('view_user_settings')
                                            <li class="mb-1 last:mb-0" x-data="{ openPR: {{ in_array(Request::segment(2), ['users-management', 'users-newManagement', 'users-editManagement', 'users-deleteManagement']) ? 1 : 0 }} }">
                                                <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate"
                                                    href="#0" @click.prevent="openPR = !openPR">
                                                    <span
                                                        class="text-sm font-medium @if (Route::is('users-management', 'users-newManagement', 'users-editManagement', 'users-deleteManagement')) {{ 'text-white' }} @endif">User
                                                        Settings</span>
                                                </a>
                                                <ul class="pl-4 mt-1 @if (
                                                    !in_array(Request::segment(2), [
                                                        'users-management',
                                                        'users-newManagement',
                                                        'users-editManagement',
                                                        'users-deleteManagement',
                                                    ])) {{ 'hidden' }} @endif"
                                                    :class="openPR ? 'block!' : 'hidden'">
                                                    @can('list_user_settings')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate @if (Route::is('users-management')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('users-management') }}">
                                                                <span class="text-sm font-medium">List</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('create_user_settings')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate  @if (Route::is('users-newManagement')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('users-newManagement') }}">
                                                                <span class="text-sm font-medium">New</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('edit_user_settings')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate  @if (Route::is('users-editManagement')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('users-editManagement') }}">
                                                                <span class="text-sm font-medium">Edit</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('edit_user_settings')
                                                        <li class="mb-1 last:mb-0">
                                                            <a class="block text-gray-400 hover:text-white hover:text-gray-700 dark:hover:text-gray-200 transition truncate  @if (Route::is('users-deleteManagement')) {{ 'text-violet-500!' }} @endif"
                                                                href="{{ route('users-deleteManagement') }}">
                                                                <span class="text-sm font-medium">Delete</span>
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endcan
                    </ul>
                </div>
            @endcan


        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="w-12 pl-4 pr-3 py-2">
                <button
                    class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors"
                    @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180"
                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>
