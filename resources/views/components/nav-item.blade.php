@props(['active' => false, 'icon' => 'heroicon-o-list-bullet', 'title' => ''])

@php
    $classes = ($active ?? false)
                ? "flex items-center gap-3 px-4 py-3 bg-orange-100 text-orange-600 rounded-xl"
                : "flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-orange-50 rounded-xl transition-colors"
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @svg($icon, 'w-5 h-5 text-orange-400')
    <span class="font-medium">{{ $title }}</span>
</a>