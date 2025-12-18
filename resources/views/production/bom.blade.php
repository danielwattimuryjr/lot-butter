@extends('layouts.dashboard')

@section('title', 'Bill of Material')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Bill of Material</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="#" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- BOM Code -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">BOM Code</label>
                    <input 
                        type="text" 
                        name="bom_code" 
                        value="BOM001" 
                        readonly
                        class="w-full px-4 py-3 bg-gray-100 rounded-lg text-gray-700 focus:outline-none"
                    >
                </div>

                <!-- BOM Date -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">BOM Date</label>
                    <input 
                        type="text" 
                        name="bom_date" 
                        value="21/03/2026" 
                        readonly
                        class="w-full px-4 py-3 bg-gray-100 rounded-lg text-gray-700 focus:outline-none"
                    >
                </div>

                <!-- Production Code -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Production Code</label>
                    <div class="relative">
                        <select 
                            name="production_code" 
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-700 appearance-none focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                        >
                            <option value="">Select Production Code</option>
                            <option value="PRD001">PRD001</option>
                            <option value="PRD002">PRD002</option>
                            <option value="PRD003">PRD003</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Production Date -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Production Date</label>
                    <input 
                        type="text" 
                        name="production_date" 
                        value="20/03/2026" 
                        readonly
                        class="w-full px-4 py-3 bg-gray-100 rounded-lg text-gray-700 focus:outline-none"
                    >
                </div>

                <!-- Product Name -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Product Name</label>
                    <input 
                        type="text" 
                        name="product_name" 
                        value="Daifuku Ichigo" 
                        readonly
                        class="w-full px-4 py-3 bg-gray-100 rounded-lg text-gray-700 focus:outline-none"
                    >
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Table Controls -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <!-- Show entries -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Show</span>
                <div class="relative">
                    <select class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 appearance-none pr-8 focus:outline-none focus:ring-2 focus:ring-orange-400">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input 
                    type="text" 
                    placeholder="Search" 
                    class="w-full pl-10 pr-4 py-2 bg-gray-100 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400"
                >
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">No.</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Raw Material Name</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4 text-sm text-gray-700">1</td>
                        <td class="py-4 px-4 text-sm text-gray-700">Filling Cream</td>
                        <td class="py-4 px-4 text-sm text-gray-700">290</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4 text-sm text-gray-700">2</td>
                        <td class="py-4 px-4 text-sm text-gray-700">Tepung Ketan</td>
                        <td class="py-4 px-4 text-sm text-gray-700">180</td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-4 px-4 text-sm text-gray-700">3</td>
                        <td class="py-4 px-4 text-sm text-gray-700">Fresh Strawberry</td>
                        <td class="py-4 px-4 text-sm text-gray-700">120</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Save Button -->
        <div class="flex justify-center mt-8">
            <button 
                type="submit" 
                class="px-8 py-3 bg-orange-400 hover:bg-orange-500 text-white font-medium rounded-full transition-colors"
            >
                Save
            </button>
        </div>
    </div>
</div>
@endsection
