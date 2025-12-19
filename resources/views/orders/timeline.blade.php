@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-4">
        Timeline Order #{{ $order->order_code ?? $order->order_no ?? $order->id }}
    </h1>

    @livewire('order-timeline', ['order' => $order])
</div>
@endsection
