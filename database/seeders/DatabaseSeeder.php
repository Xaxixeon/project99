<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            StaffSeeder::class,
            InstansiSeeder::class,
            MemberTypeSeeder::class,
            PricingSeeder::class,
            PaymentSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            WarehouseSeeder::class,
            InventorySeeder::class,
            TeamSeeder::class,          // <-- harus sebelum TeamMemberSeeder
            TeamMemberSeeder::class,    // <-- baru jalan setelah TeamSeeder
        ]);
    }
}
