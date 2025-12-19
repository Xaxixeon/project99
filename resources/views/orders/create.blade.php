<x-admin.layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Buat Purchase Order</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Input data pesanan, sistem akan membuat tugas otomatis.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('orders.store') }}" class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl p-6 space-y-4">
        @csrf

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Pelanggan</label>
                <select name="customer_id" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
                    <option value="">-- Pilih customer --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? '-' }})</option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-500 mt-1">Kosongkan jika pelanggan baru, isi nama/telepon di bawah.</p>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Nama baru</label>
                    <input type="text" name="customer_name" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" placeholder="Nama customer">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Telepon baru</label>
                    <input type="text" name="customer_phone" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" placeholder="08xxx">
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Jenis Produk *</label>
                <input type="text" name="product_type" required class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" placeholder="Banner, Stiker, Brosur...">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Ukuran</label>
                <input type="text" name="size" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" placeholder="contoh: 3x1 m">
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Bahan</label>
                <input type="text" name="material" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" placeholder="Flexi 300, Artpaper 150, dst">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Finishing</label>
                <input type="text" name="finishing" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" placeholder="Laminasi, cutting, eyelet...">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Jumlah *</label>
                <input type="number" min="1" name="quantity" required class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" value="1">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 items-center">
            <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                <input type="checkbox" name="need_design" value="1" class="rounded border-slate-400 dark:border-slate-600 dark:bg-slate-800">
                Perlu dibuatkan desain?
            </label>
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Deadline</label>
                <input type="date" name="deadline" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200 mb-1">Catatan</label>
            <textarea name="notes" rows="3" class="w-full rounded-lg border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm px-3 py-2" placeholder="Instruksi khusus, warna tertentu, revisi teks, dsb."></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('orders.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-200">Batal</a>
            <button class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500">Simpan Order</button>
        </div>
    </form>
</x-admin.layout>
