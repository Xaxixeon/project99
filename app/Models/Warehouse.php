<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'code',
        'location',
        'notes',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
