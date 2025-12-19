@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Instansi</h1>

    <form class="bg-white p-6 shadow rounded-xl space-y-4" action="{{ route('admin.instansi.store') }}" method="POST">
        @csrf

        <div>
            <label class="block mb-1 font-semibold">Nama Instansi</label>
            <input type="text" name="name" required class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Kontak</label>
            <input type="text" name="contact" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Alamat</label>
            <textarea name="address" class="w-full border p-2 rounded"></textarea>
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-md">
            Simpan
        </button>
    </form>
@endsection
