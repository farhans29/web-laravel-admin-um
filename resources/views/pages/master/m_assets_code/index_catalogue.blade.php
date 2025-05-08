<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header with gradient text -->
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600">
                Asset Inventory Catalogue
            </h2>
            <div class="relative group">
                <div
                    class="absolute -inset-0.5 bg-gradient-to-r from-pink-600 to-purple-600 rounded-lg blur opacity-75 group-hover:opacity-100 transition duration-200">
                </div>
                <a href="" class="relative flex items-center px-4 py-2 bg-gray-900 rounded-lg leading-none">
                    <span class="text-white font-medium">+ Add New Asset</span>
                </a>
            </div>
        </div>

        <!-- Modern search and filter section -->
        <div class="backdrop-blur-sm bg-white/30 p-6 rounded-xl shadow-lg border border-white/20 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Search with icon transition -->
                <div class="relative flex-1 max-w-md">
                    <form method="GET" action="{{ route('indexCatalogue.inventory-assets') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-gray-400 transition-all duration-300 group-hover:text-indigo-500"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Search assets..."
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl bg-white/70 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 placeholder-gray-400 focus:shadow-lg focus:pl-12">
                    </form>
                </div>

                <!-- Filter and show dropdown -->
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="appearance-none bg-white/70 border border-gray-200 rounded-xl pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>Show: 5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>Show: 10</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>Show: 15</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>Show: 20</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern asset cards grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($assets as $asset)
                <div class="group relative cursor-pointer" x-data="{ openModal: false }" @click="openModal = true">
                    <!-- Card background glow effect -->
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-blue-400 to-purple-600 rounded-2xl blur opacity-0 group-hover:opacity-75 transition duration-500">
                    </div>

                    <!-- Main card -->
                    <div
                        class="relative h-full bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-white/20 shadow-lg hover:shadow-xl transition-all duration-300 transform group-hover:-translate-y-1">
                        <!-- Image with gradient overlay -->
                        <div class="relative h-48 overflow-hidden">
                            @if ($asset->image)
                                <img src="{{ asset('storage/' . $asset->image) }}" alt="{{ $asset->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-300 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif
                            <!-- Status badge -->
                            <div class="absolute top-3 right-3">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $asset->status === 'available' ? 'bg-green-100 text-green-800' : ($asset->status === 'in_use' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ str_replace('_', ' ', $asset->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Card content -->
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-900 truncate">{{ $asset->name }}</h3>
                                <span
                                    class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                    #{{ $asset->asset_id }}
                                </span>
                            </div>

                            <!-- Metadata with icons -->
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <span>{{ $asset->category ?? 'No category' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $asset->location ?? 'Location not set' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>{{ $asset->purchase_date ? $asset->purchase_date->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true" @click.away="openModal = false">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <!-- Background overlay -->
                            <div x-show="openModal" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true">
                            </div>

                            <!-- Modal content -->
                            <div
                                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                                <div class="bg-white px-6 py-6 sm:px-8 sm:py-8">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900" id="modal-title">
                                                {{ $asset->name }}</h3>
                                            <div class="mt-1 flex items-center">
                                                <span
                                                    class="text-sm font-medium px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                                                    #{{ $asset->asset_id }}
                                                </span>
                                                <span
                                                    class="ml-2 text-sm font-medium px-2.5 py-0.5 rounded-full {{ $asset->status === 'available' ? 'bg-green-100 text-green-800' : ($asset->status === 'in_use' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                                    {{ str_replace('_', ' ', $asset->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <button @click="openModal = false" type="button"
                                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <span class="sr-only">Close</span>
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                        <!-- Asset Image -->
                                        <div class="sm:col-span-2">
                                            <div class="h-64 w-full rounded-xl overflow-hidden bg-gray-100">
                                                @if ($asset->image)
                                                    <img src="{{ asset('storage/' . $asset->image) }}"
                                                        alt="{{ $asset->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-16 h-16 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="1"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Basic Info -->
                                        <div>
                                            <h4 class="text-lg font-medium text-gray-900 mb-3">Basic Information</h4>
                                            <div class="space-y-3">
                                                <div>
                                                    <p class="text-sm text-gray-500">Category</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->category ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Brand</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->mainBrand->name ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Model</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->modelBrand->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location & Dates -->
                                        <div>
                                            <h4 class="text-lg font-medium text-gray-900 mb-3">Location & Dates</h4>
                                            <div class="space-y-3">
                                                <div>
                                                    <p class="text-sm text-gray-500">Department</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->department_name ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Sub Department</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->sub_department_name ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Purchase Date</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->purchase_date ? $asset->purchase_date->format('M d, Y') : 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Specifications -->
                                        <div class="sm:col-span-2">
                                            <h4 class="text-lg font-medium text-gray-900 mb-3">Specifications</h4>
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Type</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->type ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Color</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->color ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Unit</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->unit ?? '-' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-500">Part Number</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $asset->part_number ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Additional Notes -->
                                        @if ($asset->notes)
                                            <div class="sm:col-span-2">
                                                <h4 class="text-lg font-medium text-gray-900 mb-3">Additional Notes
                                                </h4>
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <p class="text-sm text-gray-700">{{ $asset->notes }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse">
                                    <button type="button"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="openModal = false">
                                        Close
                                    </button>
                                    <a href=""
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Edit Asset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modern pagination -->
        <div class="mt-8 backdrop-blur-sm bg-white/30 p-4 rounded-xl shadow-lg border border-white/20">
            <div class="px-6 py-4">
                {{ $assets->links() }}
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .pagination-modern .page-item.active .page-link {
                @apply bg-indigo-600 text-white border-indigo-600;
            }

            .pagination-modern .page-link {
                @apply bg-white/70 border-gray-200 text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-200;
            }

            .pagination-modern .page-item.disabled .page-link {
                @apply bg-white/30 text-gray-400 cursor-not-allowed;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Alpine.js is already included in your app layout
            // The modal functionality is handled by the x-data and x-show directives
        </script>
    @endpush
</x-app-layout>
