<x-admin.layout>

<h1 class="text-2xl font-bold mb-6">Tambah Material Cetak</h1>

<form action="{{ route('admin.materials.store') }}" method="POST"
      class="bg-gray-800 p-6 rounded space-y-4">
    @csrf

    <div>
        <label class="block">Nama Material</label>
        <input type="text" name="name" class="w-full p-2 rounded bg-gray-700" required
               value="{{ old('name') }}">
    </div>

    <div>
        <label class="block">Kode (opsional)</label>
        <input type="text" name="code" class="w-full p-2 rounded bg-gray-700"
               value="{{ old('code') }}">
    </div>

    <div>
        <label class="block">Harga per m2</label>
        <input type="number" name="price_per_m2" class="w-full p-2 rounded bg-gray-700" required min="0"
               value="{{ old('price_per_m2', 0) }}">
    </div>

    <div>
        <label class="block">Deskripsi</label>
        <textarea name="description" rows="4"
                  class="w-full p-2 rounded bg-gray-700">{{ old('description') }}</textarea>
    </div>

    <div class="flex items-center space-x-2">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               class="rounded border-gray-600" {{ old('is_active', true) ? 'checked' : '' }}>
        <label for="is_active">Aktif</label>
    </div>

    <button class="bg-indigo-600 px-4 py-2 rounded">Simpan</button>
</form>

</x-admin.layout>
