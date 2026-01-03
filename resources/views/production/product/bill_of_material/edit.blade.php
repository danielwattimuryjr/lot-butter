@extends("layouts.dashboard")

@section("title", "Edit Component from BOM")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Component from BOM</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ route("employee.production.products.bill-of-materials.update", [$product, $bom->component_id]) }}"
                class="space-y-6"
            >
                @csrf
                @method("PUT")

                <div>
                    <label for="component" class="mb-2 block text-sm font-medium text-gray-900">Component Name</label>
                    <select
                        name="component_id"
                        id="component"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    >
                        <option selected disabled>-- SELECT COMPONENT --</option>
                        @foreach ($components as $component)
                            <option
                                value="{{ $component->id }}"
                                {{ old("component_id", $bom->component_id) == $component->id ? "selected" : "" }}
                            >
                                {{ $component->name }}
                                @if ($component->unit)
                                    {{ "(" . $component->unit . ")" }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error("component_id")
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">Quantity</label>
                    <input
                        id="quantity"
                        name="quantity"
                        value="{{ old("quantity", $bom->quantity) }}"
                        class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-butter-400"
                        required
                    />
                    @error("quantity")
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
