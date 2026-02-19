@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-sm text-gray-700">
            {{ __('ui.showing') }} {{ $paginator->currentPage() }} {{ __('ui.of') }} {{ $paginator->lastPage() }} ({{ $paginator->total() }} {{ __('ui.entries') }})
        </div>
        <div class="flex gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-400 cursor-not-allowed">
                    {{ __('ui.previous') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">
                    {{ __('ui.previous') }}
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 py-1 text-gray-500">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if (
                            $page == 1 ||
                            $page == $paginator->lastPage() ||
                            (abs($page - $paginator->currentPage()) <= 2)
                        )
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-1 bg-blue-800 text-white rounded-md text-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">{{ $page }}</a>
                            @endif
                        @elseif (
                            $page == $paginator->currentPage() - 3 ||
                            $page == $paginator->currentPage() + 3
                        )
                            <span class="px-2 py-1 text-gray-500">...</span>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 text-sm">
                    {{ __('ui.next') }}
                </a>
            @else
                <span class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-400 cursor-not-allowed">
                    {{ __('ui.next') }}
                </span>
            @endif
        </div>
    </div>
@endif
