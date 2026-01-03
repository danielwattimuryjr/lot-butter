@extends("layouts.dashboard")

@section("title", "Components")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Components</h1>
            <p class="mt-1 text-sm text-gray-600">Manage raw materials and component inventory</p>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        @if (auth()->user()->team->name == "Production")
            <!-- Action Buttons -->
            <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <div class="flex flex-wrap gap-3">
                    <!-- Export CSV Button -->
                    <a
                        href="{{ route("export", ["resource" => "components", "format" => "csv"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                    >
                        <x-heroicon-o-document class="h-4 w-4" />
                        Export CSV
                    </a>

                    <!-- Print PDF Button -->
                    <a
                        href="{{ route("export", ["resource" => "components", "format" => "pdf"]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                    >
                        <x-heroicon-o-printer class="h-4 w-4" />
                        Print PDF
                    </a>
                </div>

                <a
                    href="{{ route("employee.production.components.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                >
                    <x-heroicon-o-plus class="h-5 w-5" />
                    Add New Component
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
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Component</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Unit</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Category</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Safety Stock</th>
                            <th class="px-4 py-3 text-right text-sm font-medium text-gray-600">Current Stock</th>
                            @if (auth()->user()->team->name == "Production")
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($components as $component)
                            <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($components->currentPage() - 1) * $components->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100">
                                            <x-heroicon-o-cube class="h-4 w-4 text-purple-600" />
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $component->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $component->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700"
                                    >
                                        {{ $component->unit }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700"
                                    >
                                        {{ $component->category }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center text-sm">
                                    <span
                                        class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800"
                                    >
                                        {{ number_format($component->safety_stock ?? 0) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right text-sm">
                                    @php
                                        $stock = $component->stock ?? 0;
                                        $safetyStock = $component->safety_stock ?? 0;
                                        $isLow = $stock <= $safetyStock;
                                    @endphp

                                    <div class="flex flex-col items-end gap-1">
                                        <span
                                            class="{{ $isLow ? "text-red-600" : "text-gray-900" }} text-lg font-bold"
                                        >
                                            {{ number_format($stock) }}
                                        </span>
                                        @if ($isLow && $stock > 0)
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700"
                                            >
                                                <x-heroicon-o-exclamation-triangle class="h-3 w-3" />
                                                Low Stock
                                            </span>
                                        @elseif ($stock == 0)
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700"
                                            >
                                                <x-heroicon-o-x-circle class="h-3 w-3" />
                                                Out of Stock
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                @if (auth()->user()->team->name == "Production")
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <a
                                                href="{{ route("employee.production.components.edit", $component) }}"
                                                class="text-orange-400 transition-colors hover:text-orange-600"
                                            >
                                                <x-heroicon-o-pencil-square class="h-5 w-5" />
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route("employee.production.components.destroy", $component) }}"
                                                class="inline-flex items-center"
                                                onsubmit="
                                                    return confirm('Are you sure you want to delete this component?');
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
                                <td colspan="8" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-100 p-4">
                                            <x-heroicon-o-cube class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <p class="mt-4 text-sm font-medium text-gray-900">No components found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Add raw materials and components to start managing your inventory.
                                        </p>
                                        @if (auth()->user()->team->name == "Production")
                                            <a
                                                href="{{ route("employee.production.components.create") }}"
                                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-butter-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                                            >
                                                <x-heroicon-o-plus class="h-4 w-4" />
                                                Add First Component
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
            <x-table-pagination :paginator="$components" />
        </div>
    </div>
@endsection
