@extends("layouts.dashboard")

@section("title", "Journal")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Journal</h1>
            <div class="mt-2 border-b border-gray-200"></div>
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
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Journal Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Date</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Description</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Debit</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Credit</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($journals as $journal)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($journals->currentPage() - 1) * $journals->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $journal->code }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $journal->date->format("d/m/Y") }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $journal->description }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $journal->debit ? "Rp" . number_format($journal->debit, 2) : "-" }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $journal->credit ? "Rp" . number_format($journal->credit, 2) : "-" }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ "Rp" . number_format($journal->balance, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No journals found.
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
