<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Shipping record associated with an order.
 *
 * This model maps to the `shippings` table and tracks outbound shipments.
 */
class Shipping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'courier',
        'tracking_number',
        'status',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_id' => 'integer',
    ];

    /**
     * Get the order that owns the shipping record.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}