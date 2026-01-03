<div class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
    <!-- Show entries -->
    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-600">Show</span>
        <div class="relative">
            <select
                id="limit"
                class="appearance-none rounded-lg border border-gray-200 bg-white px-3 py-2 pr-8 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400"
            >
                <option
                    value="10"
                    {{ request("limit") == 10 ? "selected" : "" }}
                    default
                >
                    10
                </option>
                <option value="25" {{ request("limit") == 25 ? "selected" : "" }}>25</option>
                <option value="50" {{ request("limit") == 50 ? "selected" : "" }}>50</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2">
                <x-heroicon-o-chevron-down class="h-4 w-4 text-gray-400" />
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="relative w-full sm:w-64">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400" />
        </div>
        <input
            type="text"
            id="search"
            placeholder="{{ $searchPlaceholder }}"
            value="{{ request($searchParam) }}"
            class="w-full rounded-lg bg-gray-100 py-2 pl-10 pr-4 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400"
        />
    </div>
</div>

@push("scripts")
    <script>
        $(document).ready(function () {
            // Search functionality
            $('#search').on('keypress', function (e) {
                if (e.which === 13) {
                    // Enter key
                    e.preventDefault();
                    performSearch();
                }
            });

            // Limit change functionality
            $('#limit').on('change', function () {
                performSearch();
            });

            function performSearch() {
                let query = $('#search').val();
                let perPage = $('#limit').val();

                // Build URL with parameters
                let url = origin + window.location.pathname;
                let params = [];

                if (query) {
                    params.push('{{ $searchParam }}=' + encodeURIComponent(query));
                }

                if (perPage) {
                    params.push('limit=' + perPage);
                }

                if (params.length > 0) {
                    url += '?' + params.join('&');
                }

                window.location.href = url;
            }
        });
    </script>
@endpush
