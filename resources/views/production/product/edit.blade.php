@extends("layouts.dashboard")

@section("title", "Edit Product")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Product</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ route("employee.production.products.update", $product) }}"
                class="space-y-6"
            >
                @csrf
                @method("PUT")

                <!-- Product Information -->
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900">Product Information</h2>
                    
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Product Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old("name", $product->name) }}"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                            required
                        />
                        @error("name")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="space-y-6 border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Product Variants</h2>
                        <button
                            type="button"
                            onclick="addVariant()"
                            class="rounded-full bg-butter-400 px-6 py-2 text-sm font-medium text-gray-900 transition-colors hover:bg-butter-500"
                        >
                            Add Variant
                        </button>
                    </div>

                    <div id="variants-container" class="space-y-4">
                        <!-- Existing variants will be loaded here -->
                    </div>
                </div>

                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-12 py-3 font-medium text-white transition-colors hover:bg-gray-800"
                >
                    Update Product
                </button>
            </form>
        </div>
    </div>

    <script>
        let variantCount = 0;
        const existingVariants = @json(old('variants', $product->variants->toArray()));

        function addVariant(variant = null) {
            const container = document.getElementById('variants-container');
            const variantDiv = document.createElement('div');
            variantDiv.className = 'rounded-lg border border-gray-200 bg-gray-50 p-4 space-y-4';
            variantDiv.id = `variant-${variantCount}`;
            
            const variantId = variant?.id || '';
            const variantName = variant?.name || '';
            const variantNumber = variant?.number || '';
            const variantPrice = variant?.price || '';
            
            variantDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900">Variant ${variantCount + 1}</h3>
                    <button
                        type="button"
                        onclick="removeVariant(${variantCount})"
                        class="text-red-500 hover:text-red-700 font-medium text-sm"
                    >
                        Remove
                    </button>
                </div>

                ${variantId ? `<input type="hidden" name="variants[${variantCount}][id]" value="${variantId}" />` : ''}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="variants[${variantCount}][name]" class="mb-2 block text-sm font-medium text-gray-900">Variant Name</label>
                        <input
                            type="text"
                            id="variants[${variantCount}][name]"
                            name="variants[${variantCount}][name]"
                            value="${variantName}"
                            class="w-full rounded-lg border-0 bg-white px-4 py-3 outline-none transition-all focus:ring-2 focus:ring-butter-400"
                            required
                        />
                    </div>

                    <div>
                        <label for="variants[${variantCount}][number]" class="mb-2 block text-sm font-medium text-gray-900">Number</label>
                        <input
                            type="number"
                            id="variants[${variantCount}][number]"
                            name="variants[${variantCount}][number]"
                            value="${variantNumber}"
                            class="w-full rounded-lg border-0 bg-white px-4 py-3 outline-none transition-all focus:ring-2 focus:ring-butter-400"
                            required
                        />
                    </div>

                    <div>
                        <label for="variants[${variantCount}][price]" class="mb-2 block text-sm font-medium text-gray-900">Price</label>
                        <input
                            type="number"
                            step="0.01"
                            id="variants[${variantCount}][price]"
                            name="variants[${variantCount}][price]"
                            value="${variantPrice}"
                            class="w-full rounded-lg border-0 bg-white px-4 py-3 outline-none transition-all focus:ring-2 focus:ring-butter-400"
                            required
                        />
                    </div>
                </div>
            `;
            
            container.appendChild(variantDiv);
            variantCount++;
        }

        function removeVariant(id) {
            const variantDiv = document.getElementById(`variant-${id}`);
            if (variantDiv) {
                variantDiv.remove();
            }
        }

        // Load existing variants
        document.addEventListener('DOMContentLoaded', function() {
            if (existingVariants && existingVariants.length > 0) {
                existingVariants.forEach(variant => {
                    addVariant(variant);
                });
            } else {
                // Add one empty variant if none exist
                addVariant();
            }
        });
    </script>
@endsection
