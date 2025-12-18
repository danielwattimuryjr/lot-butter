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
            <button class="inline-flex items-center gap-2 px-4 py-2 bg-orange-400 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </button>

            <!-- Print PDF Button -->
            <button class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print PDF
            </button>
        </div>

        <!-- Add New Employee Button -->
        <a href="{{ route('admin.teams.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-transparent border-2 border-orange-400 text-orange-400 hover:bg-orange-50 text-sm font-medium rounded-lg transition-colors">
            ADD NEW TEAM
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Table Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <!-- Show entries -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Show</span>
                <div class="relative">
                    <select class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 appearance-none pr-8 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    placeholder="Search" 
                    class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
            </div>
        </div>

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
                    @foreach ($teams as $team)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $team->name }}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $team->description ?? '-' }}</td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.teams.edit', $team) }}" class="text-orange-400 hover:text-orange-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-orange-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-end items-center gap-2 mt-6">
            <button class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-300 text-gray-700 font-medium">
                1
            </button>
            <button class="w-8 h-8 flex items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
</div>
@endsection
