@extends("layouts.dashboard")

@section("title", "Edit MRP Week $week")

@section("content")
    <div class="space-y-6">
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm">
            <a
                href="{{ route("employee.production.mrp.index") }}"
                class="text-gray-500 transition-colors hover:text-gray-900"
            >
                Material Requirements Planning
            </a>
            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
            <a
                href="{{ route("employee.production.mrp.overview", $product) }}"
                class="text-gray-500 transition-colors hover:text-gray-900"
            >
                {{ $product->name }}
            </a>
            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />

            @if ($level == "0")
                <a
                    href="{{ route("employee.production.mrp.level0", [$product, $entity]) }}"
                    class="text-gray-500 transition-colors hover:text-gray-900"
                >
                    Level 0 - {{ $entityName }}
                </a>
            @elseif ($level == "1")
                <a
                    href="{{ route("employee.production.mrp.level1", $product) }}"
                    class="text-gray-500 transition-colors hover:text-gray-900"
                >
                    Level 1 - {{ $entityName }}
                </a>
            @else
                <a
                    href="{{ route("employee.production.mrp.level2", [$product, $entity]) }}"
                    class="text-gray-500 transition-colors hover:text-gray-900"
                >
                    Level 2 - {{ $entityName }}
                </a>
            @endif

            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
            <span class="font-medium text-gray-900">Edit Week {{ $week }}</span>
        </div>

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit MRP: {{ $entityName }} - Week {{ $week }}</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Edit Form -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route("employee.production.mrp.store") }}" class="space-y-6">
                @csrf

                <input type="hidden" name="level" value="{{ $level }}" />
                <input type="hidden" name="year" value="{{ $year }}" />
                <input type="hidden" name="week" value="{{ $week }}" />

                @if ($level == "0")
                    <input type="hidden" name="product_variant_id" value="{{ $entityId }}" />
                @elseif ($level == "1")
                    <input type="hidden" name="component_id" value="{{ $entityId }}" />
                    @if (isset($variantId) && $variantId)
                        {{-- Variant-specific Level 1 component --}}
                        <input type="hidden" name="product_variant_id" value="{{ $variantId }}" />
                    @else
                        {{-- Product-level Level 1 component --}}
                        <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    @endif
                @else
                    <input type="hidden" name="component_id" value="{{ $entityId }}" />
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                @endif

                <!-- Info Section -->
                <div class="rounded-lg bg-gray-50 p-4">
                    <h3 class="font-medium text-gray-900">MRP Information</h3>
                    <div class="mt-2 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Level:</span>
                            <span class="ml-2 font-medium text-gray-900">
                                @if ($level == "0")
                                    Level 0 - Product Variant
                                @elseif ($level == "1")
                                    Level 1 - Product Aggregate
                                @else
                                    Level 2 - Component
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Entity:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $entityName }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Month:</span>
                            <span class="ml-2 font-medium text-gray-900">
                                {{ \Carbon\Carbon::create($year, $month)->format("F Y") }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Week:</span>
                            <span class="ml-2 font-medium text-gray-900">{{ $week }}</span>
                        </div>
                    </div>
                </div>

                @if ($week == 0)
                    <!-- Initial Projected On Hand for Week 0 -->
                    <div>
                        <label for="projected_on_hand" class="block text-sm font-medium text-gray-700">
                            Initial Projected On Hand
                        </label>
                        <p class="mt-1 text-xs text-gray-500">
                            This is the starting inventory value for this MRP level.
                        </p>
                        <input
                            type="number"
                            id="projected_on_hand"
                            name="projected_on_hand"
                            value="{{ old("projected_on_hand", $mrpRecord?->projected_on_hand ?? 0) }}"
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-butter-400 focus:ring focus:ring-butter-200 focus:ring-opacity-50"
                        />
                        @error("projected_on_hand")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <!-- Scheduled Receipts -->
                    <div>
                        <label for="scheduled_receipts" class="block text-sm font-medium text-gray-700">
                            Scheduled Receipts
                        </label>
                        <p class="mt-1 text-xs text-gray-500">
                            Orders expected to be received this week (confirmed orders in transit).
                        </p>
                        <input
                            type="number"
                            id="scheduled_receipts"
                            name="scheduled_receipts"
                            min="0"
                            value="{{ old("scheduled_receipts", $mrpRecord?->scheduled_receipts ?? 0) }}"
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-butter-400 focus:ring focus:ring-butter-200 focus:ring-opacity-50"
                        />
                        @error("scheduled_receipts")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Planned Order Receipts -->
                    <div>
                        <label for="planned_order_receipts" class="block text-sm font-medium text-gray-700">
                            Planned Order Receipts
                        </label>
                        <p class="mt-1 text-xs text-gray-500">Planned orders to receive in this week to meet demand.</p>
                        <input
                            type="number"
                            id="planned_order_receipts"
                            name="planned_order_receipts"
                            min="0"
                            value="{{ old("planned_order_receipts", $mrpRecord?->planned_order_receipts ?? 0) }}"
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-butter-400 focus:ring focus:ring-butter-200 focus:ring-opacity-50"
                        />
                        @error("planned_order_receipts")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Planned Order Releases -->
                    <div>
                        <label for="planned_order_releases" class="block text-sm font-medium text-gray-700">
                            Planned Order Releases
                        </label>
                        <p class="mt-1 text-xs text-gray-500">
                            Orders to release this week (accounting for lead time).
                        </p>
                        <input
                            type="number"
                            id="planned_order_releases"
                            name="planned_order_releases"
                            min="0"
                            value="{{ old("planned_order_releases", $mrpRecord?->planned_order_releases ?? 0) }}"
                            class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-butter-400 focus:ring focus:ring-butter-200 focus:ring-opacity-50"
                        />
                        @error("planned_order_releases")
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 border-t border-gray-200 pt-4">
                    <a
                        href="{{ $level == "0" ? route("employee.production.mrp.level0", [$product, $entity]) : ($level == "1" ? route("employee.production.mrp.level1", $product) : route("employee.production.mrp.level2", [$product, $entity])) }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                    >
                        <x-heroicon-o-check class="h-4 w-4" />
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
