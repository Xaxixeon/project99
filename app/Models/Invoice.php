<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_no',
        'amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public const STATUS_UNPAID  = 'unpaid';
    public const STATUS_PAID    = 'paid';
    public const STATUS_PARTIAL = 'partial';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
