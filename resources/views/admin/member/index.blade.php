@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Jenis Member</h1>
        <a href="{{ route('admin.member.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">Tambah Member
            Type</a>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Kode</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Diskon (%)</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($types as $t)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $t->code }}</td>
                        <td class="p-3">{{ $t->name ?? $t->label }}</td>
                        <td class="p-3">{{ $t->discount_percent ?? $t->default_discount }}%</td>

                        <td class="p-3 text-right space-x-2">
                            <a href="{{ route('admin.member.edit', $t->id) }}"
                                class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</a>

                            <form method="POST" action="{{ route('admin.member.destroy', $t->id) }}" class="inline">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded"
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
