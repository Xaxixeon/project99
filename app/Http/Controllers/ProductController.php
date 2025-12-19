<?php

namespace App\Http\Controllers;

use App\Models\PrintingFinishing;
use App\Models\PrintingMaterial;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Halaman katalog / listing produk
     */
    public function index(Request $request)
    {
        $query = Product::with(['displayGroups', 'variants']);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($dg = $request->get('display_group')) {
            $query->whereHas('displayGroups', fn($q) => $q->where('display_group_id', $dg));
        }

        if ($min = $request->get('price_min')) {
            $query->whereHas('variants', fn($q) => $q->where('price', '>=', $min));
        }

        if ($max = $request->get('price_max')) {
            $query->whereHas('variants', fn($q) => $q->where('price', '<=', $max));
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();
        $displayGroups = \App\Models\DisplayGroup::orderBy('sort_order')->orderBy('name')->get();
        $viewMode = $request->get('view', 'grid');

        return view('products.index', compact('products', 'displayGroups', 'viewMode'));
    }

    /**
     * Detail produk
     */
    public function show($sku)
    {
        $product = Product::with(['variants', 'addons'])->where('sku', $sku)->firstOrFail();
        $customer = auth('customer')->user();
        $tierPrice = $customer
            ? $product->priceForTier($customer->member_type_id)
            : ['price_per_m2' => $product->base_price, 'flat_price' => 0];

        // preset ukuran area x100
        $sizePresets = [
            'A3'            => 25,
            'A4'            => 16,
            'A5'            => 8,
            'Banner 60x160' => 96,
            'Custom'        => null,
        ];

        $materials  = PrintingMaterial::where('is_active', true)->orderBy('name')->get();
        $finishings = PrintingFinishing::where('is_active', true)->orderBy('name')->get();

        return view('products.show', compact('product', 'sizePresets', 'materials', 'finishings', 'tierPrice', 'customer'));
    }
}
