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

                <div>
                    <label for="pack" class="mb-2 block text-sm font-medium text-gray-900">Pack</label>
                    <input
                        type="text"
                        id="pack"
                        name="pack"
                        value="{{ old("pack", $product->pack) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("pack")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="mb-2 block text-sm font-medium text-gray-900">Price</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        value="{{ old("price", $product->price) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("price")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="rounded-full bg-gray-900 px-12 py-3 font-medium text-white transition-colors hover:bg-gray-800"
                >
                    Update
                </button>
            </form>
        </div>
    </div>
@endsection
