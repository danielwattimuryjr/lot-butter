@extends("layouts.dashboard")

@section("title", "Components")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Components</h1>
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

                <!-- Add New Product Button -->
                <a
                    href="{{ route("employee.production.components.create") }}"
                    class="inline-flex items-center gap-2 rounded-lg border-2 border-orange-400 bg-transparent px-4 py-2 text-sm font-medium text-orange-400 transition-colors hover:bg-orange-50"
                >
                    ADD NEW COMPONENTS
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
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Item Code</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Component Name</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Unit</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Category</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Safety Stock</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Stock</th>
                            @if (auth()->user()->team->name == "Production")
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                            @endif

                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($components as $component)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($components->currentPage() - 1) * $components->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $component->code }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $component->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $component->unit }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $component->category }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ number_format($component->safety_stock ?? 0) }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ number_format($component->stock ?? 0) }}
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

                                <td class="px-4 py-4 text-sm text-gray-700">
                                    <a
                                        href="{{ route("employee.production.components.material-requirements-planning.index", $component) }}"
                                        class="text-orange-400 transition-colors hover:text-orange-600"
                                    >
                                        MRP
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">
                                    No components found.
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
