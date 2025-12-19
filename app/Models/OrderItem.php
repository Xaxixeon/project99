<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id','product_id','name','attributes','qty','price','subtotal','design_file','note'];
    protected $casts = ['attributes' => 'array'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
