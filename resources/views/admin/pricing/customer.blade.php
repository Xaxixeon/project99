@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6 text-slate-100">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <p class="text-xs text-slate-400">Customer Pricing</p>
                <h1 class="text-2xl font-bold">
                    Harga Customer: {{ $customer->name }}
                </h1>
            </div>

            <a href="{{ route('admin.pricing.index') }}"
               class="px-4 py-2 rounded-lg bg-slate-800 border border-slate-600 text-sm hover:bg-slate-700">
                Kembali
            </a>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow p-4">
            <form action="{{ route('admin.pricing.customer.update', $customer) }}" method="POST" class="space-y-4">
                @csrf

                @include('admin.pricing.components.price-table', [
                    'products' => $products,
                    'prices' => $prices ?? $specials ?? [],
                ])

                <div class="flex justify-end">
                    <button class="px-5 py-2 rounded-full bg-sky-600 text-sm font-semibold text-white hover:bg-sky-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
