@extends('layouts.app')
@section('content')
<h2 class="text-2xl font-bold mb-4">Dashboard Saya</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div class="bg-white p-4 rounded-xl shadow">
    <h3 class="font-semibold">Pesanan Terbaru</h3>
    <ul class="mt-3 space-y-3">
      @foreach(auth()->user()->orders()->latest()->take(10)->get() as $o)
        <li class="border p-3 rounded flex justify-between">
          <div>
            <div class="font-semibold">{{ $o->order_code }}</div>
            <div class="text-sm text-gray-500">{{ $o->status }}</div>
          </div>
          <div class="text-right">
            <div class="font-semibold">Rp {{ number_format($o->total,0,',','.') }}</div>
            <div class="text-sm text-gray-500">{{ $o->created_at->diffForHumans() }}</div>
          </div>
        </li>
      @endforeach
    </ul>
  </div>

  <div class="bg-white p-4 rounded-xl shadow">
    <h3 class="font-semibold">Quick Actions</h3>
    <div class="mt-3 space-y-2">
      <a href="{{ route('order.create') }}" class="block bg-blue-600 text-white p-2 rounded">Buat Pesanan Baru</a>
      <a href="{{ route('products.index') }}" class="block border p-2 rounded">Lihat Produk</a>
    </div>
  </div>
</div>
@endsection

Route::get('/dashboard/customer', [App\Http\Controllers\DashboardController::class, 'customer'])->middleware('auth')->name('dashboard.customer');
