@extends("layouts.dashboard")

@section("title", "$product->name - Level $level MRP")

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
            <span class="font-medium text-gray-900">Level {{ $level }} - {{ $entityName }}</span>
        </div>

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">MRP Level {{ $level }}: {{ $entityName }}</h1>
            <p class="mt-1 text-sm text-gray-600">
                @if ($level == "0")
                    Variant-level MRP - Gross Requirements from MPS
                @elseif ($level == "1")
                    Product Aggregate - Gross Requirements = SUM(MPS from all variants)
                @else
                    Component Level - Gross Requirements = SUM(MPS all variants) × BOM Quantity
                    ({{ $bomQuantity ?? 0 }})
                @endif
            </p>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Production")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="#"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="#"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>
            </div>
        @endif

        <!-- MRP Table -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Information
                            </th>
                            <th class="border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-600">
                                First Stock
                            </th>
                            @foreach ($forecasts as $forecast)
                                <th
                                    class="border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-600"
                                >
                                    {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format("F") }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Week Row -->
                        <tr class="bg-purple-100">
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">Week</td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">0</td>
                            @foreach ($forecasts as $forecast)
                                <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                    {{ $forecast->week }}
                                </td>
                            @endforeach
                        </tr>

                        @php
                            // Calculate running values
                            $projectedOnHand = 0;
                            $weeklyData = [];

                            // Get initial POH from week 0 if exists
                            $initialPoh = $mrpRecords->get(0)?->projected_on_hand ?? $beginningInventory;
                            $projectedOnHand = $initialPoh;

                            foreach ($forecasts as $index => $forecast) {
                                $week = $forecast->week;
                                $mrpRecord = $mrpRecords->get($week);

                                // Calculate Gross Requirement based on level
                                if ($level == "0") {
                                    // Level 0: Gross Req from forecast / variant number
                                    $grossRequirement = ceil($forecast->forecast_value / ($variant->number ?? 1));
                                } elseif ($level == "1") {
                                    // Level 1 has two types:
                                    if (isset($isProductLevel) && $isProductLevel) {
                                        // Product-level component: Gross Req = forecast × BOM qty
                                        $grossRequirement = ceil($forecast->forecast_value) * ($bomQuantity ?? 1);
                                    } else {
                                        // Variant-specific component: Gross Req = (forecast / variant number) × BOM qty
                                        $grossRequirement = ceil($forecast->forecast_value / ($variant->number ?? 1)) * ($bomQuantity ?? 1);
                                    }
                                } else {
                                    // Level 2: Gross Req = forecast value × BOM quantity
                                    $grossRequirement = ceil($forecast->forecast_value) * ($bomQuantity ?? 1);
                                }

                                // Get manual inputs
                                $scheduledReceipts = $mrpRecord?->scheduled_receipts ?? 0;
                                $plannedOrderReceipts = $mrpRecord?->planned_order_receipts ?? 0;
                                $plannedOrderReleases = $mrpRecord?->planned_order_releases ?? 0;

                                // Calculate Net Requirements
                                $netRequirements = $grossRequirement - ($projectedOnHand + $scheduledReceipts);
                                $netRequirements = max(0, $netRequirements);

                                // Calculate POH
                                $newPoh = $projectedOnHand + $scheduledReceipts + $plannedOrderReceipts - $grossRequirement;

                                $weeklyData[$week] = [
                                    "gross_requirement" => $grossRequirement,
                                    "scheduled_receipts" => $scheduledReceipts,
                                    "projected_on_hand" => $newPoh,
                                    "net_requirements" => $netRequirements,
                                    "planned_order_receipts" => $plannedOrderReceipts,
                                    "planned_order_releases" => $plannedOrderReleases,
                                    "is_edited" => $mrpRecord?->is_edited ?? false,
                                ];

                                $projectedOnHand = $newPoh;
                            }
                        @endphp

                        <!-- Gross Requirement Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Gross Requirement
                            </td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @foreach ($forecasts as $forecast)
                                <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                    {{ number_format($weeklyData[$forecast->week]["gross_requirement"] ?? 0) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Scheduled Receipts Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Scheduled Receipts
                            </td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @foreach ($forecasts as $forecast)
                                <td
                                    class="@if ($weeklyData[$forecast->week]["is_edited"] ?? false) font-semibold text-orange-600 @else text-gray-700 @endif border border-gray-200 px-4 py-4 text-center text-sm"
                                >
                                    {{ number_format($weeklyData[$forecast->week]["scheduled_receipts"] ?? 0) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Projected On Hand Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Projected On Hand
                            </td>
                            <td
                                class="border border-gray-200 px-4 py-4 text-center text-sm font-semibold text-gray-900"
                            >
                                {{ number_format($initialPoh) }}
                            </td>
                            @foreach ($forecasts as $forecast)
                                <td
                                    class="{{ ($weeklyData[$forecast->week]["projected_on_hand"] ?? 0) < 0 ? "bg-red-100 font-semibold text-red-700" : "" }} @if ($weeklyData[$forecast->week]["is_edited"] ?? false) font-semibold text-orange-600 @else text-gray-700 @endif border border-gray-200 px-4 py-4 text-center text-sm"
                                >
                                    {{ number_format($weeklyData[$forecast->week]["projected_on_hand"] ?? 0) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Net Requirements Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Net Requirements
                            </td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @foreach ($forecasts as $forecast)
                                <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                    {{ number_format($weeklyData[$forecast->week]["net_requirements"] ?? 0) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Planned Order Receipts Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Planned Order Receipts
                            </td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @foreach ($forecasts as $forecast)
                                <td
                                    class="@if ($weeklyData[$forecast->week]["is_edited"] ?? false) font-semibold text-orange-600 @else text-gray-700 @endif border border-gray-200 px-4 py-4 text-center text-sm"
                                >
                                    {{ number_format($weeklyData[$forecast->week]["planned_order_receipts"] ?? 0) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Planned Order Releases Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Planned Order Releases
                            </td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @foreach ($forecasts as $forecast)
                                <td
                                    class="@if ($weeklyData[$forecast->week]["is_edited"] ?? false) font-semibold text-orange-600 @else text-gray-700 @endif border border-gray-200 px-4 py-4 text-center text-sm"
                                >
                                    {{ number_format($weeklyData[$forecast->week]["planned_order_releases"] ?? 0) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Actions Row -->
                        @if (auth()->user()->team->name == "Production")
                            <tr>
                                <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                    Actions
                                </td>
                                <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                    <a
                                        href="{{ route("employee.production.mrp.edit", [$level, $entity->id, $currentYear, 0]) }}?product_id={{ $product->id }}@if(isset($variant))&variant_id={{ $variant->id }}@endif"
                                        class="text-orange-400 transition-colors hover:text-orange-600"
                                    >
                                        Edit
                                    </a>
                                </td>
                                @foreach ($forecasts as $forecast)
                                    <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                        <a
                                            href="{{ route("employee.production.mrp.edit", [$level, $entity->id, $currentYear, $forecast->week]) }}?product_id={{ $product->id }}@if(isset($variant))&variant_id={{ $variant->id }}@endif"
                                            class="text-orange-400 transition-colors hover:text-orange-600"
                                        >
                                            Edit
                                        </a>
                                    </td>
                                @endforeach
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
