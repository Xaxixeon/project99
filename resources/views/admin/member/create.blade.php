@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Member Type</h1>

    <form action="{{ route('admin.member.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow space-y-4">
        @csrf

        <div>
            <label class="font-semibold">Kode</label>
            <input type="text" name="code" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label class="font-semibold">Nama / Label</label>
            <input type="text" name="name" class="w-full border p-2 rounded" required>
            <input type="text" name="label" class="w-full border p-2 rounded mt-2" placeholder="Label (opsional)">
        </div>

        <div>
            <label class="font-semibold">Diskon (%)</label>
            <input type="number" name="discount_percent" class="w-full border p-2 rounded" min="0" max="100" value="0">
        </div>

        <div>
            <label class="font-semibold">Deskripsi</label>
            <textarea name="description" class="w-full border p-2 rounded" rows="3"></textarea>
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
    </form>
@endsection
