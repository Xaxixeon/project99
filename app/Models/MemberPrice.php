<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberPrice extends Model
{
    protected $fillable = [
        'member_type',
        'product_id',
        'price',
        'label',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function memberType()
    {
        return $this->belongsTo(MemberType::class, 'member_type', 'code');
    }
}

