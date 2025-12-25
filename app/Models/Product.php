<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CustomerSpecialPrice;
use App\Models\ProductMemberPrice;
use App\Models\CustomerPrice;
use App\Models\InstansiPrice;
use App\Models\MemberPrice;
use App\Models\Inventory;
use App\Models\DisplayGroup;
use App\Models\ProductVariant;
use App\Models\GlobalPricingRule;
use App\Models\ProductAddon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'base_price',
        'attributes',
        'is_service',
        'is_active',
        'is_featured',
        'display_price',
        'short_description',
    ];

    protected $casts = [
        'attributes' => 'array',
        'is_service' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function specialPrices()
    {
        return $this->hasMany(CustomerSpecialPrice::class);
    }

    public function tierPrices()
    {
        return $this->hasMany(ProductMemberPrice::class);
    }

    public function customerPrices()
    {
        return $this->hasMany(CustomerPrice::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function displayGroups()
    {
        return $this->belongsToMany(DisplayGroup::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function globalPricingRules()
    {
        return $this->hasMany(GlobalPricingRule::class);
    }

    public function addons()
    {
        return $this->hasMany(ProductAddon::class)->orderBy('sort_order');
    }

    /**
     * Harga "mulai dari" untuk katalog/landing.
     */
    public function getStartingPriceAttribute(): ?float
    {
        if ($this->relationLoaded('variants') && $this->variants->count()) {
            return $this->variants->where('is_active', true)->min('price');
        }

        return $this->attributes['base_price'] ?? null;
    }

    public function getBestPrice($customer)
    {
        if ($customer?->member_type_id) {
            $tier = $this->priceForTier($customer->member_type_id);
            if (!empty($tier['price_per_m2'])) {
                return $tier['price_per_m2'];
            }
        }

        // PERSONAL PRICE
        $personal = CustomerPrice::where('customer_id', $customer->id)
            ->where('product_id', $this->id)
            ->first();
        if ($personal) return $personal->price;

        // INSTANSI PRICE
        if ($customer->instansi_id) {
            $inst = InstansiPrice::where('instansi_id', $customer->instansi_id)
                ->where('product_id', $this->id)
                ->first();
            if ($inst) return $inst->price;
        }

        // MEMBER PRICE
        $member = MemberPrice::where('member_type', $customer->member_type)
            ->where('product_id', $this->id)
            ->first();
        if ($member) return $member->price;

        // FALLBACK
        return $this->base_price;
    }

    public function priceForTier($memberTypeId)
    {
        $tier = $this->tierPrices()
            ->where('member_type_id', $memberTypeId)
            ->first();

        return [
            'price_per_m2' => $tier->price_per_m2 ?? $this->base_price,
            'flat_price'   => $tier->flat_price ?? 0,
        ];
    }

    use HasFactory;
}
