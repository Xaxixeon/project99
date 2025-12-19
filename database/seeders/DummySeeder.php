<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Inventory;

class DummySeeder extends Seeder
{
    public function run(): void
    {
        // === ROLES ===
        $roles = [
            'admin',
            'customer_service',
            'designer',
            'production',
            'cashier',
            'warehouse',
            'marketing',
            'manager',
            'superadmin'
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // === USERS PER ROLE ===
        foreach ($roles as $role) {
            $user = User::create([
                'name' => ucfirst($role),
                'email' => $role.'@example.com',
                'password' => Hash::make('password'),
            ]);

            $user->roles()->attach(Role::where('name', $role)->first());
        }

        // === SUPERADMIN ===
        $super = User::create([
            'name' => 'Super Admin',
            'email' => 'super@example.com',
            'password' => Hash::make('password'),
        ]);
        $super->roles()->attach(Role::where('name', 'superadmin')->first());

        // === CUSTOMERS ===
        for ($i=1; $i<=5; $i++) {
            Customer::create([
                'name' => "Customer $i",
                'email' => "customer$i@mail.com",
                'phone' => "08123$i",
                'address' => "Jl Dummy No $i",
            ]);
        }

        // === PRODUCTS ===
        for ($i=1; $i<=5; $i++) {
            Product::create([
                'sku' => 'PRD-00'.$i,
                'name' => 'Product '.$i,
                'base_price' => rand(10000, 50000),
                'description' => 'Dummy product '.$i,
                'is_active' => true
            ]);
        }

        // === WAREHOUSE ===
        $mainWarehouse = Warehouse::create([
            'name' => 'Main Warehouse',
            'code' => 'MAIN',
            'location' => 'HQ',
        ]);

        // === INVENTORIES ===
        foreach (Product::all() as $product) {
            Inventory::create([
                'warehouse_id' => $mainWarehouse->id,
                'product_id' => $product->id,
                'quantity' => rand(10, 100),
            ]);
        }
    }
}
