<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} – Xeon Digital Printing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-50 antialiased">

<header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ route('products.index') }}" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-sky-500 text-slate-950 font-bold">
                X
            </div>
            <div class="leading-tight">
                <div class="font-semibold text-xl">Xeon Digital Printing</div>
                <div class="text-sm text-slate-400">
                    Detail Produk
                </div>
            </div>
        </a>

        <a href="{{ route('products.index') }}"
           class="px-4 py-2 rounded-full border border-slate-700 text-base text-slate-200 hover:border-sky-400 hover:text-sky-300 transition">
            Kembali ke Katalog
        </a>
    </div>
</header>

<main class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-950 to-slate-900">
    <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-8">

        {{-- Breadcrumb --}}
        <nav class="text-base text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-sky-300">Beranda</a>
            <span class="mx-1">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-sky-300">Katalog</a>
            <span class="mx-1">/</span>
            <span class="text-slate-300">{{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-8 items-start">
            {{-- Detail produk --}}
            <section class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-xl space-y-4">
                <h1 class="text-3xl md:text-4xl font-extrabold">
                    {{ $product->name }}
                </h1>
                <p class="text-base text-slate-400">
                    SKU: <span class="font-mono text-slate-300">{{ $product->sku }}</span>
                </p>

                <div class="h-px bg-slate-800 my-2"></div>

                <p class="text-base md:text-lg text-slate-200 leading-relaxed">
                    {{ $product->description }}
                </p>

                @if(!empty($product->notes))
                    <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-4 mt-2">
                        <h2 class="text-xl font-semibold mb-1">Catatan</h2>
                        <p class="text-base md:text-lg text-slate-200">
                            {{ $product->notes }}
                        </p>
                    </div>
                @endif

                <div class="mt-4 space-y-2">
                    <div class="text-base text-slate-400">Harga mulai dari:</div>
                    <div class="text-2xl font-bold text-sky-300">
                        {{ $product->display_price ?? 'Harga variatif sesuai ukuran & bahan' }}
                    </div>
                </div>
            </section>

            {{-- Order cepat --}}
            <section id="order" class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-xl">
                <h2 class="text-2xl font-semibold mb-3">Order Cepat</h2>
                <p class="text-lg text-slate-300 mb-4">
                    Form ini bisa digunakan customer langsung, atau CS saat menerima order via telepon/WhatsApp.
                </p>

                <form method="POST" action="{{ route('order.store') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <div>
                        <label class="block text-base text-slate-300 mb-1">Nama Customer</label>
                        <input type="text" name="customer_name"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                               placeholder="Nama pemesan">
                    </div>

                    <div>
                        <label class="block text-base text-slate-300 mb-1">Kontak (HP / WhatsApp)</label>
                        <input type="text" name="customer_phone"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                               placeholder="08xx-xxxx-xxxx">
                    </div>

                    {{-- VARIAN PRODUK --}}
                    <div class="space-y-1">
                        <label for="variant_id" class="text-xs font-semibold">Pilih varian</label>
                            <select id="variant_id" name="variant_id"
                                    class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                                @foreach($product->variants as $variant)
                                    <option
                                        value="{{ $variant->id }}"
                                    data-price="{{ $variant->priceForCustomer($customer ?? null) }}"
                                    data-width="{{ $variant->width_cm ?? 0 }}"
                                    data-height="{{ $variant->height_cm ?? 0 }}"
                                >
                                    {{ $variant->label }}
                                    — Rp {{ number_format($variant->priceForCustomer($customer ?? null), 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500">
                            Untuk banner / ukuran custom, isi panjang & lebar di bawah (cm).
                        </p>
                    </div>

                    {{-- INPUT UKURAN CUSTOM (opsional, terutama banner) --}}
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-semibold">Lebar (cm)</label>
                            <input type="number" step="0.1" min="0" id="width_cm" name="width_cm"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm"
                                   placeholder="mis. 90">
                        </div>
                        <div>
                            <label class="text-xs font-semibold">Tinggi (cm)</label>
                            <input type="number" step="0.1" min="0" id="height_cm" name="height_cm"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm"
                                   placeholder="mis. 200">
                        </div>
                    </div>

                    {{-- TAMBAHAN / FINISHING --}}
                    @if($product->addons->count())
                        <div class="mt-4 space-y-1">
                            <div class="text-xs font-semibold">Tambahan / Finishing</div>
                            @foreach($product->addons as $addon)
                                <label class="flex items-center gap-2 text-xs text-slate-800">
                                    <input type="checkbox"
                                           name="addon_ids[]"
                                           value="{{ $addon->id }}"
                                           data-extra-price="{{ $addon->extra_price }}"
                                           class="rounded border-slate-400">
                                    <span>{{ $addon->name }}</span>
                                    @if($addon->extra_price > 0)
                                        <span class="text-sky-600">
                                            + Rp {{ number_format($addon->extra_price,0,',','.') }}
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    @endif

                    {{-- QTY & HARGA ESTIMASI --}}
                    <div class="mt-4 grid grid-cols-2 gap-3 items-end">
                        <div>
                            <label class="text-xs font-semibold">Jumlah</label>
                            <input type="number" min="1" value="1" id="qty" name="qty"
                                   class="mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm">
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-slate-600">Estimasi Total</div>
                            <div id="estimate-display"
                                 class="mt-1 text-lg font-bold text-sky-700">
                                Rp 0
                            </div>
                            <input type="hidden" id="estimated_total_price" name="estimated_total_price" value="0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-base text-slate-300 mb-1">Catatan / Brief Desain</label>
                        <textarea name="notes" rows="3"
                                  class="w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                                  placeholder="Tulis kebutuhan desain, ukuran khusus, atau deadline di sini"></textarea>
                    </div>

                    {{-- Estimasi harga (opsional) --}}
                    <div class="bg-slate-800/70 border border-slate-700 rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-base text-slate-300">Estimasi harga</span>
                            <span id="price-estimate" class="text-2xl font-bold text-sky-300">
                                Rp 0
                            </span>
                        </div>
                        <p class="mt-1 text-base text-slate-400">
                            Estimasi ini bersifat perkiraan. Harga final akan dikonfirmasi oleh admin sebelum produksi.
                        </p>
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 pt-2">
                        <button type="submit"
                                class="flex-1 px-5 py-3 rounded-2xl bg-sky-500 text-slate-950 text-lg font-semibold hover:bg-sky-400 transition">
                            Kirim Permintaan Order
                        </button>
                        <a href="https://wa.me/62xxxxxxxxxx"
                           class="flex-1 px-5 py-3 rounded-2xl border border-slate-700 text-lg text-slate-200 text-center hover:border-sky-400 hover:text-sky-300 transition">
                            Tanya via WhatsApp
                        </a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>

{{-- Estimator sederhana (opsional) --}}
<script>
    function xeonFormatRupiah(number) {
        return 'Rp ' + (number || 0).toLocaleString('id-ID');
    }

    function xeonRecalcEstimate() {
        const variantSelect = document.getElementById('variant_id');
        if (!variantSelect) return;

        const selectedOption = variantSelect.options[variantSelect.selectedIndex];
        const basePrice = parseFloat(selectedOption.dataset.price || '0');

        const widthInput  = document.getElementById('width_cm');
        const heightInput = document.getElementById('height_cm');
        const qtyInput    = document.getElementById('qty');

        const width  = parseFloat(widthInput?.value || '0');
        const height = parseFloat(heightInput?.value || '0');
        const qty    = parseInt(qtyInput?.value || '1', 10);

        let baseSubtotal = 0;
        if (width > 0 && height > 0) {
            const areaM2 = (width * height) / 10000;
            baseSubtotal = basePrice * areaM2 * qty;
        } else {
            baseSubtotal = basePrice * qty;
        }

        let addonSubtotalPerUnit = 0;
        document.querySelectorAll('input[name="addon_ids[]"]:checked')
            .forEach((cb) => {
                const extra = parseFloat(cb.dataset.extraPrice || '0');
                addonSubtotalPerUnit += extra;
            });

        const totalAddon = addonSubtotalPerUnit * qty;
        const grandTotal = Math.round(baseSubtotal + totalAddon);

        const displayEl = document.getElementById('estimate-display');
        const hiddenEl  = document.getElementById('estimated_total_price');

        if (displayEl) displayEl.textContent = xeonFormatRupiah(grandTotal);
        if (hiddenEl) hiddenEl.value = grandTotal;
    }

    document.addEventListener('DOMContentLoaded', () => {
        ['variant_id', 'width_cm', 'height_cm', 'qty'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', xeonRecalcEstimate);
            if (el) el.addEventListener('change', xeonRecalcEstimate);
        });

        document.querySelectorAll('input[name="addon_ids[]"]').forEach(cb => {
            cb.addEventListener('change', xeonRecalcEstimate);
        });

        xeonRecalcEstimate();
    });
</script>

</body>
</html>
