@extends("layouts.dashboard")

@section("title", "Edit Income")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Income</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("employee.finance.incomes.update", $income) }}" class="space-y-6">
                @csrf
                @method("PUT")

                <div>
                    <label for="Product" class="mb-2 block text-sm font-medium text-gray-900">Product</label>
                    <select
                        name="product_id"
                        id="product"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    >
                        <option selected disabled>-- SELECT PRODUCT REFERENCE --</option>
                        @foreach ($products as $product)
                            <option
                                value="{{ $product->id }}"
                                {{ old("product_id", $income->product_id) == $product->id ? "selected" : "" }}
                            >
                                {{ $product->name }}
                                @if ($product->pack)
                                    {{ "(" . $product->pack . ")" }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error("product_id")
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
{{ old("description", $income->description) }}</textarea
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
                        value="{{ old("quantity", $income->quantity) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("quantity")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_received" class="mb-2 block text-sm font-medium text-gray-900">
                        Date Received
                    </label>
                    <input
                        type="date"
                        id="date_received"
                        name="date_received"
                        value="{{ old("date_received", $income->date_received->format("Y-m-d")) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("date_received")
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
