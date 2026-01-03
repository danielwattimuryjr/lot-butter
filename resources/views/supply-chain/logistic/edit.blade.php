@extends("layouts.dashboard")

@section("title", "Edit Logistic Report")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Logistic Report</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ route("employee.supply-chain.logistics.update", $logistic) }}"
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
                                {{ old("component_id", $logistic->component_id) == $component->id ? "selected" : "" }}
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
                    <label for="transaction_type" class="mb-2 block text-sm font-medium text-gray-900">
                        Transaction Type
                    </label>
                    <div class="flex gap-4">
                        <label class="flex cursor-pointer items-center">
                            <input
                                type="radio"
                                name="transaction_type"
                                value="in"
                                {{ old("transaction_type", $logistic->transaction_type) == "in" ? "checked" : "" }}
                                class="mr-2"
                                required
                            />
                            <span class="font-medium text-green-600">Stock In</span>
                        </label>
                        <label class="flex cursor-pointer items-center">
                            <input
                                type="radio"
                                name="transaction_type"
                                value="out"
                                {{ old("transaction_type", $logistic->transaction_type) == "out" ? "checked" : "" }}
                                class="mr-2"
                                required
                            />
                            <span class="font-medium text-red-600">Stock Out</span>
                        </label>
                    </div>
                    @error("transaction_type")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">Quantity</label>
                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        value="{{ old("quantity", $logistic->quantity) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("quantity")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date" class="mb-2 block text-sm font-medium text-gray-900">Date</label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old("date", $logistic->date->format("Y-m-d")) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("date")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="mb-2 block text-sm font-medium text-gray-900">Notes</label>
                    <textarea
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        name="notes"
                        id="notes"
                    >
{{ old("notes", $logistic->notes) }}</textarea
                    >
                    @error("notes")
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
