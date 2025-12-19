@extends('layouts.app')
@section('content')
@php
$order = \App\Models\Order::find(request('order_id'));
@endphp
<div class="bg-white p-6 rounded-xl shadow">
  <h2 class="text-xl font-bold mb-4">Checkout</h2>
  @if(!$order) <p>Order tidak ditemukan.</p> @else
    <div class="space-y-2">
      <div><strong>Order Code:</strong> {{ $order->order_code }}</div>
      <div><strong>Total:</strong> Rp {{ number_format($order->total,0,',','.') }}</div>
      <a href="#" class="mt-4 inline-block bg-green-600 text-white px-4 py-2 rounded">Bayar (Mock)</a>
    </div>
  @endif
</div>
@endsection

Route::get('/checkout', function(){ return view('checkout'); })->name('checkout');