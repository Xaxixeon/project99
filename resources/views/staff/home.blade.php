@extends('layouts.app')

@section('content')
<div class="px-3 py-4 space-y-4">
    <h1 class="text-lg font-semibold">Task Board (Mobile/PWA)</h1>
    @livewire('order-kanban', ['mode' => 'compact'])
</div>
@endsection
