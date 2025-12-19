<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\PrintingMaterial;
use App\Models\PrintingFinishing;

class PricingEngine
{
    public static function calculate(array $data): array
    {
        $variant   = ProductVariant::findOrFail($data['variant_id']);
        $material  = PrintingMaterial::findOrFail($data['material_id']);
        $finishing = !empty($data['finishing_id'])
            ? PrintingFinishing::find($data['finishing_id'])
            : null;

        $qty      = max(1, (int) ($data['quantity'] ?? 1));
        $width    = (float) ($data['width'] ?? 0);
        $height   = (float) ($data['height'] ?? 0);
        $discount = (float) ($data['discount'] ?? 0); // persen
        $useTax   = (bool) ($data['use_tax'] ?? true);

        // === AREA (cm → m²)
        $area = 1;
        if ($variant->price_type === 'sqm') {
            $area = max(1, ($width * $height) / 10000);
        }

        // === BASE COMPONENT
        $variantPrice   = $variant->base_price * $area;
        $materialPrice  = $material->price * $area;
        $finishingPrice = $finishing?->price ?? 0;

        $subtotal = ($variantPrice + $materialPrice) * $qty;

        // === DISCOUNT
        $discountAmount = $discount > 0
            ? ($subtotal * $discount / 100)
            : 0;

        $afterDiscount = $subtotal - $discountAmount;

        // === TAX (PPN 11%)
        $taxAmount = $useTax ? ($afterDiscount * 0.11) : 0;

        $total = $afterDiscount + $finishingPrice + $taxAmount;

        return [
            'area'             => round($area, 2),
            'quantity'         => $qty,

            'variant_price'    => round($variantPrice),
            'material_price'   => round($materialPrice),
            'finishing_price'  => round($finishingPrice),

            'subtotal'         => round($subtotal),
            'discount_percent' => $discount,
            'discount_amount'  => round($discountAmount),

            'tax_amount'       => round($taxAmount),
            'total'            => round($total),
        ];
    }
}
