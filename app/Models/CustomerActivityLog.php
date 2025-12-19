<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerActivityLog extends Model
{
    protected $fillable = [
        'customer_id',
        'staff_id',
        'action',
        'before_payload',
        'after_payload',
    ];

    protected $casts = [
        'before_payload' => 'array',
        'after_payload'  => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(StaffUser::class, 'staff_id');
    }
}
