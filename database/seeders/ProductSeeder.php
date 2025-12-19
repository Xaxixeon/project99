<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder {
  public function run() {
    Product::updateOrCreate(
    ['sku' => 'BROSUR-A4'],
    [
        'name'        => 'Brosur A4 - Full Color',
        'description' => 'Brosur A4, full-color, cocok untuk promosi.',
        'base_price'  => 20000,
        'attributes'  => [],
        'is_service'  => false,
        'is_active'   => 1,
    ]
);
  }
}
