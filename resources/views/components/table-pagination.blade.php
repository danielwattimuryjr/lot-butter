<div class="flex justify-end items-center gap-2 mt-6">
    @if ($paginator->onFirstPage())
        <button disabled class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-300 cursor-not-allowed">
            <x-heroicon-o-chevron-left class="w-4 h-4"/>
        </button>
    @else
        <a href="{{ $paginator->appends(request()->except('page'))->previousPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:bg-gray-100 transition-colors">
            <x-heroicon-o-chevron-left class="w-4 h-4"/>
        </a>
    @endif

    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        @if ($page == $paginator->currentPage())
            <button class="w-8 h-8 flex items-center justify-center rounded-full bg-orange-400 border border-orange-400 text-white font-medium">
                {{ $page }}
            </button>
        @else
            <a href="{{ $paginator->appends(request()->except('page'))->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-700 hover:bg-gray-100 transition-colors">
                {{ $page }}
            </a>
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->appends(request()->except('page'))->nextPageUrl() }}" class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:bg-gray-100 transition-colors">
            <x-heroicon-o-chevron-right class="w-4 h-4"/>
        </a>
    @else
        <button disabled class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-300 cursor-not-allowed">
            <x-heroicon-o-chevron-right class="w-4 h-4"/>
        </button>
    @endif
</div>