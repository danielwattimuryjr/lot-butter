@props(["active" => false, "icon" => "heroicon-o-list-bullet", "title" => ""])

@php
    $classes =
        $active ?? false
            ? "flex items-center gap-3 rounded-xl bg-orange-100 px-4 py-3 text-orange-600"
            : "flex items-center gap-3 rounded-xl px-4 py-3 text-gray-700 transition-colors hover:bg-orange-50";
@endphp

<a {{ $attributes->merge(["class" => $classes]) }}>
    @svg($icon, "h-5 w-5 text-orange-400")
    <span class="font-medium">{{ $title }}</span>
</a>
