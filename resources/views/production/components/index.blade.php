@extends('layouts.dashboard')

@section('title', 'Components')

@section('content')
<div class="space-y-6">
  <!-- Page Title -->
  <div>
    <h1 class="text-xl font-bold text-gray-900">Components</h1>
    <div class="mt-2 border-b border-gray-200"></div>
  </div>

  @if (auth()->user()->team->name == 'Production')  
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div class="flex flex-wrap gap-3">
        <!-- Export CSV Button -->
        <a href="{{ route('export', ['resource' => 'components', 'format' => 'csv']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-400 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors">
            <x-heroicon-o-document class="w-4 h-4"/>
            Export CSV
        </a>

        <!-- Print PDF Button -->
        <a href="{{ route('export', ['resource' => 'components', 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
            <x-heroicon-o-printer class="w-4 h-4"/>
            Print PDF
        </a>
      </div>

      <!-- Add New Product Button -->
      <a href="{{ route('employee.production.components.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-transparent border-2 border-orange-400 text-orange-400 hover:bg-orange-50 text-sm font-medium rounded-lg transition-colors">
        ADD NEW COMPONENTS
      </a>
    </div>
  @endif

  <!-- Table Card -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <x-table-controls />

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-100">
          <tr class="border-b border-gray-200">
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">No.</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Item Code</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Component Name</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Weight</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Unit</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Category</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Safety Stock</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Stock</th>
            @if (auth()->user()->team->name == 'Production')
              <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @forelse ($components as $component)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
              <td class="py-4 px-4 text-sm text-gray-700">{{ $loop->iteration + ($components->currentPage() - 1) * $components->perPage() }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $component->code }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $component->name }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $component->weight }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $component->unit }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $component->category }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ number_format($component->safety_stock ?? 0) }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ number_format($component->stock ?? 0) }}</td>
              @if (auth()->user()->team->name == 'Production')
                <td class="py-4 px-4">
                  <div class="flex items-center gap-3">
                    <a href="{{ route('employee.production.components.edit', $component) }}" class="text-orange-400 hover:text-orange-600 transition-colors">
                      <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </a>
                    <form method="POST" action="{{ route('employee.production.components.destroy', $component) }}" class="inline-flex items-center">
                      @csrf
                      @method('DELETE')

                      <button type="submit" class="text-orange-400 hover:text-red-600 transition-colors">
                        <x-heroicon-o-trash class="w-5 h-5" />
                      </button>
                    </form>
                  </div>
                </td>
              @endif
            </tr>
          @empty
            <tr class="border-b border-gray-100 hover:bg-gray-50">
              <td colspan="9" class="py-8 px-4 text-center text-sm text-gray-500">
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