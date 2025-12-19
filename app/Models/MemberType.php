<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'label',
        'priority',
        'discount_percent',
        'description',
        'default_discount',
        'is_default',
    ];

    protected $casts = [
        'default_discount' => 'decimal:2',
        'discount_percent' => 'float',
        'is_default'       => 'boolean',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'member_type_id');
    }

    public function variantOverrides()
    {
        return $this->hasMany(MemberTypeProductVariant::class);
    }

    public function getEffectiveDiscountAttribute(): float
    {
        $percent = $this->discount_percent ?? 0;
        if ($percent === 0 && $this->default_discount) {
            return (float) $this->default_discount;
        }

        return (float) $percent;
    }
}
