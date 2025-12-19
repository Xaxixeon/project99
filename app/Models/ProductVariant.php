<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'label',
        'type',
        'paper_weight',
        'material',
        'color_type',
        'size_code',
        'width_cm',
        'height_cm',
        'price',
        'cost',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'width_cm'  => 'float',
        'height_cm' => 'float',
        'price'     => 'float',
        'cost'      => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function memberTypeOverrides()
    {
        return $this->hasMany(MemberTypeProductVariant::class, 'product_variant_id');
    }

    public function priceForCustomer($customer = null): float
    {
        $price = (float) $this->price;

        // Tier override
        if ($customer && $customer->memberType) {
            $override = $this->memberTypeOverrides
                ? $this->memberTypeOverrides->firstWhere('member_type_id', $customer->member_type_id)
                : MemberTypeProductVariant::where('member_type_id', $customer->member_type_id)
                    ->where('product_variant_id', $this->id)
                    ->first();

            if ($override) {
                if ($override->price_override !== null) {
                    $price = (float) $override->price_override;
                } elseif ($override->discount_percent !== null) {
                    $price = $price * (1 - ($override->discount_percent / 100));
                } elseif ($override->markup_percent !== null) {
                    $price = $price * (1 + ($override->markup_percent / 100));
                }
            }
        }

        // Global rules
        $rules = GlobalPricingRule::where('active', true)
            ->where(function ($q) {
                $q->where('applies_to', 'all')
                  ->orWhere(function ($qq) {
                      $qq->where('applies_to', 'product')
                          ->where('product_id', $this->product_id);
                  })
                  ->orWhere(function ($qq) {
                      $qq->where('applies_to', 'variant')
                          ->where('product_variant_id', $this->id);
                  });
            })
            ->get();

        foreach ($rules as $rule) {
            if ($rule->value_type === 'percent') {
                $price += $price * ($rule->value / 100);
            } else {
                $price += $rule->value;
            }
        }

        return max(0, $price);
    }
}
