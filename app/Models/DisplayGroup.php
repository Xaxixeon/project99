<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayGroup extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'badge_color',
        'show_on_landing',
        'sort_order',
    ];

    protected $casts = [
        'show_on_landing' => 'bool',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }
}
