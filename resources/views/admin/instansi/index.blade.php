@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Daftar Instansi</h1>
        <a href="{{ route('admin.instansi.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">Tambah Instansi</a>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Kontak</th>
                    <th class="p-3 text-left">Alamat</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($instansi as $i)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $i->name }}</td>
                        <td class="p-3">{{ $i->contact ?? '-' }}</td>
                        <td class="p-3">{{ $i->address ?? '-' }}</td>
                        <td class="p-3 text-right space-x-2">
                            <a href="{{ route('admin.instansi.edit', $i->id) }}"
                                class="px-3 py-2 bg-yellow-500 text-white rounded-md">Edit</a>

                            <form action="{{ route('admin.instansi.destroy', $i->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="px-3 py-2 bg-red-600 text-white rounded-md"
                                    onclick="return confirm('Anda yakin?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
