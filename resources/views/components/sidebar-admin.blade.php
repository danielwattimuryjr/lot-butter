<!-- Mobile Menu Button -->
<button
    id="mobile-menu-btn"
    class="fixed bottom-4 right-4 z-50 rounded-full bg-butter-500 p-3 text-white shadow-lg lg:hidden"
>
    <x-feathericon-menu class="h-6 w-6" />
</button>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"></div>

<!-- Sidebar -->
<aside
    id="sidebar"
    class="fixed inset-y-0 -left-64 z-50 w-64 shrink-0 border-r border-gray-100 bg-white transition-all duration-300 ease-in-out lg:relative lg:left-0"
>
    <div class="p-6">
        <!-- Logo -->
        <div class="mb-2">
            <img src="{{ asset("images/logo.png") }}" alt="LOT.BUTTER Logo" class="mx-auto mb-2" />
        </div>
        <p class="mb-8 text-center text-sm text-gray-500">MRP Dashboard</p>

        <!-- Navigation -->
        <nav class="space-y-1">
            <!-- Home -->
            <x-nav-item
                title="Home"
                icon="heroicon-o-home"
                :href="route('dashboard')"
                :active="request()->routeIs('dashboard')"
            />

            <!-- Employee -->
            <x-nav-item
                title="Employee"
                icon="clarity-employee-line"
                :href="route('admin.employees.index')"
                :active="request()->routeIs('admin.employees.*')"
            />

            <!-- Team -->
            <x-nav-item
                title="Team"
                icon="heroicon-o-user-group"
                :href="route('admin.teams.index')"
                :active="request()->routeIs('admin.teams.*')"
            />

            <!-- Account -->
            <x-nav-item
                title="Account"
                icon="heroicon-o-key"
                :href="route('admin.accounts.index')"
                :active="request()->routeIs('admin.accounts.*')"
            />
        </nav>
    </div>
</aside>

@push("scripts")
    <script>
        $(document).ready(function () {
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
