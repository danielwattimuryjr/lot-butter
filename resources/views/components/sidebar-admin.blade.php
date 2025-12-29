<!-- Mobile Menu Button -->
<button 
    id="mobile-menu-btn"
    class="lg:hidden fixed bottom-4 right-4 z-50 bg-butter-500 text-white p-3 rounded-full shadow-lg"
>
    <x-feathericon-menu class="w-6 h-6"/>
</button>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed lg:relative inset-y-0 -left-64 lg:left-0 z-50 w-64 bg-white transition-all duration-300 ease-in-out border-r border-gray-100 shrink-0">
    <div class="p-6">
        <!-- Logo -->
        <div class="mb-2">
            <img src="{{ asset('images/logo.png') }}" alt="LOT.BUTTER Logo" class="mb-2 mx-auto">
        </div>
        <p class="text-sm text-gray-500 mb-8 text-center">MRP Dashboard</p>

        <!-- Navigation -->
        <nav class="space-y-1">
            <!-- Home -->
            <x-nav-item title="Home" icon="heroicon-o-home" :href="route('dashboard')" :active="request()->routeIs('dashboard')"/>

            <!-- Employee -->
            <x-nav-item title="Employee" icon="clarity-employee-line" :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.*')"/>

            <!-- Team -->
            <x-nav-item title="Team" icon="heroicon-o-user-group" :href="route('admin.teams.index')" :active="request()->routeIs('admin.teams.*')"/>

            <!-- Account -->
            <x-nav-item title="Account" icon="heroicon-o-key" :href="route('admin.accounts.index')" :active="request()->routeIs('admin.accounts.*')"/>
        </nav>
    </div>
</aside>

@push('scripts')
<script>
$(document).ready(function() {
    // Mobile menu toggle
    function toggleSidebar() {
        $('#sidebar').toggleClass('-left-64 left-0');
        $('#sidebar-overlay').toggleClass('hidden');
    }

    $('#mobile-menu-btn').on('click', toggleSidebar);
    $('#sidebar-overlay').on('click', toggleSidebar);
});
</script>
@endpush
