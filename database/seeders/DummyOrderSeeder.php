<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DummyOrderSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'new',
            'assigned',
            'designing',
            'design_done',
            'production',
            'printing',
            'finishing',
            'ready',
            'paid',
            'packing',
            'shipping',
            'completed'
        ];

        $customers = Customer::all();
        $products = Product::all();
        $users = User::all();

        for ($i = 1; $i <= 20; $i++) {

            $status = $statuses[array_rand($statuses)];

            $subtotal = rand(50000, 200000);
            $discount = rand(0, 20000);
            $shipping = rand(0, 15000);
            $additional = rand(0, 20000);

            $order = Order::create([
                'order_code'        => 'ORD-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'customer_id'       => $customers->random()->id,
                'created_by'        => $users->random()->id,

                'assigned_to'       => $users->random()->id,
                'status'            => $status,

                'subtotal'          => $subtotal,
                'discount'          => $discount,
                'shipping_cost'     => $shipping,
                'additional_cost'   => $additional,
                'total'             => $subtotal - $discount + $shipping + $additional,

                'notes'             => 'Dummy order '.$i,
                'meta'              => [
                    'size' => 'A4',
                    'material' => 'Art Paper',
                    'finishing' => 'Laminating Glossy'
                ],

                'assigned_at'               => now()->subDays(rand(1,10)),
                'design_started_at'         => now()->subDays(rand(1,10)),
                'design_completed_at'       => now()->subDays(rand(1,10)),
                'production_started_at'     => now()->subDays(rand(1,10)),
                'production_completed_at'   => now()->subDays(rand(1,10)),
                'payment_at'                => now()->subDays(rand(1,10)),
                'shipping_at'               => now()->subDays(rand(1,10)),
                'completed_at'              => now()->subDays(rand(1,10)),
            ]);

            // Tambahkan item acak
            for ($x = 0; $x < rand(1, 3); $x++) {
                $product = $products->random();
                $qty     = rand(1, 5);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $product->base_price,
                    'total' => $product->base_price * $qty
                ]);
            }
        }
    }
}
