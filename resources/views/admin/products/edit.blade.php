<x-admin.layout :title="$mode === 'create' ? 'Produk Baru' : 'Edit Produk'">

    <form method="post"
          action="{{ $mode === 'create'
                    ? route('admin.products.store')
                    : route('admin.products.update', $product) }}">
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Kolom kiri: data utama --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-4">
                    <h2 class="text-sm font-semibold mb-3">Informasi Produk</h2>

                    <div class="space-y-3 text-sm">
                        <div>
                            <label class="text-xs font-semibold">Nama Produk</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                   class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                            @error('name')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold">SKU</label>
                                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                       class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                                @error('sku')
                                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center gap-2 mt-5 md:mt-7">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $product->is_active))>
                                <span class="text-xs text-slate-700">Produk aktif</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Deskripsi Singkat</label>
                            <textarea name="short_description" rows="2"
                                      class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('short_description', $product->short_description) }}</textarea>
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Deskripsi Lengkap</label>
                            <textarea name="description" rows="4"
                                      class="mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- VARIAN --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold">Varian Produk</h2>
                        <button type="button"
                                class="text-xs text-sky-600"
                                onclick="xeonAddVariantRow()">
                            + Tambah Varian
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs" id="variants-table">
                            <thead>
                            <tr class="border-b border-slate-200 text-[11px] text-slate-500">
                                <th class="px-2 py-1 text-left">Label</th>
                                <th class="px-2 py-1 text-left">Kode / Ukuran</th>
                                <th class="px-2 py-1 text-right">Harga</th>
                                <th class="px-2 py-1 text-right">Biaya</th>
                                <th class="px-2 py-1 text-right">Lebar (cm)</th>
                                <th class="px-2 py-1 text-right">Tinggi (cm)</th>
                                <th class="px-2 py-1 text-center">Aktif</th>
                                <th class="px-2 py-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $variantIndex = 0; @endphp
                            @foreach($variants as $v)
                                <tr class="border-b border-slate-100">
                                    <td class="px-2 py-1">
                                        <input type="hidden" name="variants[{{ $variantIndex }}][id]"
                                               value="{{ $v->id }}">
                                        <input type="text" name="variants[{{ $variantIndex }}][label]"
                                               value="{{ $v->label }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="text" name="variants[{{ $variantIndex }}][size_code]"
                                               value={{ $v->size_code ? "\"$v->size_code\"" : '""' }}
                                               class="w-full rounded border border-slate-200 px-2 py-1"
                                               placeholder="A3+, A4, dst.">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="number" step="1" min="0"
                                               name="variants[{{ $variantIndex }}][price]"
                                               value="{{ $v->price }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="number" step="1" min="0"
                                               name="variants[{{ $variantIndex }}][cost]"
                                               value="{{ $v->cost }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="number" step="0.1" min="0"
                                               name="variants[{{ $variantIndex }}][width_cm]"
                                               value="{{ $v->width_cm }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="number" step="0.1" min="0"
                                               name="variants[{{ $variantIndex }}][height_cm]"
                                               value="{{ $v->height_cm }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                                    </td>
                                    <td class="px-2 py-1 text-center">
                                        <input type="checkbox" name="variants[{{ $variantIndex }}][is_active]" value="1"
                                               @checked($v->is_active)>
                                    </td>
                                    <td class="px-2 py-1 text-center">
                                        <input type="checkbox"
                                               name="variants[{{ $variantIndex }}][_delete]"
                                               value="1">
                                        <span class="text-[10px] text-rose-500">hapus</span>
                                    </td>
                                </tr>
                                @php $variantIndex++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ADDON / FINISHING --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold">Tambahan / Finishing</h2>
                        <button type="button"
                                class="text-xs text-sky-600"
                                onclick="xeonAddAddonRow()">
                            + Tambah Tambahan
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-xs" id="addons-table">
                            <thead>
                            <tr class="border-b border-slate-200 text-[11px] text-slate-500">
                                <th class="px-2 py-1 text-left">Nama Tambahan</th>
                                <th class="px-2 py-1 text-right">Extra Harga</th>
                                <th class="px-2 py-1 text-right">Extra Biaya</th>
                                <th class="px-2 py-1 text-center">Aktif</th>
                                <th class="px-2 py-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $addonIndex = 0; @endphp
                            @foreach($addons as $a)
                                <tr class="border-b border-slate-100">
                                    <td class="px-2 py-1">
                                        <input type="hidden" name="addons[{{ $addonIndex }}][id]"
                                               value="{{ $a->id }}">
                                        <input type="text" name="addons[{{ $addonIndex }}][name]"
                                               value="{{ $a->name }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="number" step="1" min="0"
                                               name="addons[{{ $addonIndex }}][extra_price]"
                                               value="{{ $a->extra_price }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                                    </td>
                                    <td class="px-2 py-1">
                                        <input type="number" step="1" min="0"
                                               name="addons[{{ $addonIndex }}][extra_cost]"
                                               value="{{ $a->extra_cost }}"
                                               class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                                    </td>
                                    <td class="px-2 py-1 text-center">
                                        <input type="checkbox" name="addons[{{ $addonIndex }}][is_active]" value="1"
                                               @checked($a->is_active)>
                                    </td>
                                    <td class="px-2 py-1 text-center">
                                        <input type="checkbox"
                                               name="addons[{{ $addonIndex }}][_delete]"
                                               value="1">
                                        <span class="text-[10px] text-rose-500">hapus</span>
                                    </td>
                                </tr>
                                @php $addonIndex++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Kolom kanan: etalase / preview --}}
            <div class="space-y-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-4 text-sm">
                    <h2 class="text-sm font-semibold mb-3">Etalase / Display Group</h2>
                    <div class="space-y-1 text-xs">
                        @foreach($groups as $group)
                            <label class="flex items-center gap-2">
                                <input type="checkbox"
                                       name="display_group_ids[]"
                                       value="{{ $group->id }}"
                                       @checked(in_array($group->id, old('display_group_ids', $product->displayGroups->pluck('id')->toArray())))>
                                <span>{{ $group->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">
                        Etalase akan muncul di landing page, katalog utama, dan POS CS.
                    </p>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-4 text-xs">
                    <h2 class="text-sm font-semibold mb-2">Preview Kartu Katalog</h2>
                    <div class="border border-slate-200 rounded-xl p-3">
                        <div class="flex flex-wrap gap-1 mb-1">
                            <span class="px-2 py-0.5 rounded-full bg-sky-50 text-sky-700 border border-sky-200 text-[10px]">
                                Best Seller
                            </span>
                        </div>
                        <div class="text-sm font-semibold mb-1 text-slate-800">
                            <span id="preview-name">{{ old('name', $product->name) ?: 'Nama produk' }}</span>
                        </div>
                        <div class="text-[11px] text-slate-500 mb-2 line-clamp-2" id="preview-short">
                            {{ old('short_description', $product->short_description) ?: 'Deskripsi singkat produk akan tampil di sini.' }}
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-[11px] text-slate-500">Mulai dari</div>
                            <div class="text-base font-bold text-sky-700">
                                (otomatis dari varian)
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-2">
                        Preview ini hanya ilustrasi tampilan di katalog / landing.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <a href="{{ route('admin.products.index') }}"
               class="px-4 py-2 rounded-full border border-slate-300 text-xs">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 rounded-full bg-sky-600 text-xs font-semibold text-white hover:bg-sky-500">
                Simpan
            </button>
        </div>
    </form>

    <script>
        let variantIndex = {{ $variantIndex ?? 0 }};
        let addonIndex   = {{ $addonIndex ?? 0 }};

        function xeonAddVariantRow() {
            const tbody = document.querySelector('#variants-table tbody');
            const idx = variantIndex++;

            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-100';
            tr.innerHTML = `
                <td class="px-2 py-1">
                    <input type="text" name="variants[${idx}][label]"
                           class="w-full rounded border border-slate-200 px-2 py-1"
                           placeholder="Contoh: Artpaper 150gr A3+">
                </td>
                <td class="px-2 py-1">
                    <input type="text" name="variants[${idx}][size_code]"
                           class="w-full rounded border border-slate-200 px-2 py-1"
                           placeholder="A3+, A4, dst.">
                </td>
                <td class="px-2 py-1">
                    <input type="number" step="1" min="0"
                           name="variants[${idx}][price]"
                           class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                </td>
                <td class="px-2 py-1">
                    <input type="number" step="1" min="0"
                           name="variants[${idx}][cost]"
                           class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                </td>
                <td class="px-2 py-1">
                    <input type="number" step="0.1" min="0"
                           name="variants[${idx}][width_cm]"
                           class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                </td>
                <td class="px-2 py-1">
                    <input type="number" step="0.1" min="0"
                           name="variants[${idx}][height_cm]"
                           class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                </td>
                <td class="px-2 py-1 text-center">
                    <input type="checkbox" name="variants[${idx}][is_active]" value="1" checked>
                </td>
                <td class="px-2 py-1 text-center text-[10px] text-rose-500">
                    (hapus diabaikan untuk baris baru)
                </td>
            `;
            tbody.appendChild(tr);
        }

        function xeonAddAddonRow() {
            const tbody = document.querySelector('#addons-table tbody');
            const idx = addonIndex++;

            const tr = document.createElement('tr');
            tr.className = 'border-b border-slate-100';
            tr.innerHTML = `
                <td class="px-2 py-1">
                    <input type="text" name="addons[${idx}][name]"
                           class="w-full rounded border border-slate-200 px-2 py-1"
                           placeholder="Potong, Simpress, Simpress + Keling, dst.">
                </td>
                <td class="px-2 py-1">
                    <input type="number" step="1" min="0"
                           name="addons[${idx}][extra_price]"
                           class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                </td>
                <td class="px-2 py-1">
                    <input type="number" step="1" min="0"
                           name="addons[${idx}][extra_cost]"
                           class="w-full rounded border border-slate-200 px-2 py-1 text-right">
                </td>
                <td class="px-2 py-1 text-center">
                    <input type="checkbox" name="addons[${idx}][is_active]" value="1" checked>
                </td>
                <td class="px-2 py-1 text-center text-[10px] text-rose-500">
                    (hapus diabaikan untuk baris baru)
                </td>
            `;
            tbody.appendChild(tr);
        }

        const nameInput = document.querySelector('input[name="name"]');
        if (nameInput) {
            nameInput.addEventListener('input', function () {
                document.getElementById('preview-name').textContent = this.value || 'Nama produk';
            });
        }
        const shortInput = document.querySelector('textarea[name="short_description"]');
        if (shortInput) {
            shortInput.addEventListener('input', function () {
                document.getElementById('preview-short').textContent = this.value || 'Deskripsi singkat produk akan tampil di sini.';
            });
        }
    </script>
</x-admin.layout>
