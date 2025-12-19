@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Edit Customer: {{ $customer->name }}</h1>
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-600 hover:underline">
                ← Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}"
            class="bg-white p-6 rounded-xl shadow space-y-4">
            @method('PUT')
            @include('admin.customers._form', ['customer' => $customer])

            <div class="border-t pt-4 mt-4">
                <h3 class="text-xl font-semibold mt-2 mb-2 text-gray-900">Special Price per Produk</h3>

                <table class="w-full bg-slate-900 rounded text-sm">
                    <tr class="border-b border-slate-700 text-slate-300">
                        <th class="p-2 text-left">Produk</th>
                        <th class="p-2 text-left">Price / m²</th>
                        <th class="p-2 text-left">Flat Price</th>
                    </tr>
                    @foreach ($products as $product)
                        @php
                            $sp = $customer->specialPrices->firstWhere('product_id', $product->id);
                        @endphp
                        <tr class="border-b border-slate-800">
                            <td class="p-2 text-white">{{ $product->name }}</td>
                            <td class="p-2">
                                <input type="number" name="special[{{ $product->id }}][price_per_m2]"
                                    value="{{ $sp->price_per_m2 ?? '' }}"
                                    class="w-full bg-slate-800 border-slate-600 rounded px-2 py-1 text-white">
                            </td>
                            <td class="p-2">
                                <input type="number" name="special[{{ $product->id }}][flat_price]"
                                    value="{{ $sp->flat_price ?? '' }}"
                                    class="w-full bg-slate-800 border-slate-600 rounded px-2 py-1 text-white">
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="pt-4 flex justify-end gap-2">
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 text-sm rounded-lg border">Batal</a>
                <button class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
@endsection
