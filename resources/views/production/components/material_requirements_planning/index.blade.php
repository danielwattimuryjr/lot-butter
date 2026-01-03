@extends("layouts.dashboard")

@section("title", "$component->name - Material Requirements Planning")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $component->name }} - Material Requirements Planning</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Production")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("employee.production.components.material-requirements-planning.export", ["component" => $component, "format" => "csv", "month" => $selectedMonth, "year" => $selectedYear]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("employee.production.components.material-requirements-planning.export", ["component" => $component, "format" => "pdf", "month" => $selectedMonth, "year" => $selectedYear]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>
            </div>
        @endif

        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex flex-col gap-3">
                <!-- Component Name Display -->
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Component Name</span>
                    <span class="text-sm text-gray-500">:</span>
                    <span class="text-sm font-semibold text-orange-500">{{ $component->name }}</span>
                </div>

                <!-- Unit Display -->
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Unit</span>
                    <span class="text-sm text-gray-500">:</span>
                    <span class="text-sm font-semibold text-orange-500">{{ $component->unit }}</span>
                </div>

                <!-- Month Display -->
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-600">Month</span>
                    <span class="text-sm text-gray-500">:</span>
                    <span class="text-sm font-semibold text-orange-500" id="selected-month">
                        {{ \Carbon\Carbon::create($selectedYear, $selectedMonth)->format("F Y") }}
                    </span>
                </div>
            </div>

            <div>
                <!-- Month Selector -->
                <div class="w-full sm:w-64">
                    <label for="month-select" class="mb-2 block text-sm font-medium text-gray-700">Select Month</label>
                    <select
                        id="month-select"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-orange-500 focus:ring-orange-500"
                        onchange="
                            window.location.href =
                                '{{ route("employee.production.components.material-requirements-planning.index", $component) }}?month=' +
                                this.value.split('-')[0] +
                                '&year=' +
                                this.value.split('-')[1]
                        "
                    >
                        @forelse ($availableMonths as $monthData)
                            <option
                                value="{{ $monthData["month"] }}-{{ $monthData["year"] }}"
                                {{ $monthData["month"] == $selectedMonth && $monthData["year"] == $selectedYear ? "selected" : "" }}
                            >
                                {{ $monthData["label"] }}
                            </option>
                        @empty
                            <option value="">No data available</option>
                        @endforelse
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border border-gray-200">
                            <th
                                colspan="10"
                                class="border border-gray-200 px-4 py-3 text-left text-center text-lg font-semibold text-gray-800"
                            >
                                MRP
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Week --}}
                        <tr class="border border-gray-200">
                            <td class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Information
                            </td>
                            @foreach ($weeklyData as $week => $data)
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-center text-sm font-medium text-gray-600"
                                >
                                    @if ($week === 0)
                                        Week 0
                                        <br />
                                        [First Stock]
                                    @else
                                        Week {{ $week }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        {{-- Gross Requirements --}}
                        <tr class="border border-gray-200">
                            <td class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Gross Requirements
                            </td>
                            @foreach ($weeklyData as $week => $data)
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-center text-sm font-medium text-gray-600"
                                >
                                    {{ $data["gross_requirements"] ?? "" }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Schedule Receipts --}}
                        <tr class="border border-gray-200">
                            <td class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Schedule Receipts
                            </td>
                            @foreach ($weeklyData as $week => $data)
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-center text-sm font-medium text-gray-600"
                                >
                                    {{ $data["schedule_receipts"] ?? "" }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Projected On Hand --}}
                        <tr class="border border-gray-200">
                            <td class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Projected On Hand
                            </td>
                            @foreach ($weeklyData as $week => $data)
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-center text-sm font-medium text-gray-600"
                                >
                                    {{ $data["projected_on_hand"] ?? "" }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Net Requirements --}}
                        <tr class="border border-gray-200">
                            <td class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Net Requirements
                            </td>
                            @foreach ($weeklyData as $week => $data)
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-center text-sm font-medium text-gray-600"
                                >
                                    {{ $data["net_requirements"] ?? "" }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Planned Order Receipts --}}
                        <tr class="border border-gray-200">
                            <td class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Planned Order Receipts
                            </td>
                            @foreach ($weeklyData as $week => $data)
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-center text-sm font-medium text-gray-600"
                                >
                                    {{ $data["planned_order_receipts"] ?? "" }}
                                </td>
                            @endforeach
                        </tr>

                        {{-- Planned Order Releases --}}
                        <tr class="border border-gray-200">
                            <td class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-600">
                                Planned Order Releases
                            </td>
                            @foreach ($weeklyData as $week => $data)
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-center text-sm font-medium text-gray-600"
                                >
                                    {{ $data["planned_order_releases"] ?? "" }}
                                </td>
                            @endforeach
                        </tr>

                        @if (auth()->user()->team->name == "Production")
                            {{-- Actions --}}
                            <tr class="border border-gray-200 bg-gray-50">
                                <td
                                    class="border border-gray-200 px-4 py-3 text-left text-sm font-semibold text-gray-700"
                                >
                                    Actions
                                </td>
                                @foreach ($weeklyData as $week => $data)
                                    <td class="border border-gray-200 px-4 py-3 text-center">
                                        <a
                                            href="{{ route("employee.production.components.material-requirements-planning.edit", [$component, $data["mrp_id"]]) }}"
                                            class="inline-flex items-center gap-1 rounded-lg bg-orange-400 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-orange-500"
                                        >
                                            <x-heroicon-o-pencil class="h-3.5 w-3.5" />
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
