@extends("layouts.dashboard")

@section("title", "Products")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Products</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Production")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("export", ["resource" => "products", "format" => "csv"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("export", ["resource" => "products", "format" => "pdf"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>

                <!-- Add New Product Button -->
                <a
                    href="{{ route("employee.production.products.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg border-2 border-orange-400 bg-transparent px-4 py-2 text-sm font-medium text-orange-400 transition-colors hover:bg-orange-50"
                >
                    ADD NEW PRODUCT
                </a>
            </div>
        @endif

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <x-table-controls />

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Product Name</th>
                            @if (auth()->user()->team->name == "Production")
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                            @endif

                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            onclick="toggleVariants(event, {{ $product->id }})"
                                            class="text-orange-400 transition-colors hover:text-orange-600"
                                        >
                                            <x-heroicon-o-chevron-down
                                                class="h-5 w-5 transition-transform"
                                                id="icon-{{ $product->id }}"
                                            />
                                        </button>
                                        <span>{{ $product->name }}</span>
                                    </div>
                                </td>

                                @if (auth()->user()->team->name == "Production")
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <a
                                                href="{{ route("employee.production.products.edit", $product) }}"
                                                class="text-orange-400 transition-colors hover:text-orange-600"
                                            >
                                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route("employee.production.products.destroy", $product) }}"
                                                class="inline-flex items-center"
                                            >
                                                @csrf
                                                @method("DELETE")

                                                <button
                                                    type="submit"
                                                    class="text-orange-400 transition-colors hover:text-red-600"
                                                >
                                                    <x-heroicon-o-trash class="h-5 w-5" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif

                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <a
                                        href="{{ route("employee.production.products.master-production-schedule.index", [$product]) }}"
                                        class="ml-4 text-orange-400 transition-colors hover:text-orange-600"
                                    >
                                        MPS
                                    </a>
                                </td>
                            </tr>
                            <tr id="variants-{{ $product->id }}" class="hidden bg-gray-50">
                                <td
                                    colspan="@if(auth()->user()->team->name == 'Production') 4 @else 3 @endif"
                                    class="px-4 py-4"
                                >
                                    <div class="ml-8">
                                        <h4 class="mb-3 text-sm font-semibold text-gray-700">Product Variants</h4>
                                        @if ($product->variants->count() > 0)
                                            <table class="w-full">
                                                <thead class="bg-white">
                                                    <tr class="border-b border-gray-200">
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-600"
                                                        >
                                                            Variant Name
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-600"
                                                        >
                                                            Number
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-600"
                                                        >
                                                            Price
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->variants as $variant)
                                                        <tr class="border-b border-gray-200">
                                                            <td class="px-4 py-2 text-sm text-gray-600">
                                                                {{ $variant->name }}
                                                            </td>
                                                            <td class="px-4 py-2 text-sm text-gray-600">
                                                                {{ $variant->number }}
                                                            </td>
                                                            <td class="px-4 py-2 text-sm text-gray-600">
                                                                {{ "Rp" . number_format($variant->price, 2) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p class="text-sm text-gray-500">No variants available for this product.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td
                                    colspan="@if(auth()->user()->team->name == 'Production') 4 @else 3 @endif"
                                    class="px-4 py-8 text-center text-sm text-gray-500"
                                >
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-table-pagination :paginator="$products" />
        </div>
    </div>

    <script>
        function toggleVariants(event, productId) {
            event.preventDefault();
            event.stopPropagation();

            const variantsRow = document.getElementById(`variants-${productId}`);
            const icon = document.getElementById(`icon-${productId}`);

            if (variantsRow.classList.contains('hidden')) {
                variantsRow.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                variantsRow.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    </script>
@endsection
