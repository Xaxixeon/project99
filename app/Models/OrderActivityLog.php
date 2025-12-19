<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderActivityLog extends Model
{
    protected $fillable = [
        'order_id',
        'staff_id',
        'from_status',
        'to_status',
        'note',
        'client_timestamp',
        'before_payload',
        'after_payload',
    ];

    protected $casts = [
        'client_timestamp' => 'datetime',
        'before_payload'   => 'array',
        'after_payload'    => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function staff()
    {
        // sesuaikan jika model staff berbeda
        return $this->belongsTo(StaffUser::class, 'staff_id');
    }
}
