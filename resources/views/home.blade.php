@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
  <div class="lg:col-span-2">
    <div class="bg-white rounded-2xl p-8 shadow">
      <h1 class="text-3xl font-bold mb-2">Solusi Digital Printing Profesional</h1>
      <p class="text-gray-600 mb-4">Cetak cepat, kualitas tinggi, harga transparan. Hitung harga online & upload file di sini.</p>

      <a href="{{ route('order.create') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-xl shadow hover:bg-blue-700">Buat Pesanan Sekarang</a>
    </div>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
      @foreach($products->take(4) as $p)
        <a href="{{ route('product.show', $p->sku) }}" class="block bg-white rounded-xl shadow p-4 hover:shadow-lg">
          <div class="font-semibold">{{ $p->name }}</div>
          <div class="text-sm text-gray-500">Mulai: Rp {{ number_format($p->base_price,0,',','.') }}</div>
        </a>
      @endforeach
    </div>
  </div>

  <aside class="space-y-4">
    <div class="bg-white p-4 rounded-xl shadow">
      <h4 class="font-semibold">Kenapa pilih kami?</h4>
      <ul class="text-sm text-gray-600 mt-2 space-y-2">
        <li>Harga transparan & kalkulator real-time</li>
        <li>Upload file mudah — PDF, AI, PSD, CDR</li>
        <li>Support custom finishing</li>
      </ul>
    </div>
  </aside>
</div>
@endsection
