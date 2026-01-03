@extends("layouts.dashboard")

@section("title", "Material Requirements Planning - Select Product")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Material Requirements Planning</h1>
            <p class="mt-1 text-sm text-gray-600">Select a product to view MRP at different levels</p>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Search Bar -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search products by name..."
                    class="w-full rounded-lg border-0 bg-gray-100 py-3 pl-12 pr-4 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                    onkeyup="filterProducts()"
                />
            </div>
        </div>

        <!-- Products List -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Products</h2>
            <div id="productsList" class="space-y-3">
                @forelse ($products as $product)
                    <a
                        href="{{ route("employee.production.mrp.overview", $product) }}"
                        class="product-item block rounded-lg border border-gray-200 p-4 transition-all hover:border-butter-400 hover:bg-butter-50"
                        data-product-name="{{ strtolower($product->name) }}"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                                    <x-heroicon-o-table-cells class="h-6 w-6 text-purple-600" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $product->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $product->variants->count() }} variant(s)</p>
                                </div>
                            </div>
                            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="rounded-full bg-gray-100 p-4">
                            <x-heroicon-o-table-cells class="h-12 w-12 text-gray-400" />
                        </div>
                        <p class="mt-4 text-sm font-medium text-gray-900">No products found</p>
                        <p class="mt-1 text-sm text-gray-500">Create products first to manage their MRP.</p>
                    </div>
                @endforelse
            </div>

            <div id="noResults" class="hidden py-12 text-center">
                <p class="text-sm text-gray-500">No products match your search.</p>
            </div>
        </div>
    </div>

    @push("scripts")
        <script>
            function filterProducts() {
                const searchInput = document.getElementById('searchInput');
                const filterValue = searchInput.value.toLowerCase();
                const productItems = document.querySelectorAll('.product-item');
                const noResults = document.getElementById('noResults');
                let visibleCount = 0;

                productItems.forEach(function (item) {
                    const productName = item.getAttribute('data-product-name');
                    if (productName.includes(filterValue)) {
                        item.style.display = 'block';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCount === 0 && productItems.length > 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        </script>
    @endpush
@endsection
