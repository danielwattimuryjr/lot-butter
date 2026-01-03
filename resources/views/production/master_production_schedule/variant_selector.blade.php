@extends("layouts.dashboard")

@section("title", "$product->name - Select Variant")

@section("content")
    <div class="space-y-6">
        <!-- Breadcrumb Navigation -->
        <div class="flex items-center gap-2 text-sm">
            <a
                href="{{ route("employee.production.master-production-schedules.index") }}"
                class="text-gray-500 transition-colors hover:text-gray-900"
            >
                Master Production Schedule
            </a>
            <x-heroicon-o-chevron-right class="h-4 w-4 text-gray-400" />
            <span class="font-medium text-gray-900">{{ $product->name }}</span>
        </div>

        <!-- Page Title -->
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="mt-1 text-sm text-gray-600">Select a variant to view its production schedule</p>
            <div class="mt-2 border-b border-gray-200"></div>
        </div>

        <!-- Variants List -->
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Available Variants</h2>
            <div class="space-y-3">
                @forelse ($product->variants as $variant)
                    <a
                        href="{{ route("employee.production.master-production-schedules.variant", [$product, $variant]) }}"
                        class="block rounded-lg border border-gray-200 p-4 transition-all hover:border-butter-400 hover:bg-butter-50"
                    >
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100">
                                    <x-heroicon-o-cube class="h-6 w-6 text-purple-600" />
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $variant->name }}</h3>
                                    <div class="mt-1 flex items-center gap-4 text-sm text-gray-500">
                                        <span class="inline-flex items-center gap-1">
                                            <x-heroicon-o-tag class="h-4 w-4" />
                                            {{ $variant->number }} pieces per pack
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <x-heroicon-o-currency-dollar class="h-4 w-4" />
                                            Rp{{ number_format($variant->price, 0, ",", ".") }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <x-heroicon-o-chevron-right class="h-5 w-5 text-gray-400" />
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center py-12">
                        <div class="rounded-full bg-gray-100 p-4">
                            <x-heroicon-o-cube class="h-12 w-12 text-gray-400" />
                        </div>
                        <p class="mt-4 text-sm font-medium text-gray-900">No variants found</p>
                        <p class="mt-1 text-sm text-gray-500">This product doesn't have any variants yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
