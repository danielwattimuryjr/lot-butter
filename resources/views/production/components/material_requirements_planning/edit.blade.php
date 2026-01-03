@extends("layouts.dashboard")

@section("title", "Edit Component MRP")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Material Requirements Planning</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Info Section -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <span class="text-sm font-medium text-gray-600">Component Name</span>
                    <p class="mt-1 text-sm font-semibold text-orange-500">{{ $component->name }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Week</span>
                    <p class="mt-1 text-sm font-semibold text-orange-500">{{ $materialRequirementsPlanning->week }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-600">Month</span>
                    <p class="mt-1 text-sm font-semibold text-orange-500">
                        {{ \Carbon\Carbon::create(null, $materialRequirementsPlanning->month)->format("F") }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ route("employee.production.components.material-requirements-planning.update", [$component, $materialRequirementsPlanning]) }}"
                class="space-y-6"
            >
                @csrf
                @method("PUT")

                <!-- Read-only fields for context -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-900">Gross Requirements</label>
                        <input
                            type="text"
                            value="{{ $materialRequirementsPlanning->gross_requirements ?? "-" }}"
                            class="w-full rounded-lg border-0 bg-gray-50 px-4 py-3 text-gray-500"
                            disabled
                            readonly
                        />
                        <p class="mt-1 text-xs text-gray-500">This value is calculated automatically</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-900">Net Requirements</label>
                        <input
                            type="text"
                            value="{{ $materialRequirementsPlanning->net_requirements ?? "-" }}"
                            class="w-full rounded-lg border-0 bg-gray-50 px-4 py-3 text-gray-500"
                            disabled
                            readonly
                        />
                        <p class="mt-1 text-xs text-gray-500">This value is calculated automatically</p>
                    </div>
                </div>

                <!-- Editable fields -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="schedule_receipts" class="mb-2 block text-sm font-medium text-gray-900">
                            Schedule Receipts
                        </label>
                        <input
                            type="number"
                            id="schedule_receipts"
                            name="schedule_receipts"
                            value="{{ old("schedule_receipts", $materialRequirementsPlanning->schedule_receipts) }}"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-orange-400"
                        />
                        @error("schedule_receipts")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="projected_on_hand" class="mb-2 block text-sm font-medium text-gray-900">
                            Projected On Hand
                        </label>
                        <input
                            type="number"
                            id="projected_on_hand"
                            name="projected_on_hand"
                            value="{{ old("projected_on_hand", $materialRequirementsPlanning->projected_on_hand) }}"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-orange-400"
                        />
                        @error("projected_on_hand")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="planned_order_receipts" class="mb-2 block text-sm font-medium text-gray-900">
                            Planned Order Receipts
                        </label>
                        <input
                            type="number"
                            id="planned_order_receipts"
                            name="planned_order_receipts"
                            value="{{ old("planned_order_receipts", $materialRequirementsPlanning->planned_order_receipts) }}"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-orange-400"
                        />
                        @error("planned_order_receipts")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <p class="mt-1 text-xs text-orange-600">Editing this will recalculate subsequent weeks</p>
                    </div>

                    <div>
                        <label for="planned_order_releases" class="mb-2 block text-sm font-medium text-gray-900">
                            Planned Order Releases
                        </label>
                        <input
                            type="number"
                            id="planned_order_releases"
                            name="planned_order_releases"
                            value="{{ old("planned_order_releases", $materialRequirementsPlanning->planned_order_releases) }}"
                            class="w-full rounded-lg border-0 bg-gray-100 px-4 py-3 outline-none transition-all focus:bg-white focus:ring-2 focus:ring-orange-400"
                        />
                        @error("planned_order_releases")
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-4">
                    <button
                        type="submit"
                        class="rounded-full bg-gray-900 px-12 py-3 font-medium text-white transition-colors hover:bg-gray-800"
                    >
                        Update
                    </button>
                    <a
                        href="{{ route("employee.production.components.material-requirements-planning.index", $component) }}"
                        class="rounded-full border-2 border-gray-300 px-12 py-3 font-medium text-gray-700 transition-colors hover:border-gray-400"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
