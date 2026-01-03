@extends("layouts.dashboard")

@section("title", "Team")

@section("content")
    <div class="space-y-6">
        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">Teams</h1>
            <p class="mt-1 text-sm text-gray-600">Manage organizational teams and departments</p>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <div class="flex flex-wrap gap-3">
                <!-- Export CSV Button -->
                <a
                    href="{{ route("export", ["resource" => "teams", "format" => "csv"]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-orange-400 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-orange-500"
                >
                    <x-heroicon-o-document class="h-4 w-4" />
                    Export CSV
                </a>

                <!-- Print PDF Button -->
                <a
                    href="{{ route("export", ["resource" => "teams", "format" => "pdf"]) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-gray-900"
                >
                    <x-heroicon-o-printer class="h-4 w-4" />
                    Print PDF
                </a>
            </div>

            <a
                href="{{ route("admin.teams.create") }}"
                class="inline-flex items-center gap-2 rounded-lg bg-butter-500 px-6 py-2.5 text-sm font-medium text-white transition-colors hover:bg-butter-600"
            >
                <x-heroicon-o-plus class="h-5 w-5" />
                Add New Team
            </a>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <x-table-controls />

            <!-- Table -->
            <div class="mt-4 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">No.</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Team Name</th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-600">Description</th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teams as $team)
                            <tr class="border-b border-gray-100 transition-colors hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm text-gray-700">
                                    {{ $loop->iteration + ($teams->currentPage() - 1) * $teams->perPage() }}
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-100">
                                            <x-heroicon-o-user-group class="h-4 w-4 text-indigo-600" />
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $team->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600">
                                    {{ $team->description ?? "-" }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a
                                            href="{{ route("admin.teams.edit", $team) }}"
                                            class="text-orange-400 transition-colors hover:text-orange-600"
                                            title="Edit"
                                        >
                                            <x-heroicon-o-pencil-square class="h-5 w-5" />
                                        </a>
                                        <form
                                            method="POST"
                                            action="{{ route("admin.teams.destroy", $team) }}"
                                            class="inline-flex items-center"
                                            onsubmit="return confirm('Are you sure you want to delete this team?');"
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
                            </tr>
                        @empty
                            <tr class="border-b border-gray-100">
                                <td colspan="4" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="rounded-full bg-gray-100 p-4">
                                            <x-heroicon-o-user-group class="h-12 w-12 text-gray-400" />
                                        </div>
                                        <p class="mt-4 text-sm font-medium text-gray-900">No teams found</p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Create teams to organize your employees by department or function.
                                        </p>
                                        <a
                                            href="{{ route("admin.teams.create") }}"
                                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-butter-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-butter-600"
                                        >
                                            <x-heroicon-o-plus class="h-4 w-4" />
                                            Add First Team
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <x-table-pagination :paginator="$teams" />
        </div>
    </div>
@endsection
