<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintingMaterial extends Model
{
    protected $fillable = [
        'name',
        'code',
        'price_per_m2',
        'cost_per_m2',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
