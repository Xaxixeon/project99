<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Records changes to inventory quantities.
 *
 * Each stock movement references an inventory record and indicates whether
 * the movement was an incoming, outgoing, adjustment or reservation entry.
 */
class StockMovement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inventory_id',
        'type',
        'quantity',
        'performed_by',
        'reference',
        'notes',
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'inventory_id' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * Get the inventory record associated with this movement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    /**
     * Get the user who performed the stock movement.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}