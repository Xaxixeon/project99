<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:255',
            'material'   => 'nullable|string|max:255',
            'size_code'  => 'nullable|string|max:255',
            'price'      => 'nullable|numeric',
            'cost'       => 'nullable|numeric',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'sometimes|boolean',
        ]);

        $product->variants()->create($data);

        return back()->with('success', 'Varian berhasil ditambahkan.');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:255',
            'material'   => 'nullable|string|max:255',
            'size_code'  => 'nullable|string|max:255',
            'price'      => 'nullable|numeric',
            'cost'       => 'nullable|numeric',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'sometimes|boolean',
        ]);

        $variant->update($data);

        return back()->with('success', 'Varian berhasil diperbarui.');
    }

    public function destroy(ProductVariant $variant)
    {
        $variant->delete();

        return back()->with('success', 'Varian dihapus.');
    }
}
