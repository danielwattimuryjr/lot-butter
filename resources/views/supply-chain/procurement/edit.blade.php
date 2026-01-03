@extends("layouts.dashboard")

@section("title", "Edit Purchase")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Purchase</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ route("employee.supply-chain.procurements.update", $purchase) }}"
                class="space-y-6"
            >
                @csrf
                @method("PUT")

                <div>
                    <label for="component" class="mb-2 block text-sm font-medium text-gray-900">Component</label>
                    <select
                        name="component_id"
                        id="component"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    >
                        <option selected disabled>-- SELECT COMPONENT REFERENCE --</option>
                        @foreach ($components as $component)
                            <option
                                value="{{ $component->id }}"
                                {{ old("component_id", $purchase->component_id) == $component->id ? "selected" : "" }}
                            >
                                {{ $component->name }}
                            </option>
                        @endforeach
                    </select>
                    @error("component_id")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-900">Description</label>
                    <textarea
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        name="description"
                        id="description"
                        required
                    >
{{ old("description", $purchase->description) }}</textarea
                    >
                    @error("description")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">Quantity</label>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        value="{{ old("quantity", $purchase->quantity) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("quantity")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit_price" class="mb-2 block text-sm font-medium text-gray-900">Unit Price</label>
                    <input
                        type="number"
                        id="unit_price"
                        name="unit_price"
                        value="{{ old("unit_price", $purchase->unit_price) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("unit_price")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="mb-2 block text-sm font-medium text-gray-900">Purchase Date</label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old("date", $purchase->date->format("Y-m-d")) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("date")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supplier" class="mb-2 block text-sm font-medium text-gray-900">Supplier</label>
                    <input
                        id="supplier"
                        name="supplier"
                        value="{{ old("supplier", $purchase->supplier) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("supplier")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-12 py-3 font-medium text-white transition-colors hover:bg-gray-800"
                >
                    Create
                </button>
            </form>
        </div>
    </div>
@endsection
