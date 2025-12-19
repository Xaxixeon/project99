@props(['name'])

@php
    $iconPath = resource_path('icons/' . $name . '.svg');
@endphp

@if(file_exists($iconPath))
    @php
        $svg = file_get_contents($iconPath);
        $attr = $attributes->merge(['class' => $attributes->get('class')])->toHtml();
        $svg = preg_replace('/<svg\\b([^>]*)>/', '<svg $1 ' . $attr . '>', $svg, 1);
        echo $svg;
    @endphp
@else
@switch($name)
    @case('home')
        <svg {{ $attributes->merge(['class' => $attributes->get('class')]) }} fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h5a1 1 0 001-1V14a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 001 1h5a1 1 0 001-1V10" />
        </svg>
        @break

    @case('users')
        <svg {{ $attributes->merge(['class' => $attributes->get('class')]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path d="M17 20v-2a4 4 0 00-3-3.87M7 20v-2a4 4 0 013-3.87M12 4a4 4 0 110 8 4 4 0 010-8z"/>
        </svg>
        @break

    @case('user-group')
        <svg {{ $attributes->merge(['class' => $attributes->get('class')]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path d="M17 20v-2a4 4 0 00-3-3.87M7 20v-2a4 4 0 013-3.87M12 4a4 4 0 110 8 4 4 0 010-8z"/>
        </svg>
        @break

    @case('office-building')
        <svg {{ $attributes->merge(['class' => $attributes->get('class')]) }} fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M3 21V3h7v18m0-10h11v10H10z"/>
        </svg>
        @break

    @case('identification')
        <svg {{ $attributes->merge(['class' => $attributes->get('class')]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path d="M15 11.5a3 3 0 10-6 0M12 14v7m9-7v7H3v-7"/>
        </svg>
        @break

    @case('currency-dollar')
        <svg {{ $attributes->merge(['class' => $attributes->get('class')]) }} fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <path d="M12 8c-4 0-4 6 0 6s4 6 0 6m0-20v4m0 12v4"/>
        </svg>
        @break

    @case('chip')
        <svg {{ $attributes->merge(['class' => $attributes->get('class')]) }} fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
            <path d="M9 9h6v6H9zM4 4h16v16H4z"/>
        </svg>
        @break
@endswitch
@endif
