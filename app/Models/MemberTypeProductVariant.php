<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTypeProductVariant extends Model
{
    protected $fillable = [
        'member_type_id',
        'product_variant_id',
        'price_override',
        'discount_percent',
        'markup_percent',
    ];

    protected $casts = [
        'price_override'   => 'float',
        'discount_percent' => 'float',
        'markup_percent'   => 'float',
    ];

    public function memberType()
    {
        return $this->belongsTo(MemberType::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
