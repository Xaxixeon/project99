<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMemberPrice extends Model
{
    protected $fillable = [
        'product_id',
        'member_type_id',
        'price_per_m2',
        'flat_price',
    ];

    public function memberType()
    {
        return $this->belongsTo(MemberType::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
