<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    AJU Management</h1>
                <p class="text-gray-500 mt-1">Manage and view all AJU documents</p>
            </div>
        </div>

        <!-- Card Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Card Header -->
            <div
                class="px-6 py-4 border-b border-gray-200 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">AJU Documents List</h2>
                </div>
                <!-- Search and Pagination -->
                <div class="flex flex-col md:flex-row md:items-center gap-4 mt-4 md:mt-0">
                    <div class="relative group">
                        <form method="GET" action="{{ route('index.editaju') }}">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-focus-within:text-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search No AJU..."
                                class="block w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 placeholder-gray-400">
                        </form>
                    </div>

                    <form method="GET" action="{{ route('index.editaju') }}" class="flex items-center">
                        <label for="per_page" class="text-sm text-gray-600 mr-2">Show:</label>
                        <div class="relative">
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="appearance-none bg-white border border-gray-200 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('index.editaju', array_merge(request()->query(), ['sort_field' => 'department', 'sort_direction' => $sortField == 'department' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Department
                                    @if ($sortField == 'department')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('index.editaju', array_merge(request()->query(), ['sort_field' => 'document_number', 'sort_direction' => $sortField == 'document_number' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Document Number
                                    @if ($sortField == 'document_number')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('index.editaju', array_merge(request()->query(), ['sort_field' => 'description', 'sort_direction' => $sortField == 'description' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Description
                                    @if ($sortField == 'description')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('index.editaju', array_merge(request()->query(), ['sort_field' => 'created_by', 'sort_direction' => $sortField == 'created_by' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Created By
                                    @if ($sortField == 'created_by')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('index.editaju', array_merge(request()->query(), ['sort_field' => 'created_at', 'sort_direction' => $sortField == 'created_at' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Created At
                                    @if ($sortField == 'created_at')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($ajus as $index => $aju)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ ($ajus->currentPage() - 1) * $ajus->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">
                                                {{ $aju->department->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                    {{ $aju->no_docs }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $aju->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                            {{ substr($aju->createdByUser->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="ml-2">
                                            {{ $aju->createdByUser->name ?? '-' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($aju->created_at)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                    <div class="flex justify-center space-x-2">
                                        @if (optional($aju->archives)->where('pdfblob', '!=', null)->isNotEmpty())
                                            <div x-data="modal()">
                                                <!-- Tombol buka modal -->
                                                <button
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 cursor-pointer"
                                                    type="button"
                                                    @click.prevent="modalOpenDetail = true; loadDetails(@js($aju->details))"
                                                    aria-controls="modalDetail">
                                                    View
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                <!-- Backdrop -->
                                                <div class="fixed inset-0 bg-black/40 z-40" x-show="modalOpenDetail"
                                                    x-transition:enter="transition-opacity ease-out duration-200"
                                                    x-transition:enter-start="opacity-0"
                                                    x-transition:enter-end="opacity-100"
                                                    x-transition:leave="transition-opacity ease-in duration-100"
                                                    x-transition:leave-start="opacity-100"
                                                    x-transition:leave-end="opacity-0" x-cloak>
                                                </div>

                                                <!-- Dialog -->
                                                <div id="modalDetail"
                                                    class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6 py-8"
                                                    x-show="modalOpenDetail"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 translate-y-8"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 translate-y-8" role="dialog"
                                                    aria-modal="true" x-cloak>
                                                    <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full overflow-auto max-h-full"
                                                        @click.outside="modalOpenDetail = false"
                                                        @keydown.escape.window="modalOpenDetail = false">
                                                        <!-- Header -->
                                                        <div
                                                            class="flex items-center justify-between px-6 py-4 border-b">
                                                            <h2 class="text-lg font-semibold text-gray-800">
                                                                Dokumen AJU - {{ $aju->no_docs }}
                                                            </h2>
                                                            <button class="text-gray-500 hover:text-gray-700"
                                                                @click="modalOpenDetail = false">
                                                                <svg class="w-6 h-6" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round">
                                                                    <path d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>

                                                        <!-- Isi modal -->
                                                        <div class="px-6 py-5 space-y-4 text-sm text-gray-700">
                                                            <template x-for="(detail, index) in details"
                                                                :key="index">
                                                                <template x-if="detail.archive">
                                                                    <div
                                                                        class="flex items-center justify-between bg-blue-50 border border-blue-300 rounded-lg p-4">
                                                                        <div
                                                                            class="flex items-center gap-3 overflow-hidden">
                                                                            <svg class="w-6 h-6 text-blue-600"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round">
                                                                                <path
                                                                                    d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                                            </svg>
                                                                            <span
                                                                                class="truncate font-medium text-blue-800"
                                                                                x-text="detail.archive.file_name"></span>
                                                                        </div>
                                                                        <button
                                                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 text-xs font-medium rounded-md transition"
                                                                            @click="openPdfInNewTab(detail.archive.pdfblob)">
                                                                            View File
                                                                        </button>
                                                                    </div>
                                                                </template>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <a onclick="window.location='{{ route('index.formUpdate.GetData', ['id_aju' => $aju->id_aju]) }}'"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 cursor-pointer">
                                                Edit
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @else
                                            <div class="flex space-x-2">
                                                <span
                                                    class="inline-flex items-center justify-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-red-800 bg-red-100 w-full">
                                                    No File
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No documents found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $ajus->links() }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                details: [],

                loadDetails(details) {
                    // Filter hanya detail yang memiliki archive dengan pdfblob
                    this.details = details.filter(detail =>
                        detail.archive &&
                        detail.archive.pdfblob &&
                        detail.archive.pdfblob !== ''
                    );

                    console.log('Loaded details:', this.details); // Untuk debugging
                },

                openPdfInNewTab(base64Data) {
                    if (base64Data) {
                        const byteCharacters = atob(base64Data);
                        const byteNumbers = new Array(byteCharacters.length);
                        for (let i = 0; i < byteCharacters.length; i++) {
                            byteNumbers[i] = byteCharacters.charCodeAt(i);
                        }
                        const byteArray = new Uint8Array(byteNumbers);
                        const blob = new Blob([byteArray], {
                            type: 'application/pdf'
                        });

                        const blobUrl = URL.createObjectURL(blob);
                        window.open(blobUrl, '_blank');

                        URL.revokeObjectURL(blobUrl);
                    } else {
                        Toastify({
                            text: "PDF data is missing or invalid.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#ff4d4d",
                                color: "#fff",
                                borderRadius: "5px",
                                padding: "10px"
                            }
                        }).showToast();
                    }
                }
            }));
        });

        function openPdfInNewTab(base64Data) {
            if (base64Data) {

                const byteCharacters = atob(base64Data);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], {
                    type: 'application/pdf'
                });


                const blobUrl = URL.createObjectURL(blob);
                window.open(blobUrl, '_blank');


                URL.revokeObjectURL(blobUrl);
            } else {

                Toastify({
                    text: "PDF data is missing or invalid.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#ff4d4d",
                        color: "#fff",
                        borderRadius: "5px",
                        padding: "10px"
                    }
                }).showToast();
            }
        }
    </script>
</x-app-layout>
