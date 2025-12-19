<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAddon extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'extra_price',
        'extra_cost',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'extra_price' => 'float',
        'extra_cost'  => 'float',
        'is_default'  => 'bool',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
