<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockMovement;
use App\Models\Inventory;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $inventories = Inventory::all();

        foreach ($inventories as $inv) {
            StockMovement::create([
                'inventory_id' => $inv->id,
                'type'         => 'adjustment',
                'quantity'     => rand(-5, 5),
                'performed_by' => 1,
                'notes'        => 'Dummy adjustment'
            ]);
        }
    }
}
