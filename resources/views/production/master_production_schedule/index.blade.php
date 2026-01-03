@extends("layouts.dashboard")

@section("title", "$product->name - $variant->name - MPS")

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
            <span class="font-medium text-gray-900">{{ $variant->name }}</span>
        </div>

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">
                {{ $product->name }} ({{ $variant->name }}) - Master Production Schedule
            </h1>
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

        <!-- MPS Table -->
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
                        <tr class="bg-butter-100">
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">Week</td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">0</td>
                            @foreach ($forecasts as $forecast)
                                <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                    {{ $forecast->week }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Forecasting Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Forecasting
                            </td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @foreach ($forecasts as $forecast)
                                <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                    {{ number_format(ceil($forecast->forecast_value / $variant->number)) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- MPS Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">MPS</td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @php
                                $projectedOnHand = $beginningInventory;
                            @endphp

                            @foreach ($forecasts as $forecast)
                                @php
                                    $forecastDemand = ceil($forecast->forecast_value / $variant->number);
                                    $mps = $forecastDemand - $projectedOnHand;
                                    $projectedOnHand = 0; // Reset for next iteration
                                @endphp

                                <td
                                    class="border border-gray-200 px-4 py-4 text-center text-sm font-semibold text-gray-900"
                                >
                                    {{ number_format($mps) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- Available Row -->
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm font-medium text-gray-700">
                                Available
                            </td>
                            <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">-</td>
                            @php
                                $available = $beginningInventory;
                                $projectedOnHand = $beginningInventory;
                            @endphp

                            @foreach ($forecasts as $forecast)
                                @php
                                    $forecastDemand = ceil($forecast->forecast_value / $variant->number);
                                    $mps = $forecastDemand - $projectedOnHand;
                                    $available = $available + $mps - $forecastDemand;
                                    $projectedOnHand = 0;

                                    // Check if edited value exists
                                    $editedRecord = $mpsRecords->firstWhere("week", $forecast->week);
                                    $displayAvailable = $editedRecord && $editedRecord->is_edited && ! is_null($editedRecord->available) ? $editedRecord->available : $available;
                                @endphp

                                <td
                                    class="@if ($editedRecord && $editedRecord->is_edited) font-semibold text-orange-600 @else text-gray-900 @endif border border-gray-200 px-4 py-4 text-center text-sm"
                                >
                                    {{ number_format($displayAvailable) }}
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
                                {{ number_format($beginningInventory) }}
                            </td>
                            @foreach ($forecasts as $forecast)
                                @php
                                    // Check if edited value exists
                                    $editedRecord = $mpsRecords->firstWhere("week", $forecast->week);
                                    $displayPOH = $editedRecord && $editedRecord->is_edited && ! is_null($editedRecord->projected_on_hand) ? $editedRecord->projected_on_hand : 0;
                                @endphp

                                <td
                                    class="@if ($editedRecord && $editedRecord->is_edited) font-semibold text-orange-600 @else text-gray-700 @endif border border-gray-200 px-4 py-4 text-center text-sm"
                                >
                                    {{ number_format($displayPOH) }}
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
                                        href="{{ route("employee.production.master-production-schedules.edit-week", [$product, $variant, $currentYear, 0]) }}"
                                        class="text-orange-400 transition-colors hover:text-orange-600"
                                    >
                                        Edit
                                    </a>
                                </td>
                                @foreach ($forecasts as $forecast)
                                    <td class="border border-gray-200 px-4 py-4 text-center text-sm text-gray-700">
                                        <a
                                            href="{{ route("employee.production.master-production-schedules.edit-week", [$product, $variant, $currentYear, $forecast->week]) }}"
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
