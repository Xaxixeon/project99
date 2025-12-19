@props(['type' => 'primary'])

@php
    $palette = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'warning' => 'bg-amber-500 hover:bg-amber-600 text-white focus:ring-amber-500',
        'green'   => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
        'default' => 'bg-gray-200 hover:bg-gray-300 text-gray-800 focus:ring-gray-400',
    ];

    $classes = implode(' ', [
        'inline-flex items-center justify-center px-4 py-2 rounded-md text-sm font-semibold shadow-sm transition',
        'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100',
        $palette[$type] ?? $palette['default'],
    ]);
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
