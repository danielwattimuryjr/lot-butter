<!-- Sidebar for Employee section (simpler navigation) -->
<aside id="sidebar" class="fixed lg:static inset-y-0 -left-64 lg:left-0 z-50 w-64 bg-white rounded-2xl shadow-sm m-4 p-4 transition-all duration-300 ease-in-out overflow-y-auto">
    <!-- Logo -->
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight" style="font-family: 'Comic Sans MS', cursive, sans-serif;">
            LOT.BUTTER
        </h1>
        <p class="text-sm text-gray-500">MRP Dashboard</p>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 space-y-2">
        <!-- Home -->
        <x-nav-item :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium">Home</span>
        </x-nav-item>

        <!-- Employee -->
        <x-nav-item :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.*')">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="font-medium">Employee</span>
        </x-nav-item>

        <!-- Team -->
        <x-nav-item :href="route('admin.teams.index')" :active="request()->routeIs('admin.teams.*')">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span class="font-medium">Team</span>
        </x-nav-item>

        <!-- Account -->
        <x-nav-item :href="route('admin.accounts.index')" :active="request()->routeIs('admin.accounts.*')">
            <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="font-medium">Account</span>
        </x-nav-item>
    </nav>
</aside>
