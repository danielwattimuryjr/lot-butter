@extends('layouts.dashboard')

@section('title', 'Create Logistic Report')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Create Logistic Report</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('employee.supply-chain.logistics.store') }}" class="space-y-6">
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
                <label for="transaction_type" class="block text-sm font-medium text-gray-900 mb-2">
                    Transaction Type
                </label>
                <div class="flex gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input 
                            type="radio" 
                            name="transaction_type" 
                            value="in"
                            {{ old('transaction_type') == 'in' ? 'checked' : '' }}
                            class="mr-2"
                            required
                        >
                        <span class="text-green-600 font-medium">Stock In</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input 
                            type="radio" 
                            name="transaction_type" 
                            value="out"
                            {{ old('transaction_type') == 'out' ? 'checked' : '' }}
                            class="mr-2"
                            required
                        >
                        <span class="text-red-600 font-medium">Stock Out</span>
                    </label>
                </div>
                @error('transaction_type')
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
                <label for="date" class="block text-sm font-medium text-gray-900 mb-2">
                   Date
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
                <label for="notes" class="block text-sm font-medium text-gray-900 mb-2">
                    Notes
                </label>
                <textarea class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none" name="notes" id="notes">{{ old('notes') }}</textarea>
                @error('notes')
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
