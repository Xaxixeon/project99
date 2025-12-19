<x-admin.layout title="Produk">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold">Manajemen Produk</h1>
            <p class="text-xs text-slate-500 mt-1">
                Atur produk, varian, dan etalase untuk katalog & landing page.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <form method="get" class="hidden md:block">
                <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama / SKU"
                    class="rounded-full border border-slate-300 bg-white px-3 py-2 text-sm w-56">
            </form>
            <a href="{{ route('admin.products.create') }}"
                class="px-3 py-2 rounded-full bg-sky-600 text-xs font-semibold text-white hover:bg-sky-500">
                + Produk Baru
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr class="text-xs text-slate-500">
                    <th class="px-3 py-2 text-left">Produk</th>
                    <th class="px-3 py-2 text-left">SKU</th>
                    <th class="px-3 py-2 text-left">Etalase</th>
                    <th class="px-3 py-2 text-left">Varian</th>
                    <th class="px-3 py-2 text-left">Mulai dari</th>
                    <th class="px-3 py-2 text-center">Status</th>
                    <th class="px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b last:border-0 border-slate-100">
                        <td class="px-3 py-2 align-top">
                            <div class="text-sm font-semibold text-slate-800">
                                {{ $product->name }}
                            </div>
                            <div class="text-[11px] text-slate-500 line-clamp-2">
                                {{ $product->short_description ?? \Illuminate\Support\Str::limit($product->description, 60) }}
                            </div>
                        </td>
                        <td class="px-3 py-2 align-top text-xs text-slate-600">
                            {{ $product->sku ?? '—' }}
                        </td>
                        <td class="px-3 py-2 align-top text-xs">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($product->displayGroups as $group)
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-[10px]">
                                        {{ $group->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-3 py-2 align-top text-xs text-slate-600">
                            {{ $product->variants->count() }} varian
                        </td>
                        <td class="px-3 py-2 align-top text-xs text-sky-700 font-semibold">
                            @if ($product->starting_price)
                                Rp {{ number_format($product->starting_price, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top text-center">
                            @if ($product->is_active)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px]">
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px]">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-3 py-2 align-top text-right text-xs">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-sky-600 hover:underline">Edit</a>

                            <form action="{{ route('admin.products.destroy', $product) }}" method="post"
                                class="inline-block ml-2" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-4 text-center text-xs text-slate-500">
                            Belum ada produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</x-admin.layout>
