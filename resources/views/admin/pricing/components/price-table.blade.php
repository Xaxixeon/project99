<div class="overflow-hidden rounded-xl border border-slate-800">
    <table class="w-full text-sm">
        <thead class="bg-slate-800 text-slate-200">
            <tr class="border-b border-slate-700">
                <th class="p-3 text-left">Produk</th>
                <th class="p-3 text-left">Harga Default</th>
                <th class="p-3 text-left">Harga Khusus</th>
            </tr>
        </thead>

        <tbody class="bg-slate-900/60">
            @foreach ($products as $product)
                @php
                    $price = ($prices instanceof \Illuminate\Support\Collection)
                        ? optional($prices->firstWhere('product_id', $product->id))->price
                        : null;
                @endphp
                <tr class="border-b border-slate-800 hover:bg-slate-800/60 transition">
                    <td class="p-3 text-slate-100">{{ $product->name }}</td>

                    <td class="p-3 text-slate-300">
                        Rp {{ number_format($product->base_price ?? 0) }}
                    </td>

                    <td class="p-3">
                        <input type="number" step="100" name="prices[{{ $product->id }}]" value="{{ $price }}"
                               class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                               placeholder="Isi harga khusus">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
