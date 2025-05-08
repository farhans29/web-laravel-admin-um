<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Modern Header with Gradient -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Document Management
                </h2>
                <p class="text-gray-500 mt-1">Efficient document tracking and organization</p>
            </div>
        </div>

        <!-- Modern Card Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Card Header with Glass Effect -->
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Document List</h2>

                <!-- Search and Pagination -->
                <div class="flex flex-col md:flex-row md:items-center gap-4 mt-4 md:mt-0">
                    <div class="relative group">
                        <form method="GET" action="{{ route('index.document') }}">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-focus-within:text-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search documents..."
                                class="block w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 placeholder-gray-400">
                        </form>
                    </div>

                    <form method="GET" action="{{ route('index.document') }}" class="flex items-center">
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

            <!-- Modern Table Design -->
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'doc_type', 'sort_direction' => $sortField == 'doc_type' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Type
                                    @if ($sortField == 'doc_type')
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'date', 'sort_direction' => $sortField == 'date' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Date
                                    @if ($sortField == 'date')
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'description', 'sort_direction' => $sortField == 'description' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'no_document', 'sort_direction' => $sortField == 'no_document' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Doc Number
                                    @if ($sortField == 'no_document')
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'department_id', 'sort_direction' => $sortField == 'department_id' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Department
                                    @if ($sortField == 'department_id')
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'sub_department_id', 'sort_direction' => $sortField == 'sub_department_id' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Sub Department
                                    @if ($sortField == 'sub_department_id')
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'file_name', 'sort_direction' => $sortField == 'file_name' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    File
                                    @if ($sortField == 'file_name')
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
                                <a href="{{ route('index.document', array_merge(request()->query(), ['sort_field' => 'created_by', 'sort_direction' => $sortField == 'created_by' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Author
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
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($archives as $index => $archive)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + $archives->firstItem() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $archive->doc_type ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->date ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    {{ $archive->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-mono text-indigo-600">{{ $archive->no_document ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->subDepartment->parent->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->subDepartment->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                    <a onclick="openFileInNewTab('{{ $archive->pdfblob }}')"
                                        class="text-indigo-600 hover:text-indigo-800 cursor-pointer flex items-center">

                                        <div class="flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>

                                        <span class="whitespace-normal break-words">
                                            {{ $archive->file_name ?? '-' }}
                                        </span>

                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                            {{ substr($archive->createdByUser->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="ml-2">
                                            {{ $archive->createdByUser->name ?? '-' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <button onclick="openFileInNewTab('{{ $archive->pdfblob }}')"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 cursor-pointer">
                                        View
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No documents found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to
                                            find what you're looking for.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modern Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="px-6 py-4">
                    {{ $archives->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function openFileInNewTab(base64Data, fileName) {
            if (!base64Data) {
                showToast('File data is missing or invalid', 'error');
                return;
            }

            // Determine file type from file name or base64 data
            const fileType = getFileType(fileName || base64Data);

            if (fileType === 'pdf') {
                openPdf(base64Data);
            } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileType)) {
                openImage(base64Data, fileType);
            } else {
                showToast('Unsupported file type', 'error');
            }
        }

        function getFileType(data) {
            // Check if data is a filename with extension
            if (data.includes('.')) {
                const extension = data.split('.').pop().toLowerCase();
                return extension;
            }

            // Otherwise check base64 prefix
            if (data.startsWith('data:')) {
                return data.split(';')[0].split('/')[1];
            }

            // Default to PDF (for backward compatibility)
            return 'pdf';
        }

        function openPdf(base64Data) {
            const byteCharacters = atob(base64Data);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], {
                type: 'application/pdf'
            });
            openBlobInNewTab(blob);
        }

        function openImage(base64Data, fileType) {
            // Create proper base64 string if it's not already formatted
            let base64String = base64Data;
            if (!base64Data.startsWith('data:')) {
                base64String = `data:image/${fileType};base64,${base64Data}`;
            }

            // Open directly in new tab
            window.open(base64String, '_blank');
        }

        function openBlobInNewTab(blob) {
            const blobUrl = URL.createObjectURL(blob);
            window.open(blobUrl, '_blank');
            URL.revokeObjectURL(blobUrl);
        }

        function showToast(message, type = 'error') {
            const colors = {
                error: 'bg-red-500',
                success: 'bg-green-500',
                warning: 'bg-yellow-500'
            };

            const toast = document.createElement('div');
            toast.className =
                `fixed top-4 right-4 z-50 px-4 py-2 rounded-md shadow-lg ${colors[type]} text-white animate-fade-in-up`;
            toast.innerHTML = `
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span>${message}</span>
            </div>
        `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('animate-fade-in-up');
                toast.classList.add('animate-fade-out');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
</x-app-layout>
