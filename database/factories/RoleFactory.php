<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'admin',
                'manager',
                'designer',
                'qc',
            ]),
            'display_name' => null,
            'description'  => null,
        ];
    }

    // helper states (sangat berguna untuk test)
    public function admin()
    {
        return $this->state(fn() => ['name' => 'admin']);
    }

    public function manager()
    {
        return $this->state(fn() => ['name' => 'manager']);
    }

    public function designer()
    {
        return $this->state(fn() => ['name' => 'designer']);
    }

    public function qc()
    {
        return $this->state(fn() => ['name' => 'qc']);
    }
}
