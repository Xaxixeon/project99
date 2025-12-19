<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalPricingRule extends Model
{
    protected $fillable = [
        'name',
        'applies_to',
        'product_id',
        'product_variant_id',
        'value_type',
        'value',
        'active',
    ];

    protected $casts = [
        'value'  => 'float',
        'active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
