<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $fillable = ['name','contact','address'];

    public function customers() {
        return $this->hasMany(Customer::class);
    }

    public function prices() {
        return $this->hasMany(InstansiPrice::class);
    }
}

