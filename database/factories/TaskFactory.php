<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),

            // ENUM sesuai migration
            'status' => $this->faker->randomElement([
                'pending',
                'in_progress',
                'waiting_approval',
                'done',
            ]),

            'priority' => $this->faker->randomElement([
                'low',
                'medium',
                'high',
            ]),

            // ✅ FK AMAN (TIDAK pakai numberBetween)
            'assigned_to' => User::factory(),

            // ✅ default null (aman untuk FK self-reference)
            'parent_id' => null,

            'deadline' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
        ];
    }

    /**
     * State: task memiliki parent task
     */
    public function withParent(): self
    {
        return $this->state(fn() => [
            'parent_id' => Task::factory(),
        ]);
    }

    /**
     * State: task tanpa assignment (nullable FK)
     */
    public function unassigned(): self
    {
        return $this->state(fn() => [
            'assigned_to' => null,
            'status' => 'waiting_approval',
        ]);

        $this->assertTrue(
            $this->policy->approve($qc, $task)
        );
    }
}
