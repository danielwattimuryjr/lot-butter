@extends("layouts.dashboard")

@section("title", "Products")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Products</h1>
            <p class="mt-1 text-sm text-gray-600">Manage products and their variants</p>
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

                <a
                    href="{{ route("employee.production.products.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                >
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add New Product
                </a>
            </div>
        @endif

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <x-table-controls />

            <!-- Table -->
            <div class="mt-4 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Product Name</th>
                            @if (auth()->user()->team->name == "Production")
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            onclick="toggleVariants(event, {{ $product->id }})"
                                            class="text-butter-500 transition-colors hover:text-butter-600"
                                        >
                                            <x-heroicon-o-chevron-down
                                                class="h-5 w-5 transition-transform"
                                                id="icon-{{ $product->id }}"
                                            />
                                        </button>
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-butter-100"
                                        >
                                            <x-heroicon-o-cube class="h-5 w-5 text-butter-600" />
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $product->code }}</div>
                                        </div>
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
                                                onsubmit="
                                                    return confirm(
                                                        'Are you sure you want to delete this product and all its variants?',
                                                    );
                                                "
                                            >
                                                @csrf
                                                @method("DELETE")

                                                <button
                                                    type="submit"
                                                    class="text-orange-400 transition-colors hover:text-red-600"
                                                    title="Delete"
                                                >
                                                    <x-heroicon-o-trash class="h-5 w-5" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                            <tr id="variants-{{ $product->id }}" class="hidden bg-gray-50">
                                <td
                                    colspan="@if(auth()->user()->team->name == 'Production') 4 @else 3 @endif"
                                    class="px-4 py-4"
                                >
                                    <div class="ml-14">
                                        <h4 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                            <x-heroicon-o-tag class="h-4 w-4" />
                                            Product Variants
                                        </h4>
                                        @if ($product->variants->count() > 0)
                                            <div class="rounded-lg border border-gray-200 bg-white">
                                                <table class="w-full">
                                                    <thead class="bg-gray-50">
                                                        <tr class="border-b border-gray-200">
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-600"
                                                            >
                                                                Variant Name
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-center text-xs font-medium text-gray-600"
                                                            >
                                                                Number
                                                            </th>
                                                            <th
                                                                class="px-4 py-2 text-right text-xs font-medium text-gray-600"
                                                            >
                                                                Price
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($product->variants as $variant)
                                                            <tr class="border-b border-gray-100 last:border-0">
                                                                <td
                                                                    class="px-4 py-2.5 text-sm font-medium text-gray-900"
                                                                >
                                                                    {{ $variant->name }}
                                                                </td>
                                                                <td class="px-4 py-2.5 text-center text-sm">
                                                                    <span
                                                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700"
                                                                    >
                                                                        {{ $variant->number }}
                                                                    </span>
                                                                </td>
                                                                <td
                                                                    class="px-4 py-2.5 text-right text-sm font-semibold text-gray-900"
                                                                >
                                                                    Rp{{ number_format($variant->price, 0, ",", ".") }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div
                                                class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-3"
                                            >
                                                <x-heroicon-o-information-circle class="h-5 w-5 text-gray-400" />
                                                <p class="text-sm text-gray-500">
                                                    No variants available for this product.
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100">
                                <td
                                    colspan="@if(auth()->user()->team->name == 'Production') 4 @else 3 @endif"
                                    class="px-4 py-12 text-center"
                                >
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-100 p-4">
                                            <x-heroicon-o-cube class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <p class="mt-4 text-sm font-medium text-gray-900">No products found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Start managing your product catalog by adding your first product.
                                        </p>
                                        @if (auth()->user()->team->name == "Production")
                                            <a
                                                href="{{ route("employee.production.products.create") }}"
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-butter-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                                            >
                                                <x-heroicon-o-plus class="h-4 w-4" />
                                                Add First Product
                                            </a>
                                        @endif
                                    </div>
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
