@extends("layouts.dashboard")

@section("content")
    <!-- Stats Cards -->
    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <!-- Total Income Card -->
        <div class="rounded-2xl border border-gray-100 bg-white p-6">
            <p class="mb-2 text-sm text-gray-500">Total Income</p>
            <p class="mb-3 text-3xl font-bold text-gray-900">
                {{ money($totalIncome, "IDR", true)->formatForHumans() }}
            </p>
            <p class="text-xs text-gray-400">
                This month: {{ money($monthlyIncome, "IDR", true)->formatForHumans() }}
            </p>
        </div>

        <!-- Total Purchases Card -->
        <div class="rounded-2xl border border-gray-100 bg-white p-6">
            <p class="mb-2 text-sm text-gray-500">Total Purchases</p>
            <p class="mb-3 text-3xl font-bold text-gray-900">
                {{ money($totalPurchases, "IDR", true)->formatForHumans() }}
            </p>
            <p class="text-xs text-gray-400">
                This month: {{ money($monthlyPurchases, "IDR", true)->formatForHumans() }}
            </p>
        </div>

        <!-- Forecasted Demand Card -->
        <div class="rounded-2xl border border-gray-100 bg-white p-6">
            <p class="mb-2 text-sm text-gray-500">Forecasted Demand</p>
            <p class="mb-3 text-3xl font-bold text-gray-900">
                {{ number_format($totalForecastDemand) }}
            </p>
            <p class="text-xs text-gray-400">Current month</p>
        </div>

        <!-- Total Products Card -->
        <div class="rounded-2xl border border-gray-100 bg-white p-6">
            <p class="mb-2 text-sm text-gray-500">Total Products</p>
            <p class="mb-3 text-3xl font-bold text-gray-900">
                {{ number_format($totalProducts) }}
            </p>
            <p class="text-xs text-gray-400">Active products</p>
        </div>

        <!-- Low Stock Alert Card -->
        <div class="rounded-2xl border border-gray-100 bg-white p-6">
            <p class="mb-2 text-sm text-gray-500">Low Stock Components</p>
            <p class="{{ $lowStockComponents > 0 ? "text-red-600" : "text-gray-900" }} mb-3 text-3xl font-bold">
                {{ number_format($lowStockComponents) }}
            </p>
            <p class="text-xs text-gray-400">Below safety stock</p>
        </div>

        <!-- Net Profit Card -->
        <div class="rounded-2xl border border-gray-100 bg-white p-6">
            <p class="mb-2 text-sm text-gray-500">Net Profit</p>
            <p
                class="{{ $totalIncome - $totalPurchases >= 0 ? "text-green-600" : "text-red-600" }} mb-3 text-3xl font-bold"
            >
                {{ money($totalIncome - $totalPurchases, "IDR", true)->formatForHumans() }}
            </p>
            <p class="text-xs text-gray-400">Income - Purchases</p>
        </div>
    </div>
@endsection
