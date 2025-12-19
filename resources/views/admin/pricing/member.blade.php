@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">
                Harga Member: {{ strtoupper($type) }}
            </h1>

            <a href="{{ route('admin.pricing.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">
                Kembali
            </a>
        </div>

        {{-- Price Table --}}
        <form action="" method="POST">
            @csrf

            @include('admin.pricing.components.price-table', [
                'products' => $products,
                'prices' => $prices,
            ])

            <button type="submit" class="mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg">
                Simpan Perubahan
            </button>
        </form>

    </div>
@endsection
