@extends("layouts.dashboard")

@section("title", "Create Income")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Create Income</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Form Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("employee.finance.incomes.store") }}" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Product Selection -->
                    <div>
                        <label for="product" class="mb-2 block text-sm font-medium text-gray-900">Product *</label>
                        <select
                            name="product_id"
                            id="product"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                            required
                            onchange="loadVariants()"
                        >
                            <option value="" selected disabled>-- SELECT PRODUCT --</option>
                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    data-variants="{{ json_encode($product->variants) }}"
                                    {{ old("product_id") == $product->id ? "selected" : "" }}
                                >
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        @error("product_id")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Variant Selection -->
                    <div>
                        <label for="variant" class="mb-2 block text-sm font-medium text-gray-900">
                            Product Variant *
                        </label>
                        <select
                            name="product_variant_id"
                            id="variant"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                            required
                            onchange="updatePrice()"
                        >
                            <option value="" selected disabled>-- SELECT VARIANT --</option>
                        </select>
                        @error("product_variant_id")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-900">Description *</label>
                    <textarea
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        name="description"
                        id="description"
                        rows="3"
                        required
                        placeholder="Enter income description"
                    >
{{ old("description") }}</textarea
                    >
                    @error("description")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">Quantity *</label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="{{ old("quantity") }}"
                            min="1"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                            required
                            placeholder="0"
                            onchange="calculateAmount()"
                        />
                        @error("quantity")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit Price (Read-only) -->
                    <div>
                        <label for="unit_price" class="mb-2 block text-sm font-medium text-gray-900">Unit Price</label>
                        <input
                            type="text"
                            id="unit_price"
                            class="w-full rounded-lg border-0 bg-gray-50 px-4 py-3 text-gray-600 outline-none"
                            readonly
                            placeholder="Rp 0"
                        />
                    </div>

                    <!-- Total Amount (Read-only) -->
                    <div>
                        <label for="total_amount" class="mb-2 block text-sm font-medium text-gray-900">
                            Total Amount
                        </label>
                        <input
                            type="text"
                            id="total_amount"
                            class="w-full rounded-lg border-0 bg-gray-50 px-4 py-3 font-medium text-gray-900 outline-none"
                            readonly
                            placeholder="Rp 0"
                        />
                    </div>
                </div>

                <div>
                    <label for="date_received" class="mb-2 block text-sm font-medium text-gray-900">
                        Date Received *
                    </label>
                    <input
                        type="date"
                        id="date_received"
                        name="date_received"
                        value="{{ old("date_received", date("Y-m-d")) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("date_received")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button
                        type="submit"
                        class="rounded-lg bg-butter-500 px-8 py-3 font-medium text-white transition-colors hover:bg-butter-600"
                    >
                        Create Income
                    </button>
                    <a
                        href="{{ route("employee.finance.incomes.index") }}"
                        class="rounded-lg border-2 border-gray-300 bg-white px-8 py-3 font-medium text-gray-700 transition-colors hover:bg-gray-50"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let variants = {};
        let currentVariantPrice = 0;

        function loadVariants() {
            const productSelect = document.getElementById('product');
            const variantSelect = document.getElementById('variant');
            const selectedOption = productSelect.options[productSelect.selectedIndex];

            // Clear existing variants
            variantSelect.innerHTML = '<option value="" selected disabled>-- SELECT VARIANT --</option>';

            if (selectedOption.dataset.variants) {
                variants = JSON.parse(selectedOption.dataset.variants);

                variants.forEach(variant => {
                    const option = document.createElement('option');
                    option.value = variant.id;
                    option.textContent = `${variant.name} (${variant.number}) - Rp ${Number(variant.price).toLocaleString('id-ID')}`;
                    option.dataset.price = variant.price;
                    variantSelect.appendChild(option);
                });
            }

            // Reset price display
            document.getElementById('unit_price').value = 'Rp 0';
            document.getElementById('total_amount').value = 'Rp 0';
            currentVariantPrice = 0;
        }

        function updatePrice() {
            const variantSelect = document.getElementById('variant');
            const selectedOption = variantSelect.options[variantSelect.selectedIndex];

            if (selectedOption.dataset.price) {
                currentVariantPrice = parseFloat(selectedOption.dataset.price);
                document.getElementById('unit_price').value = `Rp ${Number(currentVariantPrice).toLocaleString('id-ID')}`;
                calculateAmount();
            }
        }

        function calculateAmount() {
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const total = quantity * currentVariantPrice;
            document.getElementById('total_amount').value = `Rp ${Number(total).toLocaleString('id-ID')}`;
        }

        // Restore variant selection if there's an old value
        @if(old('product_id'))
            window.addEventListener('DOMContentLoaded', function() {
                loadVariants();
                @if(old('product_variant_id'))
                    document.getElementById('variant').value = '{{ old("product_variant_id") }}';
                    updatePrice();
                @endif
            });
        @endif
    </script>
@endsection
