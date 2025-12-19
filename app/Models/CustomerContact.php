<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerContact extends Model
{
    protected $fillable = [
        'customer_id',
        'type',
        'value'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}