@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Harga Khusus Instansi: {{ $instansi->name }}</h1>

            <a href="{{ route('admin.pricing.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">
                Kembali
            </a>
        </div>

        <form action="" method="POST">
            @csrf

            @include('admin.pricing.components.price-table', [
                'products' => $products,
                'prices' => $prices,
            ])

            <button class="mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg">
                Simpan Perubahan
            </button>
        </form>

    </div>
@endsection
