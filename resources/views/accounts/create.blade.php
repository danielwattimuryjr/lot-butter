@extends('layouts.dashboard')

@section('title', 'Create Account')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Create Account</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.accounts.store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="employee" class="block text-sm font-medium text-gray-900 mb-2">
                    Employee
                </label>
                <select 
                    id="employee"
                    name="employee_id" 
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                >
                    <option selected disabled>-- CHOOSE EMPLOYEE --</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                    @endforeach
                </select>
                @error('employee_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-gray-900 mb-2">
                    Username
                </label>
                <input 
                    type="text" 
                    id="username" 
                    name="username"
                    value="{{ old('username') }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                    Password
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('password')
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
