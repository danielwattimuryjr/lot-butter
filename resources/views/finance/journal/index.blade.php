@extends("layouts.dashboard")

@section("title", "Journal")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Financial Journal</h1>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid gap-6 md:grid-cols-3">
            <!-- Current Balance Card -->
            <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-butter-50 to-white p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Current Balance</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900">
                            Rp {{ number_format($currentBalance, 0, ",", ".") }}
                        </p>
                    </div>
                    <div class="rounded-lg bg-butter-100 p-3">
                        <x-heroicon-o-banknotes class="h-6 w-6 text-butter-600" />
                    </div>
                </div>
            </div>

            <!-- This Month Revenue Card -->
            <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-green-50 to-white p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">This Month Revenue</p>
                        <p class="mt-2 text-2xl font-bold text-green-600">
                            Rp {{ number_format($thisMonthRevenue, 0, ",", ".") }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">{{ now()->format("F Y") }}</p>
                    </div>
                    <div class="rounded-lg bg-green-100 p-3">
                        <x-heroicon-o-arrow-trending-up class="h-6 w-6 text-green-600" />
                    </div>
                </div>
            </div>

            <!-- This Month Expenses Card -->
            <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">This Month Expenses</p>
                        <p class="mt-2 text-2xl font-bold text-red-600">
                            Rp {{ number_format($thisMonthExpenses, 0, ",", ".") }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">{{ now()->format("F Y") }}</p>
                    </div>
                    <div class="rounded-lg bg-red-100 p-3">
                        <x-heroicon-o-arrow-trending-down class="h-6 w-6 text-red-600" />
                    </div>
                </div>
            </div>
        </div>

        @if (auth()->user()->team->name == "Finance")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("export", ["resource" => "journals", "format" => "csv"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("export", ["resource" => "journals", "format" => "pdf"]) }}"
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
            <x-table-controls />

            <!-- Table -->
            <div class="mt-4 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Journal Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Description</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Debit</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Credit</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($journals as $journal)
                            <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($journals->currentPage() - 1) * $journals->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $journal->code }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $journal->date->format("d M Y") }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    <div class="max-w-xs truncate" title="{{ $journal->description }}">
                                        {{ $journal->description }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-700">
                                    @if ($journal->debit)
                                        <span class="font-medium text-red-600">
                                            Rp {{ number_format($journal->debit, 0, ",", ".") }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-700">
                                    @if ($journal->credit)
                                        <span class="font-medium text-green-600">
                                            Rp {{ number_format($journal->credit, 0, ",", ".") }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($journal->balance, 0, ",", ".") }}
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100">
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-100 p-4">
                                            <x-heroicon-o-book-open class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <p class="mt-4 text-sm font-medium text-gray-900">No journal entries found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Journal entries will appear here automatically when income or purchase
                                            transactions are recorded.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-table-pagination :paginator="$journals" />
        </div>
    </div>
@endsection
