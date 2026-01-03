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

            <!-- Production -->
            <x-nav-item-dropdown
                title="Production"
                icon="heroicon-o-cog"
                :active="request()->routeIs('employee.production.*')"
            >
                <a
                    href="{{ route("employee.production.components.index") }}"
                    class="{{ request()->routeIs("employee.production.components.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    Component
                </a>
                <a
                    href="{{ route("employee.production.products.index") }}"
                    class="{{ request()->routeIs("employee.production.products.*") && ! request()->routeIs("employee.production.master-production-schedules.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    Product
                </a>
                <a
                    href="{{ route("employee.production.bill-of-materials.index") }}"
                    class="{{ request()->routeIs("employee.production.bill-of-materials.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    BOM
                </a>
                <a
                    href="{{ route("employee.production.master-production-schedules.index") }}"
                    class="{{ request()->routeIs("employee.production.master-production-schedules.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    MPS
                </a>
                <a
                    href="{{ route("employee.production.mrp.index") }}"
                    class="{{ request()->routeIs("employee.production.mrp.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    MRP
                </a>
            </x-nav-item-dropdown>

            <!-- Supply Chain -->
            <x-nav-item-dropdown
                title="Supply Chain"
                icon="heroicon-o-shopping-cart"
                :active="request()->routeIs('employee.supply-chain.*')"
            >
                <a
                    href="{{ route("employee.supply-chain.logistics.index") }}"
                    class="{{ request()->routeIs("employee.supply-chain.logistics.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    Logistic
                </a>
                <a
                    href="{{ route("employee.supply-chain.procurements.index") }}"
                    class="{{ request()->routeIs("employee.supply-chain.procurements.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    Procurement
                </a>
            </x-nav-item-dropdown>

            <!-- Finance -->
            <x-nav-item-dropdown
                title="Finance"
                icon="heroicon-o-clipboard"
                :active="request()->routeIs('employee.finance.*')"
            >
                <a
                    href="{{ route("employee.finance.incomes.index") }}"
                    class="{{ request()->routeIs("employee.finance.incomes.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    Income
                </a>
                <a
                    href="{{ route("employee.finance.journals.index") }}"
                    class="{{ request()->routeIs("employee.finance.journals.*") ? "bg-orange-50 font-medium text-orange-600" : "text-gray-500 hover:bg-gray-50 hover:text-gray-900" }} block rounded-lg px-3 py-2 transition-colors"
                >
                    Journal
                </a>
            </x-nav-item-dropdown>
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

            // Nav group toggles
            $('.nav-toggle').on('click', function () {
                const $group = $(this).closest('.nav-group');
                const $submenu = $group.find('.nav-submenu');
                const $arrow = $(this).find('.nav-arrow');

                $submenu.toggleClass('hidden');
                $arrow.toggleClass('rotate-180');
            });
        });
    </script>
@endpush
