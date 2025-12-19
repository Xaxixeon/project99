@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-8">

        <div>
            <h1 class="text-3xl font-bold">Pengaturan Harga Khusus</h1>
            <p class="text-gray-600">Atur harga khusus berdasarkan Member, Instansi, dan Customer.</p>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow flex items-center gap-4">
                <div class="p-4 bg-blue-100 text-blue-600 rounded-full">
                    dY'Z
                </div>
                <div>
                    <p class="text-gray-500">Jenis Member</p>
                    <p class="text-2xl font-bold">{{ ($memberTypes ?? collect())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow flex items-center gap-4">
                <div class="p-4 bg-green-100 text-green-600 rounded-full">
                    dY?›
                </div>
                <div>
                    <p class="text-gray-500">Instansi</p>
                    <p class="text-2xl font-bold">{{ ($instansi ?? collect())->count() }}</p>
                </div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow flex items-center gap-4">
                <div class="p-4 bg-purple-100 text-purple-600 rounded-full">
                    dY`
                </div>
                <div>
                    <p class="text-gray-500">Customer</p>
                    <p class="text-2xl font-bold">{{ ($customers ?? collect())->count() }}</p>
                </div>
            </div>
        </div>

        {{-- Member Pricing --}}
        <div class="bg-white p-6 rounded-xl shadow space-y-3">
            <h2 class="text-xl font-bold flex items-center gap-2">dY'Z Harga Khusus Member</h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach (($memberTypes ?? []) as $type)
                    @if (Route::has('admin.pricing.member'))
                        <a href="{{ route('admin.pricing.member', $type->code) }}"
                           class="border p-4 rounded-lg hover:bg-gray-50 shadow-sm transition">
                            <h3 class="font-bold text-lg">{{ strtoupper($type->code) }}</h3>
                            <p class="text-gray-600 text-sm">{{ $type->label }}</p>
                            <div class="mt-2 text-sm text-blue-600">Kelola Harga →</div>
                        </a>
                    @else
                        <div class="border p-4 rounded-lg shadow-sm">
                            <h3 class="font-bold text-lg">{{ strtoupper($type->code) }}</h3>
                            <p class="text-gray-600 text-sm">{{ $type->label }}</p>
                            <div class="mt-2 text-sm text-gray-400">Route admin.pricing.member belum tersedia</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Instansi Pricing --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-xl font-bold mb-4">dY?› Harga Khusus Instansi</h2>

            <table class="w-full">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Instansi</th>
                    <th class="p-3 text-left">Jumlah Produk</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
                </thead>

                <tbody>
                    @foreach (($instansi ?? []) as $ins)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $ins->name }}</td>
                            <td class="p-3">{{ $ins->prices->count() }}</td>
                            <td class="p-3 text-right">
                                @if (Route::has('admin.pricing.instansi'))
                                    <a href="{{ route('admin.pricing.instansi', $ins->id) }}"
                                       class="px-3 py-2 bg-blue-600 text-white rounded-md">Kelola</a>
                                @else
                                    <span class="text-xs text-gray-400">Route belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Customer Pricing --}}
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-xl font-bold mb-4">dY` Harga Khusus Customer</h2>

            <table class="w-full">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Member</th>
                    <th class="p-3 text-left">Jumlah Harga</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
                </thead>

                <tbody>
                    @foreach (($customers ?? []) as $cust)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $cust->name }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs">
                                    {{ strtoupper($cust->member_type) }}
                                </span>
                            </td>
                            <td class="p-3">{{ $cust->prices->count() }} produk</td>
                            <td class="p-3 text-right">
                                @if (Route::has('admin.pricing.customer'))
                                    <a href="{{ route('admin.pricing.customer', $cust->id) }}"
                                       class="px-3 py-2 bg-blue-600 text-white rounded-md">Kelola</a>
                                @else
                                    <span class="text-xs text-gray-400">Route belum tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
