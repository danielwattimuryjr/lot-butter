@extends("layouts.dashboard")

@section("title", "Income")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Income Records</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Finance")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("export", ["resource" => "incomes", "format" => "csv"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("export", ["resource" => "incomes", "format" => "pdf"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>

                <!-- Add New Income Button -->
                <a
                    href="{{ route("employee.finance.incomes.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                >
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add New Income
                </a>
            </div>
        @endif

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <x-table-controls />

            <!-- Table -->
            <div class="mt-4 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Income Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Product</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Variant</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Description</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Unit Price</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Amount</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Date</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Week</th>
                            @if (auth()->user()->team->name == "Finance")
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($incomes as $income)
                            <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($incomes->currentPage() - 1) * $incomes->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $income->code }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $income->product ? $income->product->name : "N/A" }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    @if ($income->productVariant)
                                        <span
                                            class="text-butter-800 inline-flex items-center rounded-full bg-butter-100 px-2.5 py-0.5 text-xs font-medium"
                                        >
                                            {{ $income->productVariant->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <div class="max-w-xs truncate" title="{{ $income->description }}">
                                        {{ $income->description }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-700">
                                    {{ number_format($income->quantity) }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-700">
                                    Rp {{ number_format($income->unit_price, 0, ",", ".") }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($income->amount, 0, ",", ".") }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $income->date_received->format("d M Y") }}
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-700">
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800"
                                    >
                                        W{{ $income->week }}
                                    </span>
                                </td>
                                @if (auth()->user()->team->name == "Finance")
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <a
                                                href="{{ route("employee.finance.incomes.edit", $income) }}"
                                                class="text-orange-400 transition-colors hover:text-orange-600"
                                                title="Edit"
                                            >
                                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route("employee.finance.incomes.destroy", $income) }}"
                                                class="inline-flex items-center"
                                                onsubmit="
                                                    return confirm(
                                                        'Are you sure you want to delete this income record?',
                                                    );
                                                "
                                            >
                                                @csrf
                                                @method("DELETE")

                                                <button
                                                    type="submit"
                                                    class="text-orange-400 transition-colors hover:text-red-600"
                                                    title="Delete"
                                                >
                                                    <x-heroicon-o-trash class="h-5 w-5" />
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100">
                                <td colspan="11" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <x-heroicon-o-inbox class="h-12 w-12 text-gray-400" />
                                        <p class="mt-2 text-sm font-medium text-gray-900">No income records found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Get started by creating a new income entry.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-table-pagination :paginator="$incomes" />
        </div>
    </div>
@endsection
