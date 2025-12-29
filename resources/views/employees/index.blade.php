@extends('layouts.dashboard')

@section('title', 'Employee')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Employee</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex flex-wrap gap-3">
            <!-- Export CSV Button -->
            <a href="{{ route('export', ['resource' => 'employees', 'format' => 'csv']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-400 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors">
                <x-heroicon-o-document class="w-4 h-4"/>
                Export CSV
            </a>

            <!-- Print PDF Button -->
            <a href="{{ route('export', ['resource' => 'employees', 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
                <x-heroicon-o-printer class="w-4 h-4"/>
                Print PDF
            </a>
        </div>

        <!-- Add New Employee Button -->
        <a href="{{ route('admin.employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-transparent border-2 border-orange-400 text-orange-400 hover:bg-orange-50 text-sm font-medium rounded-lg transition-colors">
            ADD NEW EMPLOYEE
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
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Name</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">NIP</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Phone</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Team</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($employees as $employee)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $employee->name}}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $employee->nip}}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $employee->phone_number }}</td>
                            <td class="py-4 px-4 text-sm text-gray-700">{{ $employee->team->name }}</td>
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="text-orange-400 hover:text-orange-600 transition-colors">
                                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" class="inline-flex items-center">
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
                            <td colspan="6" class="py-8 px-4 text-center text-sm text-gray-500">
                                No employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <x-table-pagination :paginator="$employees" />
    </div>
</div>
@endsection
