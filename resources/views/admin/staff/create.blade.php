@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Tambah Staff</h1>
            <a href="{{ route('admin.staff.index') }}" class="text-sm text-gray-600 hover:underline">
                ← Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.staff.store') }}" class="bg-white p-6 rounded-xl shadow space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Kode Staff (ID Karyawan)</label>
                    <input type="text" name="staff_code" value="{{ old('staff_code') }}"
                        class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="contoh: 0001021">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Password</label>
                    <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-500 mt-1">
                        Bisa di-set default seperti <code>password</code>, nanti staff diminta ganti.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Role</label>
                <select name="role" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Status</label>
                <select name="is_active" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end gap-2">
                <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 text-sm rounded-lg border">Batal</a>
                <button class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white">
                    Simpan Staff
                </button>
            </div>
        </form>
    </div>
@endsection
