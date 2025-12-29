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

            <!-- Production -->
            <x-nav-item-dropdown title="Production" icon="heroicon-o-cog" :active="request()->routeIs('employee.production.*')">
                <a href="{{ route('employee.production.components.index') }}" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Component</a>
                <a href="{{ route('employee.production.products.index') }}" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Product</a>
                <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">MPS</a> 
                <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">MRP</a> 
            </x-nav-item-dropdown>

            <!-- Supply Chain -->
            <x-nav-item-dropdown title="Supply Chain" icon="heroicon-o-shopping-cart" :active="request()->routeIs('employee.supply-chain.*')">
                <a href="{{ route('employee.supply-chain.logistics.index') }}" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Logistic</a>
                <a href="{{ route('employee.supply-chain.procurements.index') }}" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Procurement</a>
            </x-nav-item-dropdown>

            <!-- Finance -->
            <x-nav-item-dropdown title="Finance" icon="heroicon-o-clipboard" :active="request()->routeIs('employee.finance.*')">
                <a href="{{ route('employee.finance.incomes.index') }}" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Income</a>
                <a href="{{ route('employee.finance.journals.index') }}" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Journal</a>
            </x-nav-item-dropdown>
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

    // Nav group toggles
    $('.nav-toggle').on('click', function() {
        const $group = $(this).closest('.nav-group');
        const $submenu = $group.find('.nav-submenu');
        const $arrow = $(this).find('.nav-arrow');
        
        $submenu.toggleClass('hidden');
        $arrow.toggleClass('rotate-180');
    });
});
</script>
@endpush
