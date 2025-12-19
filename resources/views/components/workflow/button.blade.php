@props(['action','label','color'=>'blue'])

<form method="POST" action="{{ $action }}" class="inline">
    @csrf
    <button class="px-3 py-1 rounded bg-{{ $color }}-600 text-white hover:bg-{{ $color }}-700 text-sm">
        {{ $label }}
    </button>
</form>
