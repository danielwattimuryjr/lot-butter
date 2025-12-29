@extends('layouts.dashboard')

@section('title', 'Bill of Material')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Bill of Material</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    @if (auth()->user()->team->name == 'Production')  
      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Add New Product Button -->
        <a href="{{ route('employee.production.products.bill-of-materials.create', $product) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-transparent border-2 border-orange-400 text-orange-400 hover:bg-orange-50 text-sm font-medium rounded-lg transition-colors">
          ADD COMPONENT TO BOM
        </a>
      </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="text-center">
          <h2 class="font-bold text-gray-900">BOM</h2>
          <p class="text-gray-900">{{ $product->name . ' (' . $product->pack . ')'}}</p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto mt-4">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">No.</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Component Name</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Qty</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Unit</th>
                        @if (auth()->user()->team->name == 'Production')  
                          <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                  @forelse ($product->components as $component)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                      <td class="py-4 px-4 text-sm text-gray-700">{{ $loop->iteration }}</td>
                      <td class="py-4 px-4 text-sm text-gray-700">{{ $component->name }}</td>
                      <td class="py-4 px-4 text-sm text-gray-700">{{ $component->pivot->quantity }}</td>
                      <td class="py-4 px-4 text-sm text-gray-700">{{ $component->unit }}</td>
                      @if (auth()->user()->team->name == 'Production')  
                        <td class="py-4 px-4">
                          <div class="flex items-center gap-3">
                            <a href="{{ route('employee.production.products.bill-of-materials.edit', [$product, $component]) }}" class="text-orange-400 hover:text-orange-600 transition-colors">
                              <x-heroicon-o-pencil-square class="w-5 h-5" />
                            </a>
                            <form method="POST" action="{{ route('employee.production.products.bill-of-materials.destroy', [$product, $component]) }}" class="inline-flex items-center">
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
                          No components found.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
