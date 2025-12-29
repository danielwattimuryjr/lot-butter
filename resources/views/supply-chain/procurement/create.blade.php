@extends('layouts.dashboard')

@section('title', 'Create Purchase')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Create Purchase</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('employee.supply-chain.procurements.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="component" class="block text-sm font-medium text-gray-900 mb-2">
                    Component
                </label>
                <select name="component_id" id="component" class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none" required>
                  <option selected disabled>-- SELECT COMPONENT REFERENCE --</option>
                  @foreach ($components as $component)
                    <option value="{{ $component->id }}" {{ old('component_id') == $component->id ? 'selected' : '' }}>
                      {{ $component->name }} 
                    </option>
                  @endforeach
                </select>
                @error('component_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                    Description
                </label>
                <textarea class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none" name="description" id="description" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-900 mb-2">
                    Quantity
                </label>
                <input 
                    type="number" 
                    id="quantity" 
                    name="quantity"
                    value="{{ old('quantity') }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="unit_price" class="block text-sm font-medium text-gray-900 mb-2">
                    Unit Price
                </label>
                <input 
                    type="number" 
                    id="unit_price" 
                    name="unit_price"
                    value="{{ old('unit_price') }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('unit_price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-gray-900 mb-2">
                    Purchase Date
                </label>
                <input 
                    type="date" 
                    id="date" 
                    name="date"
                    value="{{ old('date') }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="supplier" class="block text-sm font-medium text-gray-900 mb-2">
                    Supplier
                </label>
                <input 
                    id="supplier" 
                    name="supplier"
                    value="{{ old('supplier') }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('supplier')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button 
                type="submit"
                class="px-12 py-3 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-colors"
            >
                Create
            </button>
        </form>
    </div>
</div>
@endsection
