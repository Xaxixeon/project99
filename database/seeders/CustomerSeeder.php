<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Customer::updateOrCreate(
                ['email' => "customer$i@mail.com"],
                [
                    'name'    => "Customer $i",
                    'phone'   => "08123$i",
                    'address' => "Street No. $i, City",
                ]
            );
        }
    }
}
