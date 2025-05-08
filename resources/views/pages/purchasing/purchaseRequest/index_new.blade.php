<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                Purchase Request Management
            </h1>
            <p class="mt-1 text-gray-500 dark:text-gray-400">
                Streamline your procurement process with our Purchase Request System.
            </p>

            <!-- Status Legend -->
            <div class="mt-6 flex flex-wrap items-center gap-4">
                <div class="flex items-center transition transform hover:scale-110">
                    <div class="h-3 w-3 rounded-full bg-gray-400 mr-2"></div>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Draft</span>
                </div>
                <div class="flex items-center transition transform hover:scale-110">
                    <div class="h-3 w-3 rounded-full bg-sky-400 mr-2"></div>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Price Updated / PR Printed /
                        Waiting Quotation</span>
                </div>
                <div class="flex items-center transition transform hover:scale-110">
                    <div class="h-3 w-3 rounded-full bg-yellow-300 mr-2"></div>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Quotation Submitted / Waiting
                        Approval 1 / HQ 1 Approved / HQ 2 Approved</span>
                </div>
                <div class="flex items-center transition transform hover:scale-110">
                    <div class="h-3 w-3 rounded-full bg-green-600 mr-2"></div>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">HQ 3 Approved</span>
                </div>
                <div class="flex items-center transition transform hover:scale-110">
                    <div class="h-3 w-3 rounded-full bg-red-500 mr-2"></div>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">HQ 1/2/3 Denied / Canceled</span>
                </div>
            </div>

            <!-- Tombol Add New PR -->
            <div class="mt-6">
                <div class="relative group inline-block">
                    <div
                        class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-lg blur opacity-75 group-hover:opacity-100 transition duration-200">
                    </div>
                    <a href="{{ route('formNew.pr') }}"
                        class="relative flex items-center px-3 py-1.5 bg-gradient-to-r from-indigo-600 to-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-wider hover:shadow-lg active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New PR
                    </a>
                </div>
            </div>
        </div>



        <!-- Card Container -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header with Search -->
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Purchase Request List</h2>

                <!-- Search and Pagination -->
                <div class="flex flex-col md:flex-row md:items-center gap-4 mt-4 md:mt-0">
                    <div class="relative group">
                        <form method="GET" action="{{ route('index.pr') }}">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-focus-within:text-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search assets..."
                                class="block w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 placeholder-gray-400">
                        </form>
                    </div>

                    <form method="GET" action="{{ route('index.pr') }}" class="flex items-center">
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

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'pr_date', 'sort_direction' => $sortField == 'pr_date' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    PR Date
                                    @if ($sortField == 'pr_date')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'delivery_date', 'sort_direction' => $sortField == 'delivery_date' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Delivery Date
                                    @if ($sortField == 'delivery_date')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'idreqform', 'sort_direction' => $sortField == 'idreqform' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Purchase Request #
                                    @if ($sortField == 'idreqform')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'company_id', 'sort_direction' => $sortField == 'company_id' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Company
                                    @if ($sortField == 'company_id')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'reqlevel', 'sort_direction' => $sortField == 'reqlevel' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Request Level
                                    @if ($sortField == 'reqlevel')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'applicant', 'sort_direction' => $sortField == 'applicant' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Applicant
                                    @if ($sortField == 'applicant')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'approvalstat', 'sort_direction' => $sortField == 'approvalstat' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Review Status
                                    @if ($sortField == 'approvalstat')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'approval2_to', 'sort_direction' => $sortField == 'approval2_to' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Approval SITE To
                                    @if ($sortField == 'approval2_to')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('index.pr', array_merge(request()->query(), ['sort_field' => 'approval3_to', 'sort_direction' => $sortField == 'approval3_to' && $sortDirection == 'asc' ? 'desc' : 'asc'])) }}"
                                    class="flex items-center">
                                    Review HQ To
                                    @if ($sortField == 'approval3_to')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection == 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" />
                                        </svg>
                                    @endif
                                </a>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($pRequest as $pr)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $pr->pr_date ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $pr->delivery_date ?? '-' }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $pr->idreqform ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $pr->company->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $pr->reqlevel ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                            {{ substr($pr->applicant ?? '?', 0, 1) }}
                                        </div>
                                        <div class="ml-2">
                                            {{ $pr->applicant ?? '-' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm">
                                    @php
                                        $statusClass = '';
                                        $statusText = $pr->approvalstat ?? 'Draft';

                                        switch ($statusText) {
                                            case 'Draft':
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                                break;
                                            case 'Price Updated':
                                            case 'PR Printed':
                                            case 'Waiting Quotation':
                                                $statusClass = 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'Quotation Submitted':
                                            case 'Waiting Approval 1':
                                            case 'HQ 1 Approved':
                                            case 'HQ 2 Approved':
                                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                                break;
                                            case 'HQ 1 Reviewed':
                                            case 'HQ 2 Approved':
                                            case 'HQ 3 Approved':
                                                $statusClass = 'bg-green-100 text-green-800';
                                                break;
                                            case 'HQ 1 Denied':
                                            case 'HQ 2 Denied':
                                            case 'HQ 3 Denied':
                                            case 'Canceled':
                                                $statusClass = 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                $statusClass = 'bg-gray-100 text-gray-800';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $pr->approvelSiteTo->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $pr->reviewHqTo->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button
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
                                    <div class="flex flex-col items-center justify-center py-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No purchase
                                            requests found</h3>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by
                                            creating a new purchase request.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination and Items Per Page -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="px-6 py-4">
                    {{ $pRequest->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', (document) => ({
                modalOpenDetail: false,

            }));
        });
    </script>
</x-app-layout>
