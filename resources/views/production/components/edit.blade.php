@extends('layouts.dashboard')

@section('title', 'Edit Component')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Edit Component</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('employee.production.components.update', $component) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="code" class="block text-sm font-medium text-gray-900 mb-2">
                    Item Code
                </label>
                <input 
                    type="text" 
                    id="code" 
                    name="code"
                    value="{{ old('code', $component->code) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                    Component Name
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name"
                    value="{{ old('name', $component->name) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="weight" class="block text-sm font-medium text-gray-900 mb-2">
                    Weight
                </label>
                <input 
                    type="number" 
                    id="weight" 
                    name="weight"
                    value="{{ old('weight', $component->weight) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('weight')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-gray-900 mb-2">
                    Unit
                </label>
                <input 
                    type="text" 
                    id="unit" 
                    name="unit"
                    value="{{ old('unit', $component->unit) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('unit')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="block text-sm font-medium text-gray-900 mb-2">
                    Category
                </label>
                <input 
                    type="text" 
                    id="category" 
                    name="category"
                    value="{{ old('category', $component->category) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="safety_stock" class="block text-sm font-medium text-gray-900 mb-2">
                    Safety Stock
                </label>
                <input 
                    type="number" 
                    id="safety_stock" 
                    name="safety_stock"
                    value="{{ old('safety_stock', $component->safety_stock) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('safety_stock')
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
