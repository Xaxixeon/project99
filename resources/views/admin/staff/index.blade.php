{{-- resources/views/admin/staff/index.blade.php --}}
<x-admin.layout>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-white">Manajemen Staff</h2>
            <p class="text-sm text-slate-400 mt-1">
                Kelola akun staff, role, dan status aktif/nonaktif.
            </p>
        </div>

        <a href="{{ route('admin.staff.create') }}"
           class="inline-flex items-center px-4 py-2 rounded bg-emerald-600 text-white text-sm hover:bg-emerald-500">
            + Tambah Staff
        </a>
    </div>

    <div class="bg-slate-900 rounded-lg shadow p-4">
        <table class="min-w-full text-sm text-left text-slate-200">
            <thead>
                <tr class="border-b border-slate-700 text-xs uppercase text-slate-400">
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Role</th>
                    <th class="px-3 py-2 text-center">Status</th>
                    <th class="px-3 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffUsers as $user)
                    <tr class="border-b border-slate-800 hover:bg-slate-800/60">
                        <td class="px-3 py-2">
                            <div class="font-semibold">{{ $user->name }}</div>
                            @if($user->username)
                                <div class="text-xs text-slate-400">
                                    {{ '@'.$user->username }}
                                </div>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-slate-200">
                            {{ $user->email }}
                        </td>
                        <td class="px-3 py-2 text-slate-200">
                            @php
                                $roleNames = $user->roles?->pluck('name')->toArray() ?? [];
                            @endphp

                            @if(!empty($roleNames))
                                {{ implode(', ', $roleNames) }}
                            @else
                                <span class="text-xs text-slate-500">-</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @php
                                $isActive = $user->is_active ?? true;
                            @endphp

                            @if($isActive)
                                <span class="px-2 py-1 rounded-full bg-emerald-600/80 text-xs text-white">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-600/80 text-xs text-white">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            <div class="inline-flex gap-1">

                                {{-- Edit --}}
                                <a href="{{ route('admin.staff.edit', $user->id) }}"
                                   class="px-2 py-1 rounded bg-sky-600 text-xs text-white hover:bg-sky-500">
                                    Edit
                                </a>

                                {{-- Toggle aktif/nonaktif --}}
                                <form action="{{ route('admin.staff.toggle', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Ubah status staff ini?')">
                                    @csrf
                                    <button type="submit"
                                            class="px-2 py-1 rounded bg-slate-700 text-xs text-white hover:bg-slate-600">
                                        {{ $isActive ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                {{-- Reset password --}}
                                <form action="{{ route('admin.staff.reset-password', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Reset password untuk {{ $user->name }}?')">
                                    @csrf
                                    <button type="submit"
                                            class="px-2 py-1 rounded bg-amber-600 text-xs text-white hover:bg-amber-500">
                                        Reset PW
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.staff.destroy', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus staff ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-2 py-1 rounded bg-red-600 text-xs text-white hover:bg-red-500">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-4 text-center text-slate-500">
                            Belum ada data staff.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-admin.layout>
