@extends('layouts.dashboard')

@section('title', 'Team')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Team</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex flex-wrap gap-3">
            <!-- Export CSV Button -->
            <a href="{{ route('export', ['resource' => 'teams', 'format' => 'csv']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-400 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors">
                <x-heroicon-o-document class="w-4 h-4"/>
                Export CSV
            </a>

            <!-- Print PDF Button -->
            <a href="{{ route('export', ['resource' => 'teams', 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                <x-heroicon-o-printer class="w-4 h-4"/>
                Print PDF
            </a>
        </div>

        <!-- Add New Employee Button -->
        <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-transparent border-2 border-orange-400 text-orange-400 hover:bg-orange-50 text-sm font-medium rounded-lg transition-colors">
            ADD NEW TEAM
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <x-table-controls />

        <!-- Table -->

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">No.</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Team</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Description</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teams as $team)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $loop->iteration + ($teams->currentPage() - 1) * $teams->perPage() }}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $team->name }}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $team->description ?? '-' }}</td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.teams.edit', $team) }}" class="text-orange-400 hover:text-orange-600 transition-colors">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}" class="inline-flex items-center">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-orange-400 hover:text-red-600 transition-colors">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-4 text-center text-sm text-gray-500">
                                No teams found.
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
