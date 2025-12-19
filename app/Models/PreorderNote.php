<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreorderNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'note_id',
        'customer_name',
        'title',
        'product',
        'notes',
        'deadline',
        'priority',
        'user_id',
        'updated_at_client',
    ];
}
