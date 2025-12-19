<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        Warehouse::updateOrCreate(
        ['code' => 'MAIN'],
        [
            'name'     => 'Main Warehouse',
            'location' => 'Head Office',
            'notes'    => 'Default warehouse'
        ]);

        Warehouse::updateOrCreate(
        ['code' => 'WHS-2'],
        [
            'name'     => 'Secondary Warehouse',
            'location' => 'Branch A',
        ]);
    }
}
