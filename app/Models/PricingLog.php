<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingLog extends Model
{
    protected $fillable = [
        'admin_id',
        'product_id',
        'product_variant_id',
        'old_price',
        'new_price',
        'changed_at',
    ];

    protected $casts = [
        'old_price'  => 'float',
        'new_price'  => 'float',
        'changed_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
