@extends("layouts.dashboard")

@section("title", "Create Component")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Create Component</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("employee.production.components.store") }}" class="space-y-6">
                @csrf

                <div>
                    <label for="code" class="mb-2 block text-sm font-medium text-gray-900">Item Code</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old("code") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("code")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-900">Component Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old("name") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("name")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight" class="mb-2 block text-sm font-medium text-gray-900">Weight</label>
                    <input
                        type="number"
                        id="weight"
                        name="weight"
                        value="{{ old("weight") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("weight")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="unit" class="mb-2 block text-sm font-medium text-gray-900">Unit</label>
                    <input
                        type="text"
                        id="unit"
                        name="unit"
                        value="{{ old("unit") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("unit")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="mb-2 block text-sm font-medium text-gray-900">Category</label>
                    <input
                        type="text"
                        id="category"
                        name="category"
                        list="category-list"
                        value="{{ old("category") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        autocomplete="off"
                        required
                    />
                    <datalist id="category-list">
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </datalist>
                    @error("category")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="safety_stock" class="mb-2 block text-sm font-medium text-gray-900">Safety Stock</label>
                    <input
                        type="number"
                        id="safety_stock"
                        name="safety_stock"
                        value="{{ old("safety_stock") }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("safety_stock")
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
