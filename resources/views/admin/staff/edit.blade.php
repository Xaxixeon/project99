@extends('layouts.admin')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Edit Staff: {{ $staff->name }}</h1>
            <a href="{{ route('admin.staff.index') }}" class="text-sm text-slate-600 dark:text-slate-300 hover:underline">
                ← Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.staff.update', $staff->id) }}"
              class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 p-6 rounded-xl shadow space-y-4">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">Kode Staff</label>
                    <input type="text" name="staff_code" value="{{ old('staff_code', $staff->staff_code) }}"
                           class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-800 dark:text-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $staff->name) }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-800 dark:text-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">Username</label>
                    <input type="text" name="username" value="{{ old('username', $staff->username) }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-800 dark:text-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">Email</label>
                    <input type="email" name="email" value="{{ old('email', $staff->email) }}" required
                           class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-800 dark:text-slate-100">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">Role</label>
                <select name="role" class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-800 dark:text-slate-100">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected($staff->roles->pluck('name')->contains($role->name))>
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">Status</label>
                <select name="is_active" class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-800 dark:text-slate-100">
                    <option value="1" @selected($staff->is_active ?? true)>Aktif</option>
                    <option value="0" @selected(!($staff->is_active ?? true))>Nonaktif</option>
                </select>
            </div>

            <div class="border-t border-gray-200 dark:border-slate-700 pt-4 mt-4">
                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">Reset Password (opsional)</label>
                <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm bg-white dark:bg-slate-800 border-gray-300 dark:border-slate-700 text-slate-800 dark:text-slate-100"
                       placeholder="Kosongkan jika tidak diubah">
            </div>

            <div class="pt-4 flex justify-end gap-2">
                <a href="{{ route('admin.staff.index') }}" class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-slate-700 text-slate-700 dark:text-slate-200">Batal</a>
                <button class="px-4 py-2 text-sm rounded-lg bg-blue-600 hover:bg-blue-500 text-white">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
@endsection
