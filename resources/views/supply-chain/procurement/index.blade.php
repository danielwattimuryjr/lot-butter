@extends("layouts.dashboard")

@section("title", "Procurement")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Procurement Management</h1>
            <p class="mt-1 text-sm text-gray-600">Track and manage component purchases</p>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Procurement")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("export", ["resource" => "procurements", "format" => "csv"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("export", ["resource" => "procurements", "format" => "pdf"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>

                <!-- Add New Purchase Button -->
                <a
                    href="{{ route("employee.supply-chain.procurements.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                >
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add New Purchase
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
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Purchase Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Component</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Week</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Unit Price</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Total Amount</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Purchase Date</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Supplier</th>
                            @if (auth()->user()->team->name == "Procurement")
                                <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($procurements as $procurement)
                            <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($procurements->currentPage() - 1) * $procurements->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $procurement->code }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-butter-100">
                                            <x-heroicon-o-cube class="h-4 w-4 text-butter-600" />
                                        </div>
                                        <span>{{ $procurement->component->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-700">
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800"
                                    >
                                        W{{ $procurement->week }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-700">
                                    {{ number_format($procurement->quantity) }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm text-gray-700">
                                    Rp {{ number_format($procurement->unit_price, 0, ",", ".") }}
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($procurement->total_amount, 0, ",", ".") }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $procurement->date->format("d M Y") }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <span
                                        class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700"
                                    >
                                        {{ $procurement->supplier }}
                                    </span>
                                </td>
                                @if (auth()->user()->team->name == "Procurement")
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <a
                                                href="{{ route("employee.supply-chain.procurements.edit", $procurement) }}"
                                                class="text-orange-400 transition-colors hover:text-orange-600"
                                                title="Edit"
                                            >
                                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route("employee.supply-chain.procurements.destroy", $procurement) }}"
                                                class="inline-flex items-center"
                                                onsubmit="
                                                    return confirm(
                                                        'Are you sure you want to delete this purchase record?',
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
                                <td colspan="10" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-100 p-4">
                                            <x-heroicon-o-shopping-cart class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <p class="mt-4 text-sm font-medium text-gray-900">No purchase records found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Start by creating your first component purchase.
                                        </p>
                                        @if (auth()->user()->team->name == "Procurement")
                                            <a
                                                href="{{ route("employee.supply-chain.procurements.create") }}"
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-butter-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                                            >
                                                <x-heroicon-o-plus class="h-4 w-4" />
                                                Add First Purchase
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-table-pagination :paginator="$procurements" />
        </div>
    </div>
@endsection
