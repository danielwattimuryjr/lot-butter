@extends("layouts.dashboard")

@section("title", "Bill of Material")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Bill of Material</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Product/Variant Selector -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Select Product or Variant</h2>
            <div class="space-y-4">
                <!-- Product Name -->
                <div>
                    <p class="text-sm font-medium text-gray-600">Product:</p>
                    <p class="text-lg font-bold text-gray-900">{{ $product->name }}</p>
                </div>

                <!-- Variant Selector -->
                <div>
                    <label for="variant-select" class="mb-2 block text-sm font-medium text-gray-900">
                        Select Variant (or use product-level BOM)
                    </label>
                    <select
                        id="variant-select"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        onchange="loadBOM()"
                    >
                        <option value="product" {{ ! request("variant_id") ? "selected" : "" }}>
                            Product-level BOM ({{ $product->name }})
                        </option>
                        @foreach ($product->variants as $variant)
                            <option
                                value="{{ $variant->id }}"
                                {{ request("variant_id") == $variant->id ? "selected" : "" }}
                            >
                                {{ $variant->name }} ({{ $variant->number }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if (auth()->user()->team->name == "Production")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <a
                    href="{{ route("employee.production.bill-of-materials.create", ["product" => $product->id, "variant_id" => request("variant_id")]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                >
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add Components to BOM
                </a>
            </div>
        @endif

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4 text-center">
                <h2 class="font-bold text-gray-900">Bill of Materials</h2>
                <p class="text-gray-700">
                    @if ($selectedVariant)
                        {{ $selectedVariant->name }} ({{ $selectedVariant->number }})
                    @else
                        {{ $product->name }} (Product-level)
                    @endif
                </p>
            </div>

            <!-- Table -->
            <div class="mt-4 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Component Name</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Quantity</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Unit</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Level</th>
                            @if (auth()->user()->team->name == "Production")
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($components as $component)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $component->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ rtrim(rtrim(number_format($component->pivot->quantity, 4, ".", ""), "0"), ".") }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $component->unit }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $component->pivot->level }}</td>
                                @if (auth()->user()->team->name == "Production")
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <a
                                                href="{{ route("employee.production.bill-of-materials.edit", ["bom" => $component->pivot->id, "product" => $product->id, "variant_id" => request("variant_id")]) }}"
                                                class="text-orange-400 transition-colors hover:text-orange-600"
                                            >
                                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route("employee.production.bill-of-materials.destroy", ["bom" => $component->pivot->id, "product" => $product->id, "variant_id" => request("variant_id")]) }}"
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
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No components found in this BOM.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function loadBOM() {
            const select = document.getElementById('variant-select');
            const variantId = select.value;
            const productId = {{ $product->id }};

            if (variantId === 'product') {
                window.location.href = `/employee/production/bill-of-materials?product=${productId}`;
            } else {
                window.location.href = `/employee/production/bill-of-materials?product=${productId}&variant_id=${variantId}`;
            }
        }
    </script>
@endsection
