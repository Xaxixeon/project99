<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;

class PricingService
{
    /**
     * Hierarki harga:
     * 1. Special Price (Paling tinggi)
     * 2. Tier Price per Produk
     * 3. Global Tier Discount
     * 4. Default Product Price
     */
    public static function calculate(Product $product, Customer $customer, float $areaM2, int $qty)
    {
        $memberType = $customer->memberType;

        // (1) SPECIAL PRICE
        $special = $product->specialPrices()
            ->where('customer_id', $customer->id)
            ->first();

        if ($special) {
            $total = ($special->price_per_m2 * $areaM2 * $qty) + $special->flat_price;
            return [
                'source' => 'SPECIAL_PRICE',
                'total' => $total,
            ];
        }

        // (2) TIER PRICE PER PRODUK
        $tier = $product->tierPrices()
            ->where('member_type_id', $customer->member_type_id)
            ->first();

        if ($tier) {
            $perM2 = $tier->price_per_m2 ?? $product->base_price;
            $flat  = $tier->flat_price ?? 0;
            $total = ($perM2 * $areaM2 * $qty) + $flat;

            return [
                'source' => 'TIER_PRODUCT',
                'total' => $total,
            ];
        }

        // (3) GLOBAL TIER DISCOUNT
        $globalDiscount = $memberType?->discount_percent ?? 0;

        $base = ($product->base_price * $areaM2 * $qty);

        if ($globalDiscount > 0) {
            $discount = $base * ($globalDiscount / 100);
            return [
                'source' => 'GLOBAL_TIER',
                'total' => $base - $discount,
            ];
        }

        // (4) DEFAULT PRICE
        return [
            'source' => 'BASE_PRICE',
            'total' => $product->base_price * $areaM2 * $qty,
        ];
    }
}
