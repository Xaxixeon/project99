<?php

namespace Database\Factories;

use App\Models\StaffUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StaffUserFactory extends Factory
{
    protected $model = StaffUser::class;

    public function definition(): array
    {
        return [
            'staff_code' => fake()->unique()->bothify('STF-###'),
            'name'       => fake()->name(),
            'username'   => fake()->unique()->userName(),
            'email'      => fake()->unique()->safeEmail(),
            'password'   => Hash::make('password'),
            'is_active'  => true,
            'metadata'   => [],
        ];
    }
}
