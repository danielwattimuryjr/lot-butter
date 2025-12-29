@props(['active' => false, 'icon' => 'heroicon-o-list-bullet', 'title' => ''])

@php
  $buttonClasses = ($active ?? false)
        ? "nav-toggle w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-orange-100 text-orange-600 transition-colors"
        : "nav-toggle w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-700 transition-colors";
@endphp

<div class="nav-group">
  <button {{ $attributes->merge(['class' => $buttonClasses]) }}>
    <div class="flex items-center gap-3">
      @svg($icon, 'w-5 h-5 text-orange-400')
      {{ $title }}
    </div>
    <x-heroicon-o-chevron-down class="w-4 h-4 text-gray-400 transition-transform nav-arrow" />
  </button>
  <div class="nav-submenu hidden pl-12 space-y-1 mt-1">
    {{ $slot }}
  </div>
</div>