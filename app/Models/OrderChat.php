<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderChat extends Model
{
    protected $fillable = [
        'order_id',
        'staff_id',
        'customer_id',
        'message',
        'attachment',
        'sender_type',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
