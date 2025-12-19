<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPrice extends Model
{
    protected $fillable = [
        'customer_id',
        'product_id',
        'price',
        'label',
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
