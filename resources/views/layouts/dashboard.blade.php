<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'LOT.BUTTER - MRP Dashboard' }}</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Caveat:wght@400;700&display=swap');
        .font-handwritten { font-family: 'Caveat', cursive; }
    </style>
</head>
<body class="antialiased bg-white overflow-x-hidden">
    <!-- Top Bar -->
    <div class="h-12 flex items-center justify-end px-4 lg:px-6" style="background: linear-gradient(90deg, #fcd34d 0%, #f59e0b 100%);">
        <a href="{{ route('logout') }}" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center gap-2 text-white hover:text-gray-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            <span class="font-medium">Log Out</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <div class="flex min-h-[calc(100vh-3rem)] overflow-hidden">
        <!-- Sidebar -->
        @if (auth()->user()->hasRole('admin'))
            <x-sidebar-admin />
        @else
            <x-sidebar-employee />
        @endif

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-6 w-full">
            <x-header />

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
