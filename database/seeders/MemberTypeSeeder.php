<?php

namespace Database\Seeders;

use App\Models\MemberType;
use Illuminate\Database\Seeder;

class MemberTypeSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['code' => 'silver', 'label' => 'Silver', 'default_discount' => 0],
            ['code' => 'gold',   'label' => 'Gold',   'default_discount' => 5],
            ['code' => 'platinum', 'label' => 'Platinum', 'default_discount' => 10],
        ];

        foreach ($defaults as $type) {
            MemberType::updateOrCreate(['code' => $type['code']], $type);
        }
    }
}

