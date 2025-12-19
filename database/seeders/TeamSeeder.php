<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    public function run()
    {
        $teams = [
            ['name' => 'Design Team'],
            ['name' => 'Production Team'],
            ['name' => 'Customer Service Team'],
            ['name' => 'Marketing Team'],
            ['name' => 'Warehouse Team'],
            ['name' => 'Management Team'],
        ];

        foreach ($teams as $t) {
            Team::firstOrCreate(['name' => $t['name']]);
        }
    }
}
