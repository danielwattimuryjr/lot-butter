<div class="mt-6 flex items-center justify-end gap-2">
    @if ($paginator->onFirstPage())
        <button
            disabled
            class="flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-full border border-gray-200 text-gray-300"
        >
            <x-heroicon-o-chevron-left class="h-4 w-4" />
        </button>
    @else
        <a
            href="{{ $paginator->appends(request()->except("page"))->previousPageUrl() }}"
            class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-400 transition-colors hover:bg-gray-100"
        >
            <x-heroicon-o-chevron-left class="h-4 w-4" />
        </a>
    @endif

    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        @if ($page == $paginator->currentPage())
            <button
                class="flex h-8 w-8 items-center justify-center rounded-full border border-orange-400 bg-orange-400 font-medium text-white"
            >
                {{ $page }}
            </button>
        @else
            <a
                href="{{ $paginator->appends(request()->except("page"))->url($page) }}"
                class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-700 transition-colors hover:bg-gray-100"
            >
                {{ $page }}
            </a>
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a
            href="{{ $paginator->appends(request()->except("page"))->nextPageUrl() }}"
            class="flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 text-gray-400 transition-colors hover:bg-gray-100"
        >
            <x-heroicon-o-chevron-right class="h-4 w-4" />
        </a>
    @else
        <button
            disabled
            class="flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-full border border-gray-200 text-gray-300"
        >
            <x-heroicon-o-chevron-right class="h-4 w-4" />
        </button>
    @endif
</div>
