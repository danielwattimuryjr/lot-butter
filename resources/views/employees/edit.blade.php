@extends('layouts.dashboard')

@section('title', 'Edit Employee')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Edit Employee</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.employees.update', $employee) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                    Name
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name"
                    value="{{ old('name', $employee->name) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nip" class="block text-sm font-medium text-gray-900 mb-2">
                    NIP
                </label>
                <input 
                    type="text" 
                    id="nip" 
                    name="nip"
                    value="{{ old('nip', $employee->nip) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    maxlength="11"
                    required
                >
                @error('nip')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-900 mb-2">
                    Phone
                </label>
                <input 
                    type="text" 
                    id="phone_number" 
                    name="phone_number"
                    value="{{ old('phone_number', $employee->phone_number) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    maxlength="12"
                    required
                >
                @error('phone_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="team" class="block text-sm font-medium text-gray-900 mb-2">
                    Team
                </label>
                <select 
                    id="team"
                    name="team_id" 
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                >
                    <option selected disabled>-- CHOOSE TEAM --</option>
                    @foreach ($teams as $team)
                        <option value="{{ $team->id }}" {{ (old('team_id', $employee->team_id)) == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                    @endforeach
                </select>
                @error('team_id')
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
