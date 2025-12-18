<div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-8">
    @if (request()->routeIs('dashboard'))    
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">
                Hi, {{ auth()->user()->name ?? 'User' }}!
            </h1>
            <p class="text-butter-500 font-medium mt-1">{{ auth()->user()->hasRole('admin') ? 'Admin' : auth()->user()->team->name }}</p>
            <p class="text-gray-600 mt-2">
                We're excited to have you here. Let's make today productive and smooth.
            </p>
        </div>
    @else
        &nbsp;
    @endif
    
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200">
            <img 
                src="{{ $userAvatar ?? asset('images/avatar.jpg') }}" 
                alt="User Avatar"
                class="w-full h-full object-cover"
            >
        </div>
        <button class="text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>
</div>
