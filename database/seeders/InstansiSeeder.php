<?php

namespace Database\Seeders;

use App\Models\Instansi;
use Illuminate\Database\Seeder;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        Instansi::firstOrCreate(
            ['name' => 'Contoh Instansi'],
            [
                'contact' => '021-0000000',
                'address' => 'Jl. Contoh No. 1',
            ]
        );
    }
}

