@extends('layouts.dashboard')

@section('title', 'MRP Process')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">MRP Process</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="#" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                <!-- Month -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Month</label>
                    <input 
                        type="text" 
                        name="month" 
                        placeholder="Enter month"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
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

                <!-- Year -->
                <div>
                    <label class="block text-sm text-gray-600 mb-2">Year</label>
                    <input 
                        type="text" 
                        name="year" 
                        placeholder="Enter year"
                        class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent"
                    >
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

                <!-- Bulan Pesan & Bulan Simpan -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Bulan Pesan</label>
                        <input 
                            type="text" 
                            name="bulan_pesan" 
                            value="21/03/2026" 
                            readonly
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-700 focus:outline-none"
                        >
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Bulan Simpan</label>
                        <input 
                            type="text" 
                            name="bulan_simpan" 
                            value="21/03/2026" 
                            readonly
                            class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-gray-700 focus:outline-none"
                        >
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-b border-gray-200 my-8"></div>

            <!-- Process Button -->
            <div class="flex justify-center">
                <button 
                    type="submit" 
                    class="px-8 py-3 bg-orange-400 hover:bg-orange-500 text-white font-medium rounded-full transition-colors"
                >
                    Process
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
