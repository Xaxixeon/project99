@props([
    'type' => 'primary',
])

@php
    $colors = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 text-white',
        'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white',
        'outline' => 'border border-gray-400 text-gray-700 hover:bg-gray-100',
    ];

    $style = $colors[$type] ?? $colors['primary'];
@endphp

<button {{ $attributes->merge(['class' => "px-4 py-2 rounded-md font-semibold $style"]) }}>
    {{ $slot }}
</button>
