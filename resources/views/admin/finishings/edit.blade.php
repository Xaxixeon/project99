<x-admin.layout>

<h1 class="text-2xl font-bold mb-6">Edit Finishing</h1>

<form action="{{ route('admin.finishings.update', $finishing) }}" method="POST"
      class="bg-gray-800 p-6 rounded space-y-4">
    @csrf
    @method('PUT')

    <div>
        <label class="block">Nama Finishing</label>
        <input type="text" name="name" class="w-full p-2 rounded bg-gray-700" required
               value="{{ old('name', $finishing->name) }}">
    </div>

    <div>
        <label class="block">Kode (opsional)</label>
        <input type="text" name="code" class="w-full p-2 rounded bg-gray-700"
               value="{{ old('code', $finishing->code) }}">
    </div>

    <div>
        <label class="block">Harga per m2</label>
        <input type="number" name="price_per_m2" class="w-full p-2 rounded bg-gray-700" required min="0"
               value="{{ old('price_per_m2', $finishing->price_per_m2) }}">
    </div>

    <div>
        <label class="block">Flat Fee</label>
        <input type="number" name="flat_fee" class="w-full p-2 rounded bg-gray-700" required min="0"
               value="{{ old('flat_fee', $finishing->flat_fee) }}">
    </div>

    <div>
        <label class="block">Deskripsi</label>
        <textarea name="description" rows="4"
                  class="w-full p-2 rounded bg-gray-700">{{ old('description', $finishing->description) }}</textarea>
    </div>

    <div class="flex items-center space-x-2">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               class="rounded border-gray-600" {{ old('is_active', $finishing->is_active) ? 'checked' : '' }}>
        <label for="is_active">Aktif</label>
    </div>

    <button class="bg-indigo-600 px-4 py-2 rounded">Update</button>
</form>

</x-admin.layout>
