@extends("layouts.dashboard")

@section("title", "Edit MPS Week $week")

@section("content")
    <div class="space-y-6">
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm">
            <a
                href="{{ route("employee.production.master-production-schedules.index") }}"
                class="text-gray-500 transition-colors hover:text-gray-900"
            >
                Master Production Schedule
            </a>
            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
            <a
                href="{{ route("employee.production.master-production-schedules.show", $product) }}"
                class="text-gray-500 transition-colors hover:text-gray-900"
            >
                {{ $product->name }}
            </a>
            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
            <a
                href="{{ route("employee.production.master-production-schedules.variant", [$product, $variant]) }}"
                class="text-gray-500 transition-colors hover:text-gray-900"
            >
                {{ $variant->name }}
            </a>
            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
            <span class="font-medium text-gray-900">Edit Week {{ $week }}</span>
        </div>

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">
                Edit MPS: {{ $product->name }} ({{ $variant->name }}) - Week {{ $week }}
            </h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Edit Form -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form
                method="POST"
                action="{{ route("employee.production.master-production-schedules.store-weekly") }}"
                class="space-y-6"
            >
                @csrf

                <input type="hidden" name="product_variant_id" value="{{ $variant->id }}" />
                <input type="hidden" name="year" value="{{ $year }}" />
                <input type="hidden" name="month" value="{{ $month }}" />
                <input type="hidden" name="week" value="{{ $week }}" />

                <!-- Week Info -->
                <div class="rounded-lg bg-gray-50 p-4">
                    <h3 class="font-medium text-gray-900">Week Information</h3>
                    <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Product:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $product->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Variant:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $variant->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Year:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $year }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Week:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $week }}</span>
                        </div>
                    </div>
                </div>

                @if ($week == 0)
                    <!-- Beginning Inventory for Week 0 -->
                    <div>
                        <label for="beginning_inventory" class="block text-sm font-medium text-gray-900">
                            Beginning Inventory
                        </label>
                        <input
                            type="number"
                            id="beginning_inventory"
                            name="beginning_inventory"
                            value="{{ $mpsRecord->beginning_inventory ?? 0 }}"
                            min="0"
                            class="mt-2 w-full rounded-lg border-gray-300 focus:border-butter-500 focus:ring-butter-500"
                            required
                        />
                        <p class="mt-1 text-sm text-gray-500">Starting inventory for the month</p>
                    </div>
                @else
                    <!-- Projected On Hand -->
                    <div>
                        <label for="projected_on_hand" class="block text-sm font-medium text-gray-900">
                            Projected On Hand
                        </label>
                        <input
                            type="number"
                            id="projected_on_hand"
                            name="projected_on_hand"
                            value="{{ $mpsRecord->projected_on_hand ?? 0 }}"
                            class="mt-2 w-full rounded-lg border-gray-300 focus:border-butter-500 focus:ring-butter-500"
                        />
                        <p class="mt-1 text-sm text-gray-500">Override the calculated projected on hand value</p>
                    </div>

                    <!-- Available -->
                    <div>
                        <label for="available" class="block text-sm font-medium text-gray-900">Available</label>
                        <input
                            type="number"
                            id="available"
                            name="available"
                            value="{{ $mpsRecord->available ?? 0 }}"
                            class="mt-2 w-full rounded-lg border-gray-300 focus:border-butter-500 focus:ring-butter-500"
                        />
                        <p class="mt-1 text-sm text-gray-500">Override the calculated available value</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                    >
                        <x-heroicon-o-check class="h-5 w-5" />
                        Save Changes
                    </button>

                    <a
                        href="{{ route("employee.production.master-production-schedules.variant", [$product, $variant]) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                    >
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
