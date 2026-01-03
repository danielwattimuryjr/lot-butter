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
                    class="block py-2 text-gray-500 transition-colors hover:text-gray-900"
                >
                    Component
                </a>
                <a
                    href="{{ route("employee.production.products.index") }}"
                    class="block py-2 text-gray-500 transition-colors hover:text-gray-900"
                >
                    Product
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
                    class="block py-2 text-gray-500 transition-colors hover:text-gray-900"
                >
                    Logistic
                </a>
                <a
                    href="{{ route("employee.supply-chain.procurements.index") }}"
                    class="block py-2 text-gray-500 transition-colors hover:text-gray-900"
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
                    class="block py-2 text-gray-500 transition-colors hover:text-gray-900"
                >
                    Income
                </a>
                <a
                    href="{{ route("employee.finance.journals.index") }}"
                    class="block py-2 text-gray-500 transition-colors hover:text-gray-900"
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
