<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'channel',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'bool',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
