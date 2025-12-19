<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintingFinishing extends Model
{
    protected $fillable = [
        'name',
        'code',
        'price_per_m2',
        'flat_fee',
        'cost_per_m2',
        'cost_flat',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
