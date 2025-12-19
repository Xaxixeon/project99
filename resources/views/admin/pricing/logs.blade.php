@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Riwayat Perubahan Harga</h1>

    <div class="bg-white p-6 rounded-xl shadow">

        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Produk</th>
                    <th class="p-3">Harga Lama</th>
                    <th class="p-3">Harga Baru</th>
                    <th class="p-3">Diubah Oleh</th>
                    <th class="p-3">Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($logs as $log)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $log->product->name }}</td>
                        <td class="p-3 text-red-600">Rp {{ number_format($log->old_price) }}</td>
                        <td class="p-3 text-green-600">Rp {{ number_format($log->new_price) }}</td>
                        <td class="p-3">{{ $log->editor->name }}</td>
                        <td class="p-3">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>
@endsection
