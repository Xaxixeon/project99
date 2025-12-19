<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            $product = $products->random();

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'qty'        => rand(1, 10),
                'price'      => $product->base_price,
                'total'      => $product->base_price * rand(1, 10),
            ]);
        }
    }
}
