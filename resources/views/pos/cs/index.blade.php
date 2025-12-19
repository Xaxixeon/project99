@php use Illuminate\Support\Str; @endphp
<x-admin.layout title="POS Customer Service">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">POS / Kasir Customer Service</h1>
            <p class="text-xs text-slate-500 mt-1">
                Buat order cepat dari walk-in / chat, pilih produk, varian, ukuran, dan finishing.
                Sistem akan otomatis hitung harga & buat invoice.
            </p>
        </div>
        <form method="get" class="w-full max-w-xs">
            <input type="text" name="q" value="{{ $search }}"
                   placeholder="Cari produk / SKU..."
                   class="w-full rounded-full border border-slate-300 bg-white px-3 py-2 text-sm">
        </form>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Katalog produk --}}
        <div class="lg:col-span-2">
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($products as $product)
                    <div class="bg-white border border-slate-200 rounded-2xl p-3 flex flex-col justify-between shadow-sm">
                        <div>
                            <div class="flex flex-wrap gap-1 mb-1">
                                @foreach($product->displayGroups as $group)
                                    <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-[10px]">
                                        {{ $group->name }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="text-sm font-semibold mb-1">{{ $product->name }}</div>
                            <div class="text-[11px] text-slate-500 mb-2">
                                {{ $product->short_description ?? Str::limit($product->description, 80) }}
                            </div>

                            @if($product->variants->count())
                                <div class="flex flex-wrap gap-1 mb-2 text-[11px] text-slate-600">
                                    @foreach($product->variants->take(3) as $variant)
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200">
                                            {{ $variant->size_code ?: $variant->label }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="mt-2 flex items-center justify-between">
                            <div>
                                <div class="text-[11px] text-slate-500">Mulai dari</div>
                                <div class="text-base font-bold text-sky-700">
                                    @if($product->starting_price)
                                        Rp {{ number_format($product->starting_price,0,',','.') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <button type="button"
                                    class="px-3 py-1.5 rounded-full bg-sky-600 text-xs font-semibold text-white hover:bg-sky-500"
                                    onclick='xeonPosOpenProduct(@json($product))'>
                                Tambah
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Keranjang / order summary --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
            <h2 class="text-sm font-semibold mb-3">Ringkasan Order</h2>

            <form method="post" action="{{ route('cs.pos.order.store') }}" id="pos-form" class="space-y-3">
                @csrf

                <div>
                    <label class="text-xs font-semibold">Customer</label>
                    <select name="customer_id"
                            class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                        <option value="">Pilih customer...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="pos-items" class="space-y-2 text-xs">
                    {{-- diisi via JS --}}
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs pt-2">
                    <div>
                        <label class="font-semibold">Service Fee (%)</label>
                        <input type="number" step="0.1" min="0" name="service_fee_percent" id="service_fee_percent"
                               class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                               value="0" oninput="xeonPosRenderItems()">
                    </div>
                    <div>
                        <label class="font-semibold">Tax (%)</label>
                        <input type="number" step="0.1" min="0" name="tax_percent" id="tax_percent"
                               class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                               value="0" oninput="xeonPosRenderItems()">
                    </div>
                    <div>
                        <label class="font-semibold">Packing Fee</label>
                        <input type="number" step="0.1" min="0" name="packing_fee" id="packing_fee"
                               class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                               value="0" oninput="xeonPosRenderItems()">
                    </div>
                    <div>
                        <label class="font-semibold">Delivery Fee</label>
                        <input type="number" step="0.1" min="0" name="delivery_fee" id="delivery_fee"
                               class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                               value="0" oninput="xeonPosRenderItems()">
                    </div>
                    <div>
                        <label class="font-semibold">Rush Fee</label>
                        <input type="number" step="0.1" min="0" name="rush_order_fee" id="rush_order_fee"
                               class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                               value="0" oninput="xeonPosRenderItems()">
                    </div>
                </div>

                <div class="border-t border-slate-200 pt-3">
                    <label class="text-xs font-semibold">Metode Pembayaran</label>
                    <select name="payment_method" class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm"
                            onchange="xeonPosRenderItems()">
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer</option>
                        <option value="qris">QRIS</option>
                        <option value="tempo">Tempo / Jatuh Tempo</option>
                        <option value="company_billing">Company Billing</option>
                    </select>
                    <label class="text-xs font-semibold mt-2 block">Jatuh Tempo (opsional)</label>
                    <input type="date" name="due_date"
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                </div>

                <div class="border-t border-slate-200 pt-3 mt-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="text-xs text-slate-600 space-y-1">
                            <div>Subtotal: <span id="pos-subtotal">Rp 0</span></div>
                            <div>Service: <span id="pos-service">Rp 0</span></div>
                            <div>Packing/Delivery/Rush: <span id="pos-flat">Rp 0</span></div>
                            <div>Tax: <span id="pos-tax">Rp 0</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-semibold">Grand Total</div>
                            <div id="pos-total" class="font-bold text-sky-700">Rp 0</div>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="mt-3 w-full px-4 py-2 rounded-full bg-emerald-600 text-sm font-semibold text-white hover:bg-emerald-500">
                    Buat Order & Invoice
                </button>

                <input type="hidden" name="items_json" id="pos-items-json">
            </form>
        </div>
    </div>

    {{-- Modal pilih varian / ukuran / addon --}}
    <div id="pos-modal"
         class="fixed inset-0 z-40 hidden items-center justify-center bg-black/40">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-4">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-semibold" id="pos-modal-title">Tambah produk</h3>
                <button type="button"
                        class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center"
                        onclick="xeonPosCloseModal()">✕</button>
            </div>

            <div id="pos-modal-content" class="space-y-3 text-xs">
                {{-- diisi via JS --}}
            </div>

            <div class="mt-3 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Subtotal: <span id="pos-modal-subtotal">Rp 0</span>
                </div>
                <button type="button"
                        class="px-4 py-1.5 rounded-full bg-sky-600 text-xs font-semibold text-white"
                        onclick="xeonPosConfirmAdd()">Tambah ke Order</button>
            </div>
        </div>
    </div>

    <script>
        let xeonPosState = {
            items: [],
            currentProduct: null,
            currentVariantId: null,
            currentQty: 1,
            currentWidth: null,
            currentHeight: null,
            currentAddonIds: [],
        };

        function xeonFormat(number) {
            return 'Rp ' + (number || 0).toLocaleString('id-ID');
        }

        function xeonPosOpenProduct(product) {
            xeonPosState.currentProduct   = product;
            xeonPosState.currentVariantId = product.variants[0]?.id || null;
            xeonPosState.currentQty       = 1;
            xeonPosState.currentWidth     = null;
            xeonPosState.currentHeight    = null;
            xeonPosState.currentAddonIds  = [];

            const c = document.getElementById('pos-modal-content');
            let html = '';

            html += `<div class="text-sm font-semibold mb-1">${product.name}</div>`;

            html += `<div>
                <label class="text-[11px] font-semibold">Varian</label>
                <select id="pos-variant"
                        class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs"
                        onchange="xeonPosUpdateSubtotal()">`;

            product.variants.forEach(v => {
                html += `<option value="${v.id}" data-price="${v.price}">
                    ${v.label} — ${xeonFormat(v.price)}
                </option>`;
            });

            html += `</select></div>`;

            html += `<div class="grid grid-cols-2 gap-2 mt-2">
                <div>
                    <label class="text-[11px] font-semibold">Lebar (cm)</label>
                    <input type="number" min="0" step="0.1" id="pos-width"
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs"
                           placeholder="opsional, khusus banner"
                           oninput="xeonPosUpdateSubtotal()">
                </div>
                <div>
                    <label class="text-[11px] font-semibold">Tinggi (cm)</label>
                    <input type="number" min="0" step="0.1" id="pos-height"
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs"
                           placeholder="opsional, khusus banner"
                           oninput="xeonPosUpdateSubtotal()">
                </div>
            </div>`;

            html += `<div class="grid grid-cols-2 gap-2 mt-2">
                <div>
                    <label class="text-[11px] font-semibold">Qty</label>
                    <input type="number" min="1" value="1"
                           id="pos-qty"
                           class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs"
                           oninput="xeonPosUpdateSubtotal()">
                </div>
            </div>`;

            if (product.addons && product.addons.length) {
                html += `<div class="mt-2">
                    <div class="text-[11px] font-semibold mb-1">Tambahan / Finishing</div>`;
                product.addons.forEach(a => {
                    html += `<label class="flex items-center gap-2 mb-1">
                        <input type="checkbox"
                               class="pos-addon"
                               data-id="${a.id}"
                               data-extra-price="${a.extra_price}"
                               onchange="xeonPosUpdateSubtotal()">
                        <span>${a.name}</span>
                        ${a.extra_price > 0
                            ? `<span class="text-sky-600">+ ${xeonFormat(a.extra_price)}</span>`
                            : ''
                        }
                    </label>`;
                });
                html += `</div>`;
            }

            c.innerHTML = html;

            document.getElementById('pos-modal').classList.remove('hidden');
            document.getElementById('pos-modal').classList.add('flex');

            xeonPosUpdateSubtotal();
        }

        function xeonPosCloseModal() {
            document.getElementById('pos-modal').classList.add('hidden');
            document.getElementById('pos-modal').classList.remove('flex');
        }

        function xeonPosUpdateSubtotal() {
            const product = xeonPosState.currentProduct;
            if (!product) return;

            const variantSelect = document.getElementById('pos-variant');
            const qtyInput      = document.getElementById('pos-qty');
            const widthInput    = document.getElementById('pos-width');
            const heightInput   = document.getElementById('pos-height');
            const addonChecks   = document.querySelectorAll('.pos-addon');

            const variantId = parseInt(variantSelect?.value || '0', 10);
            const price     = parseFloat(variantSelect?.selectedOptions[0]?.dataset.price || '0');
            const qty       = parseInt(qtyInput?.value || '1', 10);
            const width     = parseFloat(widthInput?.value || '0');
            const height    = parseFloat(heightInput?.value || '0');

            xeonPosState.currentVariantId = variantId;
            xeonPosState.currentQty       = qty;
            xeonPosState.currentWidth     = width > 0 && height > 0 ? width : null;
            xeonPosState.currentHeight    = width > 0 && height > 0 ? height : null;

            let baseSubtotal = 0;
            if (width > 0 && height > 0) {
                const areaM2 = (width * height) / 10000;
                baseSubtotal = price * areaM2 * qty;
            } else {
                baseSubtotal = price * qty;
            }

            let addonSubtotalPerUnit = 0;
            let addonIds = [];
            addonChecks.forEach(cb => {
                if (cb.checked) {
                    const extra = parseFloat(cb.dataset.extraPrice || '0');
                    addonSubtotalPerUnit += extra;
                    addonIds.push(parseInt(cb.dataset.id, 10));
                }
            });
            xeonPosState.currentAddonIds = addonIds;

            const totalAddon = addonSubtotalPerUnit * qty;
            const subtotal   = Math.round(baseSubtotal + totalAddon);

            document.getElementById('pos-modal-subtotal').textContent = xeonFormat(subtotal);
        }

        function xeonPosConfirmAdd() {
            const p = xeonPosState.currentProduct;
            const variantId = xeonPosState.currentVariantId;
            const qty = xeonPosState.currentQty;
            const width = xeonPosState.currentWidth;
            const height = xeonPosState.currentHeight;
            const addonIds = xeonPosState.currentAddonIds || [];

            if (!p || !variantId || qty < 1) return;

            const variant = p.variants.find(v => v.id === variantId);
            if (!variant) return;

            const price = variant.price;
            let lineSubtotal = 0;

            if (width && height) {
                const areaM2 = (width * height) / 10000;
                lineSubtotal = price * areaM2 * qty;
            } else {
                lineSubtotal = price * qty;
            }

            let addonTotal = 0;
            addonIds.forEach(id => {
                const a = p.addons.find(x => x.id === id);
                if (a) addonTotal += (a.extra_price || 0) * qty;
            });

            const lineTotal = Math.round(lineSubtotal + addonTotal);

            const item = {
                product_id: p.id,
                product_name: p.name,
                variant_id: variantId,
                variant_label: variant.label,
                qty: qty,
                unit_price: price,
                width_cm: width,
                height_cm: height,
                addon_ids: addonIds,
                line_total: lineTotal,
            };

            xeonPosState.items.push(item);
            xeonPosRenderItems();
            xeonPosCloseModal();
        }

        function xeonPosRenderItems() {
            const container = document.getElementById('pos-items');
            if (!xeonPosState.items.length) {
                container.innerHTML = '<div class="text-[11px] text-slate-500">Belum ada item.</div>';
            } else {
                container.innerHTML = xeonPosState.items.map((it, idx) => `
                    <div class="flex items-center justify-between gap-2 border border-slate-200 rounded-lg px-2 py-2">
                        <div class="flex-1">
                            <div class="text-xs font-semibold">${it.product_name}</div>
                            <div class="text-[11px] text-slate-500">${it.variant_label}</div>
                            <div class="text-[11px] text-slate-500">
                                Qty ${it.qty}
                                ${it.width_cm && it.height_cm
                                    ? ` • ${it.width_cm}×${it.height_cm} cm`
                                    : ''}
                            </div>
                        </div>
                        <div class="text-right text-xs">
                            <div>${xeonFormat(it.unit_price)}</div>
                            <div class="font-semibold">${xeonFormat(it.line_total)}</div>
                            <button type="button"
                                    class="mt-1 text-[11px] text-rose-500"
                                    onclick="xeonPosRemoveItem(${idx})">Hapus</button>
                        </div>
                    </div>
                `).join('');
            }

            const total = xeonPosState.items.reduce((sum, it) => sum + it.line_total, 0);

            const servicePercent = parseFloat(document.getElementById('service_fee_percent')?.value || '0');
            const packingFee     = parseFloat(document.getElementById('packing_fee')?.value || '0');
            const deliveryFee    = parseFloat(document.getElementById('delivery_fee')?.value || '0');
            const rushFee        = parseFloat(document.getElementById('rush_order_fee')?.value || '0');
            const taxPercent     = parseFloat(document.getElementById('tax_percent')?.value || '0');

            const serviceFee = total * (servicePercent / 100);
            const flatFees   = packingFee + deliveryFee + rushFee;
            const preTax     = total + serviceFee + flatFees;
            const taxFee     = preTax * (taxPercent / 100);
            const grandTotal = preTax + taxFee;

            document.getElementById('pos-subtotal').textContent = xeonFormat(total);
            document.getElementById('pos-service').textContent  = xeonFormat(serviceFee);
            document.getElementById('pos-flat').textContent     = xeonFormat(flatFees);
            document.getElementById('pos-tax').textContent      = xeonFormat(taxFee);
            document.getElementById('pos-total').textContent    = xeonFormat(grandTotal);

            const payloadItems = xeonPosState.items.map(it => ({
                variant_id: it.variant_id,
                qty: it.qty,
                width_cm: it.width_cm,
                height_cm: it.height_cm,
                addon_ids: it.addon_ids,
            }));
            document.getElementById('pos-items-json').value = JSON.stringify(payloadItems);
        }

        function xeonPosRemoveItem(index) {
            xeonPosState.items.splice(index, 1);
            xeonPosRenderItems();
        }

        document.getElementById('pos-form').addEventListener('submit', function (e) {
            const json = document.getElementById('pos-items-json').value;
            if (!json || json === '[]') {
                e.preventDefault();
                alert('Belum ada item di order.');
                return;
            }

            const payload = JSON.parse(json);
            payload.forEach((it, index) => {
                const base = `items[${index}]`;
                ['variant_id', 'qty', 'width_cm', 'height_cm'].forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `${base}[${key}]`;
                    input.value = it[key] ?? '';
                    this.appendChild(input);
                });
                if (it.addon_ids && it.addon_ids.length) {
                    it.addon_ids.forEach((aid, j) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `${base}[addon_ids][${j}]`;
                        input.value = aid;
                        this.appendChild(input);
                    });
                }
            });
        });

        xeonPosRenderItems();
    </script>
</x-admin.layout>
