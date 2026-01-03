@extends("layouts.dashboard")

@section("title", "Bill of Material - Select Product")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Bill of Material</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Product Selector -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Select a Product</h2>
            <p class="mb-6 text-sm text-gray-600">Choose a product to view or manage its bill of materials.</p>

            <!-- Search Form -->
            <form method="GET" action="{{ route("employee.production.bill-of-materials.index") }}" class="mb-6">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? "" }}"
                        placeholder="Search products by name..."
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 pl-10 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                    />
                    <svg
                        class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
            </form>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($products as $product)
                    <a
                        href="{{ route("employee.production.bill-of-materials.index", ["product" => $product->id]) }}"
                        class="group block rounded-lg border border-gray-200 p-6 transition-all hover:border-butter-400 hover:shadow-md"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-butter-600">
                                    {{ $product->name }}
                                </h3>
                                <p class="mt-2 text-sm text-gray-500">{{ $product->variants->count() }} variant(s)</p>
                            </div>
                            <svg
                                class="h-5 w-5 text-gray-400 transition-colors group-hover:text-butter-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                            />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No products found</h3>
                        <p class="mt-2 text-sm text-gray-500">
                            @if ($search ?? false)
                                No products match your search "{{ $search }}". Try a different search term.
                            @else
                                    Create a product first to manage its bill of materials.
                            @endif
                        </p>
                        <a
                            href="{{ route("employee.production.products.create") }}"
                            class="mt-6 inline-flex items-center rounded-lg bg-butter-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                        >
                            Create Product
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
