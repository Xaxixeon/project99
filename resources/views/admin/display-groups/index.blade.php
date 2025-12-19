<x-admin.layout title="Etalase & Landing">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Etalase & Katalog Publik</h1>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Form buat / edit etalase --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-slate-900/80 border border-slate-700 rounded-2xl p-4">
                <h2 class="text-sm font-semibold mb-3">Tambah Etalase</h2>
                <form action="{{ route('admin.display-groups.store') }}" method="post" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-xs text-slate-300">Nama</label>
                        <input name="name" class="w-full mt-1 rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-sm" placeholder="Best Seller / Promo / Baru">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-slate-300">Warna Badge</label>
                            <select name="badge_color" class="w-full mt-1 rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-xs">
                                <option value="sky">Biru (default)</option>
                                <option value="emerald">Hijau</option>
                                <option value="amber">Kuning</option>
                                <option value="rose">Merah</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-slate-300">Urutan</label>
                            <input type="number" name="sort_order" value="0" class="w-full mt-1 rounded-lg bg-slate-950 border border-slate-700 px-3 py-2 text-xs">
                        </div>
                    </div>
                    <label class="inline-flex items-center gap-2 text-xs text-slate-300">
                        <input type="checkbox" name="show_on_landing" value="1" checked class="rounded border-slate-600 bg-slate-950">
                        Tampilkan di landing page
                    </label>

                    <button class="mt-2 inline-flex px-4 py-2 rounded-lg bg-sky-500 text-slate-950 text-xs font-semibold hover:bg-sky-400">
                        Simpan Etalase
                    </button>
                </form>
            </div>

            {{-- List etalase + assign produk singkat --}}
            @foreach($groups as $group)
                <div class="bg-slate-900/80 border border-slate-700 rounded-2xl p-4">
                    <form action="{{ route('admin.display-groups.update', $group) }}" method="post">
                        @csrf @method('PUT')
                        <div class="flex items-center justify-between mb-2">
                            <input name="name" value="{{ $group->name }}" class="bg-transparent text-sm font-semibold">
                            <button formaction="{{ route('admin.display-groups.destroy', $group) }}"
                                    formmethod="POST"
                                    onclick="return confirm('Hapus etalase ini?')"
                                    class="text-xs text-rose-400">
                                Hapus
                            </button>
                        </div>
                        <input type="hidden" name="badge_color" value="{{ $group->badge_color }}">
                        <input type="hidden" name="sort_order" value="{{ $group->sort_order }}">

                        <label class="inline-flex items-center gap-2 text-xs text-slate-300 mb-2">
                            <input type="checkbox" name="show_on_landing" value="1"
                                   {{ $group->show_on_landing ? 'checked' : '' }}
                                   class="rounded border-slate-600 bg-slate-950">
                            Tampil di landing
                        </label>

                        <div class="text-[11px] text-slate-400 mb-1">Produk di etalase ini:</div>
                        <select name="product_ids[]" multiple size="4"
                                class="w-full rounded-lg bg-slate-950 border border-slate-700 text-xs">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ $group->products->contains($product->id) ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <button class="mt-2 inline-flex px-3 py-1.5 rounded-lg bg-sky-600 text-xs font-semibold">
                            Simpan
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Preview + drag sort --}}
        <div class="lg:col-span-2 bg-slate-900/80 border border-slate-700 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-semibold">Preview Landing (Etalase & Produk)</h2>
                <form action="{{ route('admin.display-groups.sort') }}" method="post" id="sort-form" class="hidden">
                    @csrf
                    <input type="hidden" name="order" id="sort-order">
                </form>
                <button type="button"
                        onclick="submitSortOrder()"
                        class="text-xs px-3 py-1.5 rounded bg-sky-600 text-white">
                    Simpan Urutan
                </button>
            </div>

            <div class="grid md:grid-cols-2 gap-4 text-xs">
                <div class="bg-slate-950/80 border border-slate-800 rounded-xl p-3">
                    <div class="font-semibold text-sm mb-2">Drag & Drop Urutan Etalase</div>
                    <div id="dg-list" class="space-y-2">
                        @foreach($groups as $group)
                            <div class="border border-slate-700 rounded-lg p-2 flex items-center justify-between cursor-move bg-slate-900"
                                 draggable="true"
                                 data-id="{{ $group->id }}"
                                 ondragstart="handleDragStart(event)"
                                 ondragover="handleDragOver(event)"
                                 ondrop="handleDrop(event)">
                                <span class="text-[11px] text-slate-200">{{ $group->name }}</span>
                                <span class="text-[10px] text-slate-500">#{{ $group->sort_order }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-slate-500 mt-2">Urutan ini menentukan tampilan etalase di landing.</p>
                </div>

                <div class="grid gap-3">
                    @foreach($groups as $group)
                        @if(!$group->show_on_landing) @continue @endif
                        <div class="bg-slate-950/80 border border-slate-800 rounded-xl p-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-semibold text-sm">{{ $group->name }}</div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-sky-500/10 text-sky-300 border border-sky-500/40">
                                    Etalase
                                </span>
                            </div>
                            <div class="flex flex-col gap-2">
                                @forelse($group->products->take(3) as $product)
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="truncate">{{ $product->name }}</span>
                                        <span class="font-semibold text-sky-300">
                                            {{ $product->starting_price ? 'Mulai ' . number_format($product->starting_price,0,',','.') : 'Harga variatif' }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-slate-500 text-[11px]">Belum ada produk.</div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        let dragSrcEl = null;

        function handleDragStart(e) {
            dragSrcEl = e.currentTarget;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.currentTarget.dataset.id);
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        }

        function handleDrop(e) {
            e.stopPropagation();
            const target = e.currentTarget;
            if (dragSrcEl === target) return;

            const list = document.getElementById('dg-list');
            const srcIndex = Array.from(list.children).indexOf(dragSrcEl);
            const tgtIndex = Array.from(list.children).indexOf(target);

            if (srcIndex < tgtIndex) {
                list.insertBefore(dragSrcEl, target.nextSibling);
            } else {
                list.insertBefore(dragSrcEl, target);
            }
            return false;
        }

        function submitSortOrder() {
            const list = document.getElementById('dg-list');
            const ids = Array.from(list.children).map(el => el.dataset.id);
            document.getElementById('sort-order').value = JSON.stringify(ids);
            document.getElementById('sort-form').submit();
        }
    </script>
</x-admin.layout>
