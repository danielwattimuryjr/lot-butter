<div class="mb-8 flex flex-col justify-between gap-4 lg:flex-row lg:items-start">
    @if (request()->routeIs("dashboard"))
        <div>
            <h1 class="text-2xl font-bold text-gray-900 lg:text-3xl">
                Hi, {{ auth()->user()->employee ? auth()->user()->employee->name : auth()->user()->name }}!
            </h1>
            <p class="mt-1 font-medium text-butter-500">
                {{ auth()->user()->hasRole("admin") ? "Admin" : auth()->user()->team->name }}
            </p>
            <p class="mt-2 text-gray-600">We're excited to have you here. Let's make today productive and smooth.</p>
        </div>
    @else
        &nbsp;
    @endif

    <div class="flex items-center gap-3">
        <div class="h-12 w-12 overflow-hidden rounded-full bg-gray-200">
            <img src="{{ asset("images/avatar.jpeg") }}" alt="User Avatar" class="h-full w-full object-cover" />
        </div>
        <button class="text-gray-400 hover:text-gray-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>
</div>
