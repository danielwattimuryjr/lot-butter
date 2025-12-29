@extends('layouts.dashboard')

@section('title', 'Edit Component from BOM')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Edit Component from BOM</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('employee.production.products.bill-of-materials.update', [$product, $bom->component_id]) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="component" class="block text-sm font-medium text-gray-900 mb-2">
                    Component Name
                </label>
                <select name="component_id" id="component" class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none" required>
                  <option selected disabled>-- SELECT COMPONENT --</option>
                  @foreach ($components as $component)
                    <option value="{{ $component->id }}" {{ old('component_id', $bom->component_id) == $component->id ? 'selected' : '' }}>
                      {{ $component->name }} 
                      @if ($component->unit)
                          {{ '(' . $component->unit . ')' }}
                      @endif
                    </option>
                  @endforeach
                </select>
                @error('component_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-900 mb-2">
                    Quantity
                </label>
                <input 
                    id="quantity" 
                    name="quantity"
                    value="{{ old('quantity', $bom->quantity) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button 
                type="submit"
                class="px-12 py-3 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-colors"
            >
                Update
            </button>
        </form>
    </div>
</div>
@endsection
