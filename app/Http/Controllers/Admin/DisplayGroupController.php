<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisplayGroup;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DisplayGroupController extends Controller
{
    public function index()
    {
        $groups   = DisplayGroup::orderBy('sort_order')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.display-groups.index', compact('groups', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'badge_color'     => 'nullable|string|max:20',
            'show_on_landing' => 'boolean',
            'sort_order'      => 'nullable|integer',
        ]);

        $data['slug'] = Str::slug($data['name']);

        DisplayGroup::create($data);

        return back()->with('success', 'Etalase berhasil ditambahkan.');
    }

    public function update(Request $request, DisplayGroup $displayGroup)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'badge_color'     => 'nullable|string|max:20',
            'show_on_landing' => 'boolean',
            'sort_order'      => 'nullable|integer',
            'product_ids'     => 'array',
            'product_ids.*'   => 'integer|exists:products,id',
        ]);

        $data['slug'] = Str::slug($data['name']);

        $displayGroup->update($data);

        if (isset($data['product_ids'])) {
            $syncData = [];
            foreach ($data['product_ids'] as $index => $pid) {
                $syncData[$pid] = ['sort_order' => $index];
            }
            $displayGroup->products()->sync($syncData);
        }

        return back()->with('success', 'Etalase diperbarui.');
    }

    public function destroy(DisplayGroup $displayGroup)
    {
        $displayGroup->delete();

        return back()->with('success', 'Etalase dihapus.');
    }

    public function sort(Request $request)
    {
        $order = $request->input('order', []);
        if (is_string($order)) {
            $decoded = json_decode($order, true);
            if (is_array($decoded)) {
                $order = $decoded;
            }
        }

        foreach ($order as $index => $id) {
            DisplayGroup::where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Urutan etalase diperbarui.');
    }
}
