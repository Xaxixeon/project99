<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = Warehouse::all();
        $products = Product::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                Inventory::create([
                    'warehouse_id' => $warehouse->id,
                    'product_id'   => $product->id,
                    'quantity'     => rand(20, 200),
                ]);
            }
        }
    }
}
