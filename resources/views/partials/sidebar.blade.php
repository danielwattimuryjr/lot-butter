<!-- Mobile Menu Button -->
<button 
    id="mobile-menu-btn"
    class="lg:hidden fixed bottom-4 right-4 z-50 bg-butter-500 text-white p-3 rounded-full shadow-lg"
>
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="lg:hidden fixed inset-0 bg-black/50 z-40 hidden"></div>

<!-- Sidebar -->
<aside id="sidebar" class="fixed lg:relative inset-y-0 -left-64 lg:left-0 z-50 w-64 bg-white transition-all duration-300 ease-in-out border-r border-gray-100 shrink-0">
    <div class="p-6">
        <!-- Logo -->
        <div class="mb-2">
            <h1 class="font-handwritten text-3xl font-bold text-gray-900">LOT.BUTTER</h1>
        </div>
        <p class="text-sm text-gray-500 mb-8">MRP Dashboard</p>

        <!-- Navigation -->
        <nav class="space-y-1">
            <!-- Home -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-butter-100 text-gray-900 font-medium">
                <svg class="w-5 h-5 text-butter-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Home
            </a>

            <!-- Production -->
            <div class="nav-group">
                <button class="nav-toggle w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Production
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform nav-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="nav-submenu hidden pl-12 space-y-1 mt-1">
                    <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Raw Material</a>
                    <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Packaging</a>
                    <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Product</a>
                    <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">BOM</a>
                    <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">MRP Process</a>
                </div>
            </div>

            <!-- Procurement -->
            <div class="nav-group">
                <button class="nav-toggle w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Procurement
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform nav-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="nav-submenu hidden pl-12 space-y-1 mt-1">
                    <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Purchasing</a>
                </div>
            </div>

            <!-- Finance -->
            <div class="nav-group">
                <button class="nav-toggle w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Finance
                    </div>
                    <svg class="w-4 h-4 text-gray-400 transition-transform nav-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="nav-submenu hidden pl-12 space-y-1 mt-1">
                    <a href="#" class="block py-2 text-gray-500 hover:text-gray-900 transition-colors">Journal</a>
                </div>
            </div>
        </nav>
    </div>
</aside>

@push('scripts')
<script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function toggleSidebar() {
        sidebar.classList.toggle('-left-64');
        sidebar.classList.toggle('left-0');
        overlay.classList.toggle('hidden');
    }

    mobileMenuBtn?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);

    // Nav group toggles
    document.querySelectorAll('.nav-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const group = this.closest('.nav-group');
            const submenu = group.querySelector('.nav-submenu');
            const arrow = this.querySelector('.nav-arrow');
            
            submenu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        });
    });

    // Auto-expand Production menu by default
    document.addEventListener('DOMContentLoaded', function() {
        const productionGroup = document.querySelector('.nav-group');
        if (productionGroup) {
            const submenu = productionGroup.querySelector('.nav-submenu');
            const arrow = productionGroup.querySelector('.nav-arrow');
            submenu?.classList.remove('hidden');
            arrow?.classList.add('rotate-180');
        }
    });
</script>
@endpush
