@extends("layouts.dashboard")

@section("title", "Add Components to BOM")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Add Components to BOM</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Info Card -->
        <div class="rounded-xl border border-gray-100 bg-blue-50 p-4">
            <p class="text-sm text-gray-700">
                <strong>Adding to:</strong>
                {{ $product->name }}

                @if ($selectedVariant)
                    - {{ $selectedVariant->name }} ({{ $selectedVariant->number }})
                @else
                    (Product-level BOM)
                @endif
            </p>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ route("employee.production.bill-of-materials.store", ["product" => $product->id, "variant_id" => request("variant_id")]) }}"
                class="space-y-6"
            >
                @csrf

                <!-- Components Section -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Components</h2>
                        <button
                            type="button"
                            onclick="addComponent()"
                            class="rounded-full bg-butter-400 px-6 py-2 text-sm font-medium text-gray-900 transition-colors hover:bg-butter-500"
                        >
                            Add Component
                        </button>
                    </div>

                    <div id="components-container" class="space-y-4">
                        <!-- Components will be added here dynamically -->
                    </div>
                </div>

                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-12 py-3 font-medium text-white transition-colors hover:bg-gray-800"
                >
                    Add to BOM
                </button>
            </form>
        </div>
    </div>

    <script>
        let componentCount = 0;
        const availableComponents = @json($components);

        function addComponent() {
            const container = document.getElementById('components-container');
            const componentDiv = document.createElement('div');
            componentDiv.className = 'rounded-lg border border-gray-200 bg-gray-50 p-4 space-y-4';
            componentDiv.id = `component-${componentCount}`;

            componentDiv.innerHTML = `
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900">Component ${componentCount + 1}</h3>
                    <button
                        type="button"
                        onclick="removeComponent(${componentCount})"
                        class="text-red-500 hover:text-red-700 font-medium text-sm"
                    >
                        Remove
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="components[${componentCount}][component_id]" class="mb-2 block text-sm font-medium text-gray-900">Component</label>
                        <select
                            id="components[${componentCount}][component_id]"
                            name="components[${componentCount}][component_id]"
                            class="w-full rounded-lg border-0 bg-white px-4 py-3 outline-none transition-all focus:ring-2 focus:ring-butter-400"
                            required
                        >
                            <option value="">-- SELECT COMPONENT --</option>
                            ${availableComponents
                                .map(
                                    (c) => `
                                <option value="${c.id}">${c.name} ${c.unit ? '(' + c.unit + ')' : ''}</option>
                            `,
                                )
                                .join('')}
                        </select>
                    </div>

                    <div>
                        <label for="components[${componentCount}][quantity]" class="mb-2 block text-sm font-medium text-gray-900">Quantity</label>
                        <input
                            type="number"
                            step="0.0001"
                            id="components[${componentCount}][quantity]"
                            name="components[${componentCount}][quantity]"
                            class="w-full rounded-lg border-0 bg-white px-4 py-3 outline-none transition-all focus:ring-2 focus:ring-butter-400"
                            required
                        />
                    </div>

                    <div>
                        <label for="components[${componentCount}][level]" class="mb-2 block text-sm font-medium text-gray-900">Level</label>
                        <select
                            id="components[${componentCount}][level]"
                            name="components[${componentCount}][level]"
                            class="w-full rounded-lg border-0 bg-white px-4 py-3 outline-none transition-all focus:ring-2 focus:ring-butter-400"
                            required
                        >
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
            `;

            container.appendChild(componentDiv);
            componentCount++;
        }

        function removeComponent(id) {
            const componentDiv = document.getElementById(`component-${id}`);
            if (componentDiv) {
                componentDiv.remove();
            }
        }

        // Add one component by default
        document.addEventListener('DOMContentLoaded', function () {
            addComponent();
        });
    </script>
@endsection
