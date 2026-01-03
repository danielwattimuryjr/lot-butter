@extends("layouts.dashboard")

@section("title", "$product->name - Master Production Schedule")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">
                {{ $product->name }} ({{ $product->pack }}) - Master Production Schedule
            </h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Production")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("employee.production.products.master-production-schedule.export", ["product" => $product, "format" => "csv", "month" => request("month", now()->month), "year" => request("year", now()->year)]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("employee.production.products.master-production-schedule.export", ["product" => $product, "format" => "pdf", "month" => request("month", now()->month), "year" => request("year", now()->year)]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>
            </div>
        @endif

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Information
                            </th>
                            @foreach ($monthlyData as $week => $data)
                                <th
                                    class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600"
                                >
                                    @if ($week === 0)
                                        First Stock
                                    @else
                                        {{ \Carbon\Carbon::now()->format("F") }}
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Week --}}
                        <tr class="bg-butter-100">
                            <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">Week</td>
                            @foreach ($monthlyData as $week => $data)
                                <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">
                                    {{ $week }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Forecasting --}}
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">Forecasting</td>
                            @foreach ($monthlyData as $data)
                                <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">
                                    {{ $data["forecasting"] }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- MPS --}}
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">MPS</td>
                            @foreach ($monthlyData as $data)
                                <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">
                                    {{ $data["mps"] }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Available --}}
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">Available</td>
                            @foreach ($monthlyData as $data)
                                <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">
                                    {{ $data["available"] }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Projected on Hand --}}
                        <tr>
                            <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">Projected On Hand</td>
                            @foreach ($monthlyData as $data)
                                <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">
                                    {{ $data["projected_on_hand"] ?? 0 }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Actions --}}
                        @if (auth()->user()->team->name == "Production")
                            <tr>
                                <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">Actions</td>
                                @foreach ($monthlyData as $week => $data)
                                    <td class="border border-gray-200 px-4 py-4 text-sm text-gray-700">
                                        <a
                                            href="{{ route("employee.production.products.master-production-schedule.edit", [$product, $data["mps_id"]]) }}"
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
