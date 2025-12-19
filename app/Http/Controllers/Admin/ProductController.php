<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisplayGroup;
use App\Models\Product;
use App\Models\ProductAddon;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');

        $products = Product::with(['displayGroups', 'variants'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    public function create()
    {
        $groups = DisplayGroup::orderBy('name')->get();

        return view('admin.products.edit', [
            'product'  => new Product(),
            'groups'   => $groups,
            'variants' => collect(),
            'addons'   => collect(),
            'mode'     => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateBase($request);

        $product = Product::create($data);

        $this->syncDisplayGroups($product, $request);
        $this->syncVariants($product, $request);
        $this->syncAddons($product, $request);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function edit($id)
    {
        $product  = Product::with(['displayGroups', 'variants', 'addons'])->findOrFail($id);
        $groups   = DisplayGroup::orderBy('name')->get();
        $variants = $product->variants;
        $addons   = $product->addons;

        return view('admin.products.edit', [
            'product'  => $product,
            'groups'   => $groups,
            'variants' => $variants,
            'addons'   => $addons,
            'mode'     => 'edit',
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $this->validateBase($request, $product->id);
        $product->update($data);

        $this->syncDisplayGroups($product, $request);
        $this->syncVariants($product, $request);
        $this->syncAddons($product, $request);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // ========================= HELPERS =========================

    protected function validateBase(Request $request, ?int $productId = null): array
    {
        return $request->validate([
            'name'              => 'required|string|max:255',
            'sku'               => 'nullable|string|max:100|unique:products,sku,' . ($productId ?? 'null'),
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'is_active'         => 'sometimes|boolean',
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }

    protected function syncDisplayGroups(Product $product, Request $request): void
    {
        $groupIds = $request->input('display_group_ids', []);
        $product->displayGroups()->sync($groupIds);
    }

    protected function syncVariants(Product $product, Request $request): void
    {
        $rows = $request->input('variants', []);
        if (!is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $label = trim($row['label'] ?? '');
            $id    = $row['id'] ?? null;
            $deleteFlag = !empty($row['_delete']);

            if ($id && $deleteFlag) {
                ProductVariant::where('product_id', $product->id)->where('id', $id)->delete();
                continue;
            }

            if ($label === '' && !$id) {
                continue;
            }

            $payload = [
                'label'     => $label ?: ($row['size_code'] ?? ''),
                'size_code' => $row['size_code'] ?? null,
                'price'     => (float)($row['price'] ?? 0),
                'cost'      => (float)($row['cost'] ?? 0),
                'width_cm'  => $row['width_cm'] ?? null,
                'height_cm' => $row['height_cm'] ?? null,
                'is_active' => !empty($row['is_active']),
            ];

            if ($id) {
                $variant = ProductVariant::where('product_id', $product->id)->findOrFail($id);
                $variant->update($payload);
            } else {
                $product->variants()->create($payload);
            }
        }
    }

    protected function syncAddons(Product $product, Request $request): void
    {
        $rows = $request->input('addons', []);
        if (!is_array($rows)) {
            return;
        }

        foreach ($rows as $row) {
            $name = trim($row['name'] ?? '');
            $id   = $row['id'] ?? null;
            $deleteFlag = !empty($row['_delete']);

            if ($id && $deleteFlag) {
                ProductAddon::where('product_id', $product->id)->where('id', $id)->delete();
                continue;
            }

            if ($name === '' && !$id) {
                continue;
            }

            $payload = [
                'name'        => $name,
                'extra_price' => (float)($row['extra_price'] ?? 0),
                'extra_cost'  => (float)($row['extra_cost'] ?? 0),
                'is_active'   => !empty($row['is_active']),
            ];

            if ($id) {
                $addon = ProductAddon::where('product_id', $product->id)->findOrFail($id);
                $addon->update($payload);
            } else {
                $product->addons()->create($payload);
            }
        }
    }
}
