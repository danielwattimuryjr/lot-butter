@extends("layouts.dashboard")

@section("title", "$product->name - MRP Overview")

@section("content")
    <div class="space-y-6">
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm">
            <a
                href="{{ route("employee.production.mrp.index") }}"
                class="text-gray-500 transition-colors hover:text-gray-900"
            >
                Material Requirements Planning
            </a>
            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
            <span class="font-medium text-gray-900">{{ $product->name }}</span>
        </div>

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">MRP: {{ $product->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Select a level to view detailed MRP calculations</p>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Level 0 - Product Variants -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                    <span class="text-lg font-bold text-blue-600">0</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Level 0 - Product Variants</h2>
                    <p class="text-sm text-gray-500">Gross Requirement from MPS per variant</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse ($product->variants as $variant)
                    <a
                        href="{{ route("employee.production.mrp.level0", [$product, $variant]) }}"
                        class="block rounded-lg border border-gray-200 p-4 transition-all hover:border-blue-400 hover:bg-blue-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                    <x-heroicon-o-cube class="h-5 w-5 text-gray-600" />
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $variant->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $variant->number }} pcs per variant</p>
                                </div>
                            </div>
                            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
                        </div>
                    </a>
                @empty
                    <p class="py-4 text-center text-sm text-gray-500">No variants available</p>
                @endforelse
            </div>
        </div>

        <!-- Level 1 - Components -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                    <span class="text-lg font-bold text-green-600">1</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Level 1 - Components</h2>
                    <p class="text-sm text-gray-500">Components from Product BOM and Variant BOM</p>
                </div>
            </div>

            <!-- Product-Level Components -->
            @if ($productLevel1Components->isNotEmpty())
                <div class="mb-4">
                    <h3 class="mb-2 text-sm font-medium text-gray-700">Product Components (Aggregate)</h3>
                    <p class="mb-3 text-xs text-gray-500">Gross Req = SUM(MPS all variants) × BOM Quantity</p>
                    <div class="space-y-2">
                        @foreach ($productLevel1Components as $component)
                            <a
                                href="{{ route("employee.production.mrp.level1-product", [$product, $component]) }}"
                                class="block rounded-lg border border-gray-200 p-3 transition-all hover:border-green-400 hover:bg-green-50"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100">
                                            <x-heroicon-o-cube class="h-4 w-4 text-gray-600" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $component->name }}
                                            </h4>
                                            <p class="text-xs text-gray-500">{{ $component->unit }}</p>
                                        </div>
                                    </div>
                                    <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Variant-Specific Components -->
            @if (count($variantLevel1Components) > 0)
                <div>
                    <h3 class="mb-2 text-sm font-medium text-gray-700">Variant-Specific Components</h3>
                    <p class="mb-3 text-xs text-gray-500">Gross Req = MPS of specific variant × BOM Quantity</p>
                    <div class="space-y-4">
                        @foreach ($variantLevel1Components as $variantData)
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <h4 class="mb-2 text-sm font-semibold text-gray-900">
                                    {{ $variantData["variant"]->name }}
                                </h4>
                                <div class="space-y-2">
                                    @foreach ($variantData["components"] as $component)
                                        <a
                                            href="{{ route("employee.production.mrp.level1-variant", [$product, $variantData["variant"], $component]) }}"
                                            class="block rounded-lg border border-white bg-white p-3 transition-all hover:border-green-400 hover:bg-green-50"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100"
                                                    >
                                                        <x-heroicon-o-cube class="h-4 w-4 text-gray-600" />
                                                    </div>
                                                    <div>
                                                        <h5 class="text-sm font-medium text-gray-900">
                                                            {{ $component->name }}
                                                        </h5>
                                                        <p class="text-xs text-gray-500">{{ $component->unit }}</p>
                                                    </div>
                                                </div>
                                                <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($productLevel1Components->isEmpty() && count($variantLevel1Components) == 0)
                <p class="py-4 text-center text-sm text-gray-500">No Level 1 components found</p>
            @endif
        </div>

        <!-- Level 2 - Raw Materials -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100">
                    <span class="text-lg font-bold text-orange-600">2</span>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Level 2 - Raw Materials</h2>
                    <p class="text-sm text-gray-500">Gross Requirement = SUM(MPS all variants) × BOM Quantity</p>
                </div>
            </div>
            <div class="space-y-3">
                @forelse ($level2Components as $component)
                    <a
                        href="{{ route("employee.production.mrp.level2", [$product, $component]) }}"
                        class="block rounded-lg border border-gray-200 p-4 transition-all hover:border-orange-400 hover:bg-orange-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                    <x-heroicon-o-wrench-screwdriver class="h-5 w-5 text-gray-600" />
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $component->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $component->unit }}</p>
                                </div>
                            </div>
                            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
                        </div>
                    </a>
                @empty
                    <p class="py-4 text-center text-sm text-gray-500">No Level 2 components in BOM</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
