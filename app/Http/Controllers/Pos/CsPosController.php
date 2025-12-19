<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAddon;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CsPosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');

        $products = Product::with([
                'variants' => fn($q) => $q->where('is_active', true),
                'displayGroups',
                'addons' => fn($q) => $q->where('is_active', true),
            ])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        $customers = Customer::orderBy('name')->get();

        return view('pos.cs.index', compact('products', 'customers', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'           => 'required|integer|exists:customers,id',
            'items'                 => 'required|array|min:1',
            'items.*.variant_id'    => 'required|integer|exists:product_variants,id',
            'items.*.qty'           => 'required|integer|min:1',
            'items.*.width_cm'      => 'nullable|numeric|min:0',
            'items.*.height_cm'     => 'nullable|numeric|min:0',
            'items.*.addon_ids'     => 'array',
            'items.*.addon_ids.*'   => 'integer|exists:product_addons,id',
            'service_fee_percent'   => 'nullable|numeric|min:0',
            'packing_fee'           => 'nullable|numeric|min:0',
            'delivery_fee'          => 'nullable|numeric|min:0',
            'rush_order_fee'        => 'nullable|numeric|min:0',
            'tax_percent'           => 'nullable|numeric|min:0',
            'payment_method'        => 'nullable|string|max:50',
            'due_date'              => 'nullable|date',
        ]);

        $customerId = $data['customer_id'];

        DB::beginTransaction();

        try {
            $totalPrice = 0; // subtotal before fees/tax
            $totalCost  = 0;

            $customer = Customer::findOrFail($customerId);

            $order = Order::create([
                'order_code'  => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $customerId,
                'status'      => 'new',
                'subtotal'    => 0,
                'total'       => 0,
                'total_price' => 0,
                'total_cost'  => 0,
                'profit'      => 0,
                'meta'        => ['source' => 'cs_pos'],
            ]);

            foreach ($data['items'] as $item) {
                $variant = ProductVariant::with('product')->findOrFail($item['variant_id']);

                $addons = collect($item['addon_ids'] ?? [])
                    ->unique()
                    ->values()
                    ->map(fn($id) => ProductAddon::findOrFail($id));

                $qty    = $item['qty'];
                $width  = $item['width_cm'] ?? 0;
                $height = $item['height_cm'] ?? 0;

                $unitPrice = $variant->priceForCustomer($customer);

                if ($width > 0 && $height > 0) {
                    $areaM2 = ($width * $height) / 10000;
                    $baseSubtotal = $unitPrice * $areaM2 * $qty;
                    $baseCost     = $variant->cost  * $areaM2 * $qty;
                } else {
                    $baseSubtotal = $unitPrice * $qty;
                    $baseCost     = $variant->cost  * $qty;
                }

                $addonSubtotal = 0;
                $addonCost     = 0;
                foreach ($addons as $addon) {
                    $addonSubtotal += $addon->extra_price * $qty;
                    $addonCost     += $addon->extra_cost  * $qty;
                }

                $lineTotal = (int) round($baseSubtotal + $addonSubtotal);
                $lineCost  = (int) round($baseCost + $addonCost);

                $totalPrice += $lineTotal;
                $totalCost  += $lineCost;

                $order->items()->create([
                    'product_id' => $variant->product_id,
                    'name'       => $variant->product->name,
                    'attributes' => [
                        'variant_id'    => $variant->id,
                        'variant_label' => $variant->label,
                        'addon_ids'     => $addons->pluck('id')->all(),
                        'width_cm'      => $width ?: null,
                        'height_cm'     => $height ?: null,
                    ],
                    'qty'      => $qty,
                    'price'    => $unitPrice,
                    'subtotal' => $lineTotal,
                ]);
            }

            $profit = $totalPrice - $totalCost;

            // fees & tax
            $servicePercent = (float) ($data['service_fee_percent'] ?? 0);
            $packingFee     = (float) ($data['packing_fee'] ?? 0);
            $deliveryFee    = (float) ($data['delivery_fee'] ?? 0);
            $rushFee        = (float) ($data['rush_order_fee'] ?? 0);
            $taxPercent     = (float) ($data['tax_percent'] ?? 0);

            $serviceFee = $totalPrice * ($servicePercent / 100);
            $preTax = $totalPrice + $serviceFee + $packingFee + $deliveryFee + $rushFee;
            $taxFee = $preTax * ($taxPercent / 100);

            $grandTotal = (int) round($preTax + $taxFee);
            $profit = $grandTotal - $totalCost;

            $order->update([
                'subtotal'    => $totalPrice,
                'total'       => $grandTotal,
                'total_price' => $grandTotal,
                'total_cost'  => $totalCost,
                'profit'      => $profit,
                'meta'        => array_merge($order->meta ?? [], [
                    'fees' => [
                        'service_fee_percent' => $servicePercent,
                        'service_fee'         => $serviceFee,
                        'packing_fee'         => $packingFee,
                        'delivery_fee'        => $deliveryFee,
                        'rush_order_fee'      => $rushFee,
                        'tax_percent'         => $taxPercent,
                        'tax_fee'             => $taxFee,
                    ],
                    'payment_method' => $data['payment_method'] ?? null,
                ]),
            ]);

            $invoice = Invoice::create([
                'order_id'    => $order->id,
                'customer_id' => $customerId,
                'amount'      => $grandTotal,
                'status'      => 'unpaid',
                'due_date'    => !empty($data['due_date'])
                    ? $data['due_date']
                    : now()->addDays(7),
            ]);

            DB::commit();

            return redirect()
                ->route('invoices.show', $invoice)
                ->with('success', 'Order & invoice berhasil dibuat.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return back()->withErrors('Gagal membuat order: ' . $e->getMessage());
        }
    }
}
