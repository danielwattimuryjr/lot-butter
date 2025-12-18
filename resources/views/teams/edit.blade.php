@extends('layouts.dashboard')

@section('title', 'Edit Team')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div>
        <h1 class="text-xl font-bold text-gray-900">Edit Team</h1>
        <div class="mt-2 border-b border-gray-200"></div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.teams.update', $team) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                    Team Name
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name"
                    placeholder="Enter team name"
                    value="{{ old('name', $team->name) }}"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-900 mb-2">
                    Description
                </label>
                <textarea 
                    type="text" 
                    id="description" 
                    name="description"
                    placeholder="Enter team description"
                    class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                >{{ old('description', $team->description) }}</textarea>
                @error('description')
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
