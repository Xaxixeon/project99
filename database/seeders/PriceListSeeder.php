<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceListSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('price_lists')->insert([
            ['name' => 'Digital Print Standard'],
            ['name' => 'Premium UV Print'],
        ]);
    }
}
