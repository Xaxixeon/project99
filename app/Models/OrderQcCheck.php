<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderQcCheck extends Model
{
    protected $fillable = [
        'order_id',
        'staff_id',
        'item',
        'passed',
        'notes',
    ];

    protected $casts = [
        'passed' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
