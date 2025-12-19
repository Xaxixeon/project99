<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderFile extends Model
{
    protected $fillable = [
        'order_id',
        'uploaded_by_staff_id',
        'uploaded_by_customer_id',
        'file_path',
        'file_original_name',
        'type',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
