<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Produk – Xeon Digital Printing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-50 antialiased">

<header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-sky-500 text-slate-950 font-bold">
                X
            </div>
            <div class="leading-tight">
                <div class="font-semibold text-xl">Xeon Digital Printing</div>
                <div class="text-sm text-slate-400">
                    Katalog Produk
                </div>
            </div>
        </a>

        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="hidden md:inline-block text-base text-slate-300 hover:text-sky-400">
                Beranda
            </a>
            <a href="{{ route('customer.login') }}"
               class="px-4 py-2 rounded-full bg-slate-900 border border-slate-700 text-base font-semibold hover:border-sky-400 hover:text-sky-300 transition">
                Login Customer
            </a>
        </div>
    </div>
</header>

<main class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-950 to-slate-900">
    <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-8">

        {{-- Search & filter --}}
        <section class="bg-slate-900/80 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold">Katalog Produk</h1>
                    <p class="text-base md:text-lg text-slate-300">
                        Cari produk, filter etalase, dan atur tampilan grid atau list.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('products.index', array_merge(request()->all(), ['view' => 'grid'])) }}"
                       class="px-3 py-1.5 rounded-full border {{ $viewMode === 'grid' ? 'border-sky-500 text-sky-300' : 'border-slate-700 text-slate-300' }} text-xs">
                        Grid
                    </a>
                    <a href="{{ route('products.index', array_merge(request()->all(), ['view' => 'list'])) }}"
                       class="px-3 py-1.5 rounded-full border {{ $viewMode === 'list' ? 'border-sky-500 text-sky-300' : 'border-slate-700 text-slate-300' }} text-xs">
                        List
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('products.index') }}"
                  class="mt-4 grid md:grid-cols-4 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-sm text-slate-400 mb-1">Cari produk / SKU</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-4 py-3 text-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                           placeholder="Contoh: brosur, banner, poster, BROSUR-A4">
                </div>

                <div>
                    <label class="block text-sm text-slate-400 mb-1">Etalase</label>
                    <select name="display_group"
                            class="w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Semua</option>
                        @foreach($displayGroups as $group)
                            <option value="{{ $group->id }}" @selected(request('display_group') == $group->id)>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Harga min</label>
                        <input type="number" name="price_min" value="{{ request('price_min') }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Harga max</label>
                        <input type="number" name="price_max" value="{{ request('price_max') }}"
                               class="w-full rounded-2xl border border-slate-700 bg-slate-950/70 px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                    </div>
                </div>

                <div class="flex gap-3 md:col-span-4">
                    <button type="submit"
                            class="px-6 py-3 rounded-2xl bg-sky-500 text-slate-950 text-lg font-semibold hover:bg-sky-400 transition">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-3 rounded-2xl border border-slate-700 text-lg text-slate-200 hover:border-sky-400 hover:text-sky-300 transition">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- List/Grid produk --}}
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold">Daftar Produk</h2>
                @if($products->count())
                    <span class="text-base text-slate-400">
                        Menampilkan {{ $products->count() }} produk
                    </span>
                @endif
            </div>

            @if($viewMode === 'list')
                <div class="space-y-3">
                    @forelse($products as $product)
                        <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-4 flex flex-col md:flex-row gap-4 hover:border-sky-500/60 hover:shadow-xl hover:shadow-sky-500/10 transition">
                            <div class="flex-1 space-y-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($product->displayGroups as $group)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] border border-sky-500/40 text-sky-300">
                                            {{ $group->name }}
                                        </span>
                                    @endforeach
                                </div>
                                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                                <p class="text-sm text-slate-300 line-clamp-3">
                                    {{ $product->short_description ?? $product->description }}
                                </p>
                                @if($product->variants->count())
                                    <div class="flex flex-wrap gap-1 text-[11px] text-slate-400">
                                        @foreach($product->variants->take(6) as $variant)
                                            <span class="px-2 py-0.5 rounded-full bg-slate-800 border border-slate-700">
                                                {{ $variant->size_code ?: $variant->label }}
                                            </span>
                                        @endforeach
                                        @if($product->variants->count() > 6)
                                            <span class="text-[11px] text-slate-500">+{{ $product->variants->count() - 6 }} varian</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex md:flex-col justify-between items-end md:items-end gap-3">
                                <div class="text-right">
                                    <div class="text-[11px] text-slate-400">Mulai dari</div>
                                    <div class="text-base font-bold text-sky-400">
                                        @if($product->starting_price)
                                            Rp {{ number_format($product->starting_price, 0, ',', '.') }}
                                        @else
                                            Harga variatif
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('product.show', $product->sku) }}"
                                   class="text-xs px-3 py-1.5 rounded-full bg-sky-500 text-slate-950 font-semibold hover:bg-sky-400">
                                    Detail & Order
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-lg text-slate-300">
                            Belum ada produk yang terdaftar.
                        </div>
                    @endforelse
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @forelse($products as $product)
                        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-4 flex flex-col justify-between hover:border-sky-500/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-sky-500/10 transition">
                            <div>
                                {{-- badges etalase --}}
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($product->displayGroups as $group)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] border border-sky-500/40 text-sky-300">
                                            {{ $group->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <h3 class="text-sm font-semibold mb-1">{{ $product->name }}</h3>
                                <p class="text-xs text-slate-300 line-clamp-3 mb-2">
                                    {{ $product->short_description ?? $product->description }}
                                </p>

                                {{-- varian ringkas --}}
                                @if($product->variants->count())
                                    <div class="flex flex-wrap gap-1 mb-2 text-[11px] text-slate-400">
                                        @foreach($product->variants->take(3) as $variant)
                                            <span class="px-2 py-0.5 rounded-full bg-slate-800 border border-slate-700">
                                                {{ $variant->size_code ?: $variant->label }}
                                            </span>
                                        @endforeach
                                        @if($product->variants->count() > 3)
                                            <span class="text-[11px] text-slate-500">+{{ $product->variants->count() - 3 }} varian</span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="mt-3 flex items-center justify-between">
                                <div>
                                    <div class="text-[11px] text-slate-400">Mulai dari</div>
                                    <div class="text-base font-bold text-sky-400">
                                        @if($product->starting_price)
                                            Rp {{ number_format($product->starting_price, 0, ',', '.') }}
                                        @else
                                            Harga variatif
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('product.show', $product->sku) }}"
                                   class="text-xs px-3 py-1.5 rounded-full bg-sky-500 text-slate-950 font-semibold hover:bg-sky-400">
                                    Detail & Order
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-lg text-slate-300">
                            Belum ada produk yang terdaftar.
                        </div>
                    @endforelse
                </div>
            @endif

            @if(method_exists($products, 'links'))
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        </section>
    </div>
</main>

</body>
</html>
