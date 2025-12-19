<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSpecialPrice extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'price_per_m2',
        'flat_price',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
