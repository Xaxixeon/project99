<x-admin.layout>

<h1 class="text-2xl font-bold mb-6">Tambah Produk</h1>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
      class="bg-gray-800 p-6 rounded space-y-4">
    @csrf

    <div>
        <label class="block">Nama Produk</label>
        <input type="text" name="name" class="w-full p-2 rounded bg-gray-700" required>
    </div>

    <div>
        <label class="block">SKU</label>
        <input type="text" name="sku" class="w-full p-2 rounded bg-gray-700" required>
    </div>

    <div>
        <label class="block">Harga Dasar</label>
        <input type="number" name="base_price" class="w-full p-2 rounded bg-gray-700" required>
    </div>

    <div>
        <label class="block">Kategori (opsional)</label>
        <input type="text" name="category" class="w-full p-2 rounded bg-gray-700">
    </div>

    <div>
        <label class="block">Deskripsi</label>
        <textarea name="description" rows="4"
                  class="w-full p-2 rounded bg-gray-700"></textarea>
    </div>

    <div>
        <label class="block">Thumbnail</label>
        <input type="file" name="thumbnail" class="w-full p-2 rounded bg-gray-700">
    </div>

    <button class="bg-indigo-600 px-4 py-2 rounded">Simpan</button>
</form>

</x-admin.layout>
