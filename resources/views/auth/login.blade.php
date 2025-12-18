@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4" style="background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 50%, #d97706 100%);">
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden w-full max-w-5xl flex flex-col lg:flex-row">
        <!-- Left Side - Login Form -->
        <div class="flex-1 p-8 lg:p-12 flex flex-col justify-center">
            <!-- Logo -->
            <div class="mb-8">
                <h1 class="font-handwritten text-4xl lg:text-5xl font-bold text-gray-900 tracking-wide">
                    LOT.BUTTER
                </h1>
            </div>

            <!-- Welcome Text -->
            <div class="mb-8">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">
                    Hi, Lot.Butter Employee!
                </h2>
                <p class="text-gray-600">
                    The faster you work, the luckier you get
                </p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Username Field -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-900 mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username"
                        placeholder="Enter your username"
                        class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                        required
                    >
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 bg-gray-100 border-0 rounded-lg focus:ring-2 focus:ring-butter-400 focus:bg-white transition-all outline-none"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Button -->
                <div class="flex justify-center pt-4">
                    <button 
                        type="submit"
                        class="px-12 py-3 bg-gray-900 text-white font-medium rounded-full hover:bg-gray-800 transition-colors"
                    >
                        Login
                    </button>
                </div>
            </form>

            <!-- Terms -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600">
                    You acknowledge that you read and agree to our 
                    <a href="#" class="font-medium hover:underline">Terms of Service</a> 
                    and our 
                    <a href="#" class="font-medium hover:underline">Privacy Policy</a>.
                </p>
            </div>
        </div>

        <!-- Right Side - Image -->
        <div class="hidden lg:block flex-1 relative">
            <img 
                src="{{ asset('images/login-image.jpg') }}" 
                alt="LOT.BUTTER Products"
                class="w-full h-full object-cover"
            >
            <!-- Overlay with branding -->
            <div class="absolute top-4 right-4">
                <span class="font-handwritten text-white text-xl font-bold drop-shadow-lg">LOT.BUTTER</span>
            </div>
        </div>
    </div>
</div>
@endsection
