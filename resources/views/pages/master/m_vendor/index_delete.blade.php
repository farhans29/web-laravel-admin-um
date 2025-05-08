<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Vendor Management</h2>
        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Vendor List</h2>
                <div class="relative">
                    <form method="GET" action="{{ route('index.vendor') }}">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Search..."
                            class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-48">
                        <div class="absolute left-3 top-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendor's Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendor's Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                City</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Country</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Phone</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                TOP (days)</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                POS Code</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NPWP ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vendor->vendor_type }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 break-words">{{ $vendor->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 break-words">{{ $vendor->address }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->city }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->country }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->phone }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->termin }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->zip_code }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->npwp_id }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                {{ $vendor->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $vendor->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    <form action="{{ route('vendor.softDelete', $vendor->idsupplier) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="delete-document-type bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded cursor-pointer">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No vendor data found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('index.vendor') }}">
                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="border border-gray-300 rounded px-4 py-2 w-32">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        </select>
                    </form>
                </div>
                <div class="px-6 py-4">{{ $vendors->links() }}</div>
            </div>
        </div>
    </div>

    <script></script>
</x-app-layout>
