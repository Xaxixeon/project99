<x-guest-layout>
    <div class="max-w-3xl mx-auto mt-10 bg-slate-900 text-white rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Buat Order Manual</h2>

        <p class="text-sm text-slate-400 mb-4">
            Form ini rute lama. Untuk pengalaman terbaik, sebaiknya order melalui
            katalog produk, lalu gunakan tombol <strong>Order Cepat</strong> di halaman produk.
        </p>

        @php
            $productId = request('product');
        @endphp

        <form method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-sm mb-1">Produk (SKU / ID)</label>
                <input type="text" name="product_id" value="{{ $productId }}"
                    class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Nama Customer</label>
                <input type="text" name="customer_name"
                    class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" name="customer_email"
                        class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">WhatsApp</label>
                    <input type="text" name="customer_phone"
                        class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm mb-1">Lebar (cm)</label>
                    <input type="number" step="0.1" name="width_cm"
                        class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Tinggi (cm)</label>
                    <input type="number" step="0.1" name="height_cm"
                        class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm">
                </div>
                <div>
                    <label class="block text-sm mb-1">Qty</label>
                    <input type="number" name="qty" value="1" min="1"
                        class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Catatan / Brief</label>
                <textarea name="note" rows="3" class="w-full px-3 py-2 rounded bg-slate-800 border border-slate-700 text-sm"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm mb-1">Upload Desain (opsional)</label>
                <input type="file" name="design_file" class="w-full text-sm text-slate-300">
            </div>

            <button class="px-4 py-2 rounded bg-emerald-600 text-sm font-semibold">
                Buat Order
            </button>
        </form>
    </div>
</x-guest-layout>
