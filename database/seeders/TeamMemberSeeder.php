<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\StaffUser;

class TeamMemberSeeder extends Seeder
{
    public function run()
    {
        $teams = Team::all();
        $staffs = StaffUser::all();

        if ($teams->isEmpty() || $staffs->isEmpty()) {
            return;
        }

        foreach ($teams as $team) {
            $member = $staffs->random(1)->first();
            $team->members()->syncWithoutDetaching([$member->id]);
        }
    }
}
