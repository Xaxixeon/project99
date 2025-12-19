@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Instansi</h1>

    <form class="bg-white p-6 shadow rounded-xl space-y-4" action="{{ route('admin.instansi.update', $instansi->id) }}"
        method="POST">
        @csrf @method('PUT')

        <div>
            <label class="block mb-1 font-semibold">Nama Instansi</label>
            <input type="text" name="name" value="{{ $instansi->name }}" required class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Kontak</label>
            <input type="text" name="contact" value="{{ $instansi->contact }}" class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="block mb-1 font-semibold">Alamat</label>
            <textarea name="address" class="w-full border p-2 rounded">{{ $instansi->address }}</textarea>
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-md">
            Update
        </button>
    </form>
@endsection
