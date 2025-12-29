@extends('layouts.dashboard')

@section('title', 'Edit Income')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Edit Income</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('employee.finance.incomes.update', $income) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="Product" class="block text-sm font-medium text-gray-900 mb-2">
                    Product
                </label>
                <select name="product_id" id="product" class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none" required>
                  <option selected disabled>-- SELECT PRODUCT REFERENCE --</option>
                  @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id', $income->product_id) == $product->id ? 'selected' : '' }}>
                      {{ $product->name }} 
                      @if ($product->pack)
                          {{ '(' . $product->pack . ')' }}
                      @endif
                    </option>
                  @endforeach
                </select>
                @error('product_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                    Description
                </label>
                <textarea class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none" name="description" id="description" required>{{ old('description', $income->description) }}</textarea>
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
                    value="{{ old('quantity', $income->quantity) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="date_received" class="block text-sm font-medium text-gray-900 mb-2">
                    Date Received
                </label>
                <input 
                    type="date" 
                    id="date_received" 
                    name="date_received"
                    value="{{ old('date_received', $income->date_received->format('Y-m-d')) }}" class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('date_received')
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
