@props([
    'title' => 'Title',
    'value' => '0',
    'bg' => 'blue',   {{-- warna: blue, green, red, yellow, purple, gray --}}
    'icon' => null
])

@php
    // Map warna Tailwind berdasarkan parameter bg=""
    $colors = [
        'blue'   => 'bg-blue-100 text-blue-700 border-blue-300 dark:bg-blue-900/30 dark:text-blue-200 dark:border-blue-700/60',
        'green'  => 'bg-green-100 text-green-700 border-green-300 dark:bg-green-900/30 dark:text-green-200 dark:border-green-700/60',
        'red'    => 'bg-red-100 text-red-700 border-red-300 dark:bg-red-900/30 dark:text-red-200 dark:border-red-700/60',
        'yellow' => 'bg-yellow-100 text-yellow-700 border-yellow-300 dark:bg-yellow-900/30 dark:text-yellow-100 dark:border-yellow-700/60',
        'purple' => 'bg-purple-100 text-purple-700 border-purple-300 dark:bg-purple-900/30 dark:text-purple-200 dark:border-purple-700/60',
        'gray'   => 'bg-gray-100 text-gray-700 border-gray-300 dark:bg-gray-800/50 dark:text-gray-200 dark:border-gray-700/60',
    ];

    $colorClass = $colors[$bg] ?? $colors['blue'];
@endphp

<div class="p-5 rounded-xl shadow bg-white border border-gray-200 dark:bg-slate-900 dark:border-slate-700 dark:text-slate-100">
    <div class="flex items-center justify-between">
        
        <div>
            <p class="text-gray-500 dark:text-slate-400 text-sm">{{ $title }}</p>
            <h2 class="text-2xl font-bold mt-1 text-slate-900 dark:text-slate-100">{{ $value }}</h2>
        </div>

        @if($icon)
            <div class="p-3 rounded-full {{ $colorClass }}">
                {!! $icon !!}
            </div>
        @endif

    </div>
</div>
