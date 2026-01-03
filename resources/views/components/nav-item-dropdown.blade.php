@props(["active" => false, "icon" => "heroicon-o-list-bullet", "title" => ""])

@php
    $buttonClasses =
        $active ?? false
            ? "nav-toggle flex w-full items-center justify-between gap-3 rounded-xl bg-orange-100 px-4 py-3 text-orange-600 transition-colors"
            : "nav-toggle flex w-full items-center justify-between gap-3 rounded-xl px-4 py-3 text-gray-700 transition-colors hover:bg-gray-50";
    $submenuClasses = $active ? "nav-submenu mt-1 space-y-1 pl-12" : "nav-submenu mt-1 hidden space-y-1 pl-12";
    $arrowClasses = $active
        ? "nav-arrow h-4 w-4 rotate-180 text-gray-400 transition-transform"
        : "nav-arrow h-4 w-4 text-gray-400 transition-transform";
@endphp

<div class="nav-group">
    <button {{ $attributes->merge(["class" => $buttonClasses]) }}>
        <div class="flex items-center gap-3">
            @svg($icon, "h-5 w-5 text-orange-400")
            <span class="font-medium">{{ $title }}</span>
        </div>
        <x-heroicon-o-chevron-down class="{{ $arrowClasses }}" />
    </button>
    <div class="{{ $submenuClasses }}">
        {{ $slot }}
    </div>
</div>
