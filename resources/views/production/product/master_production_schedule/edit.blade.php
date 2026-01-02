@extends('layouts.dashboard')

@section('title', 'Edit Product MPS')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Edit Product MPS</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('employee.production.products.master-production-schedule.update', [$product, $master_production_schedule]) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="{{ $master_production_schedule->week === 0 ? 'hidden' : '' }}">
                <label for="available" class="block text-sm font-medium text-gray-900 mb-2">
                    Available
                </label>
                <input 
                    type="number" 
                    id="available" 
                    name="available"
                    value="{{ old('available', $master_production_schedule->available) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                >
                @error('available')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="projected_on_hand" class="block text-sm font-medium text-gray-900 mb-2">
                    Projected On Hand
                </label>
                <input 
                    type="number" 
                    id="projected_on_hand" 
                    name="projected_on_hand"
                    value="{{ old('projected_on_hand', $master_production_schedule->projected_on_hand) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                >
                @error('projected_on_hand')
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
