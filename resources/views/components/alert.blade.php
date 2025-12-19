@props(['type' => 'info'])

@php
    $styles = [
        'success' => 'bg-green-100 border-green-400 text-green-800',
        'error' => 'bg-red-100 border-red-400 text-red-800',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
        'info' => 'bg-blue-100 border-blue-400 text-blue-800',
    ];

    $style = $styles[$type] ?? $styles['info'];
@endphp

<div class="border-l-4 p-4 rounded {{ $style }}">
    {{ $slot }}
</div>
