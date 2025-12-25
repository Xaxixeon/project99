<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(50_000, 500_000);
        $shipping = fake()->numberBetween(0, 50_000);
        $discountPercent = fake()->randomElement([0, 5, 10]);
        $discountAmount = (int) round($subtotal * ($discountPercent / 100));
        $taxAmount = (int) round(($subtotal - $discountAmount) * 0.11);

        $total = $subtotal + $shipping - $discountAmount + $taxAmount;

        return [
            'order_code'        => 'ORD-' . fake()->unique()->numerify('######'),
            'customer_id'       => Customer::factory(),
            'user_id'           => User::factory(),

            // Financials (CONSISTENT TYPE)
            'subtotal'          => $subtotal,
            'shipping'          => $shipping,
            'discount_percent'  => $discountPercent,
            'discount_amount'   => $discountAmount,
            'tax_amount'        => $taxAmount,
            'total'             => $total,

            // ENUM SAFE
            'status' => fake()->randomElement([
                'pending',
                'confirmed',
                'production',
                'printing',
                'completed',
            ]),

            'notes' => fake()->optional()->sentence(),
            'meta'  => [],
        ];
    }

    /**
     * State helpers (optional but useful)
     */
    public function pending()
    {
        return $this->state(fn() => ['status' => 'pending']);
    }

    public function completed()
    {
        return $this->state(fn() => ['status' => 'completed']);
    }

    public function cancelled()
    {
        return $this->state(fn() => ['status' => 'cancelled']);
    }
}
