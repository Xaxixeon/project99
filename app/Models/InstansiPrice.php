<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstansiPrice extends Model
{
    protected $fillable = [
        'instansi_id',
        'product_id',
        'price',
        'label',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

