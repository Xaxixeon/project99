<x-admin.layout>

<h1 class="text-2xl font-bold mb-6">Daftar Finishing Cetak</h1>

<a href="{{ route('admin.finishings.create') }}"
   class="bg-indigo-600 text-white px-4 py-2 rounded mb-4 inline-block">
    + Tambah Finishing
</a>

@if (session('success'))
<div class="bg-green-600 text-white p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<table class="w-full bg-gray-800 rounded shadow text-left">
    <thead>
        <tr class="border-b border-gray-700">
            <th class="p-3">Nama</th>
            <th class="p-3">Kode</th>
            <th class="p-3">Harga / m2</th>
            <th class="p-3">Flat Fee</th>
            <th class="p-3">Status</th>
            <th class="p-3 w-32">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($finishings as $finishing)
        <tr class="border-b border-gray-700">
            <td class="p-3 font-semibold">{{ $finishing->name }}</td>
            <td class="p-3 text-sm text-gray-300">{{ $finishing->code ?? '-' }}</td>
            <td class="p-3">Rp {{ number_format($finishing->price_per_m2) }}</td>
            <td class="p-3">Rp {{ number_format($finishing->flat_fee) }}</td>
            <td class="p-3">
                @if($finishing->is_active)
                    <span class="px-2 py-1 text-xs rounded bg-green-700 text-white">Aktif</span>
                @else
                    <span class="px-2 py-1 text-xs rounded bg-gray-600 text-white">Nonaktif</span>
                @endif
            </td>
            <td class="p-3 space-x-2">
                <a href="{{ route('admin.finishings.edit', $finishing) }}"
                   class="bg-yellow-500 px-3 py-1 rounded text-black">
                    Edit
                </a>
                <form action="{{ route('admin.finishings.destroy', $finishing) }}" method="POST"
                      class="inline" onsubmit="return confirm('Hapus finishing?')">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-600 px-3 py-1 rounded">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="p-3 text-center text-gray-400">Belum ada data finishing.</td>
        </tr>
        @endforelse
    </tbody>
</table>

</x-admin.layout>
