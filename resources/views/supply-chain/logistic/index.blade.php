@extends("layouts.dashboard")

@section("title", "Logistic")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Logistic</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Procurement")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("export", ["resource" => "logistics", "format" => "csv"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("export", ["resource" => "logistics", "format" => "pdf"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>

                <!-- Add New Product Button -->
                <a
                    href="{{ route("employee.supply-chain.logistics.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg border-2 border-orange-400 bg-transparent px-4 py-2 text-sm font-medium text-orange-400 transition-colors hover:bg-orange-50"
                >
                    ADD NEW REPORT
                </a>
            </div>
        @endif

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <x-table-controls />

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Logistic Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Component</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Safety Stock</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Stock In</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Logistic Date</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Stock Total</th>
                            @if (auth()->user()->team->name == "Procurement")
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logistics as $logistic)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($logistics->currentPage() - 1) * $logistics->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $logistic->code }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $logistic->component->name }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $logistic->component->safety_stock }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($logistic->transaction_type === "in")
                                        <span class="font-medium text-green-600">+{{ $logistic->quantity }}</span>
                                    @else
                                        <span class="font-medium text-red-600">-{{ $logistic->quantity }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $logistic->date->format("d/m/Y") }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $logistic->stock_total }}
                                </td>
                                @if (auth()->user()->team->name == "Procurement")
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <a
                                                href="{{ route("employee.supply-chain.logistics.edit", $logistic) }}"
                                                class="text-orange-400 transition-colors hover:text-orange-600"
                                            >
                                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route("employee.supply-chain.logistics.destroy", $logistic) }}"
                                                class="inline-flex items-center"
                                            >
                                                @csrf
                                                @method("DELETE")

                                                <button
                                                    type="submit"
                                                    class="text-orange-400 transition-colors hover:text-red-600"
                                                >
                                                    <x-heroicon-o-trash class="h-5 w-5" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No logistics found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-table-pagination :paginator="$logistics" />
        </div>
    </div>
@endsection
