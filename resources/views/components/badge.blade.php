@props(['color' => 'gray'])

@php
    $colors = [
        'gray' => 'bg-gray-200 text-gray-700',
        'blue' => 'bg-blue-200 text-blue-700',
        'green' => 'bg-green-200 text-green-700',
        'yellow' => 'bg-yellow-200 text-yellow-700',
        'red' => 'bg-red-200 text-red-700',
        'purple' => 'bg-purple-200 text-purple-700',
    ];

    $style = $colors[$color] ?? $colors['gray'];
@endphp

<span class="px-3 py-1 rounded-full text-xs font-semibold {{ $style }}">
    {{ $slot }}
</span>
