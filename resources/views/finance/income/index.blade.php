@extends('layouts.dashboard')

@section('title', 'Income')

@section('content')
<div class="space-y-6">
  <!-- Page Title -->
  <div>
    <h1 class="text-xl font-bold text-gray-900">Income</h1>
    <div class="mt-2 border-b border-gray-200"></div>
  </div>

  @if (auth()->user()->team->name == 'Finance')  
    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div class="flex flex-wrap gap-3">
        <!-- Export CSV Button -->
        <a href="{{ route('export', ['resource' => 'incomes', 'format' => 'csv']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-400 hover:bg-orange-500 text-white text-sm font-medium rounded-lg transition-colors">
            <x-heroicon-o-document class="w-4 h-4"/>
            Export CSV
        </a>

        <!-- Print PDF Button -->
        <a href="{{ route('export', ['resource' => 'incomes', 'format' => 'pdf']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition-colors">
            <x-heroicon-o-printer class="w-4 h-4"/>
            Print PDF
        </a>
      </div>

      <!-- Add New Product Button -->
      <a href="{{ route('employee.finance.incomes.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-transparent border-2 border-orange-400 text-orange-400 hover:bg-orange-50 text-sm font-medium rounded-lg transition-colors">
        ADD NEW INCOME
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
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Income Code</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Description</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Qty</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Amount</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Date Received</th>
            <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Week</th>
            @if (auth()->user()->team->name == 'Finance')  
              <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @forelse ($incomes as $income)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
              <td class="py-4 px-4 text-sm text-gray-700">{{ $loop->iteration + ($incomes->currentPage() - 1) * $incomes->perPage() }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $income->code }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $income->description }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $income->quantity }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ 'Rp' . number_format($income->amount, 2) }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $income->date_received->format('d/m/Y') }}</td>
              <td class="py-4 px-4 text-sm text-gray-700">{{ $income->week }}</td>
              @if (auth()->user()->team->name == 'Finance')  
                <td class="py-4 px-4">
                  <div class="flex items-center gap-3">
                    <a href="{{ route('employee.finance.incomes.edit', $income) }}" class="text-orange-400 hover:text-orange-600 transition-colors">
                      <x-heroicon-o-pencil-square class="w-5 h-5" />
                    </a>
                    <form method="POST" action="{{ route('employee.finance.incomes.destroy', $income) }}" class="inline-flex items-center">
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
              <td colspan="7" class="py-8 px-4 text-center text-sm text-gray-500">
                  No incomes found.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <x-table-pagination :paginator="$incomes" />
  </div>
</div>
@endsection