@extends('layouts.admin')

@section('content')
    <div class="p-6 space-y-6">

        <h1 class="text-3xl font-bold">Pengaturan Harga Khusus</h1>
        <p class="text-gray-600">Atur harga khusus berdasarkan Member, Instansi, dan Customer.</p>

        {{-- Member Pricing --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-3">Harga Khusus Member</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach ($memberTypes as $type)
                    <a href="{{ route('admin.pricing.member', $type->code) }}"
                        class="p-4 border rounded-lg hover:bg-gray-50 shadow-sm">
                        <h3 class="font-semibold">{{ strtoupper($type->code) }}</h3>
                        <p class="text-sm text-gray-500">{{ $type->description }}</p>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Instansi Pricing --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-3">Harga Khusus Instansi</h2>
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="p-3">Nama Instansi</th>
                        <th class="p-3">Jumlah Produk Khusus</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instansi as $ins)
                        <tr class="border-b">
                            <td class="p-3">{{ $ins->name }}</td>
                            <td class="p-3">{{ $ins->prices->count() }} produk</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('admin.pricing.instansi', $ins->id) }}"
                                    class="px-3 py-2 bg-blue-600 text-white rounded-md">
                                    Kelola Harga
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Customer Pricing --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-3">Harga Khusus Customer</h2>
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Member</th>
                        <th class="p-3">Harga Khusus</th>
                        <th class="p-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $cust)
                        <tr class="border-b">
                            <td class="p-3">{{ $cust->name }}</td>
                            <td class="p-3">{{ $cust->member_type }}</td>
                            <td class="p-3">{{ $cust->prices->count() }} produk</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('admin.pricing.customer', $cust->id) }}"
                                    class="px-3 py-2 bg-blue-600 text-white rounded-md">
                                    Kelola Harga
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
