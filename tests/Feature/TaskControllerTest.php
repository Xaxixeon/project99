<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;


class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }

    #[Test]
    #[Group('task')]
    public function manager_can_merge_tasks(): void
    {
        $manager = $this->createUser('manager');

        $parentTask = Task::factory()->create();
        $childTask  = Task::factory()->create([
            'parent_id' => null,
        ]);

        $this->actingAs($manager)
            ->post(route('tasks.merge', $childTask->id), [
                'parent_id' => $parentTask->id,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id'        => $childTask->id,
            'parent_id'=> $parentTask->id,
        ]);
    }

    #[Test]
    #[Group('task')]
    public function staff_cannot_merge_tasks(): void
    {
        $staff = $this->createUser('staff');

        $parentTask = Task::factory()->create();
        $childTask  = Task::factory()->create();

        $this->actingAs($staff)
            ->post(route('tasks.merge', $childTask->id), [
                'parent_id' => $parentTask->id,
            ])
            ->assertStatus(403);
    }

    #[Test]
    #[Group('task')]
    public function staff_can_update_own_task_status(): void
    {
        $staff = $this->createUser('staff');

        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'pending',
        ]);

        $this->actingAs($staff)
            ->patch(route('tasks.updateStatus', $task->id), [
                'status' => 'in_progress',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id'     => $task->id,
            'status' => 'in_progress',
        ]);
    }

    #[Test]
    #[Group('task')]
    public function staff_cannot_mark_task_done(): void
    {
        $staff = $this->createUser('staff');

        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'in_progress',
        ]);

        $this->actingAs($staff)
            ->patch(route('tasks.updateStatus', $task->id), [
                'status' => 'done',
            ])
            ->assertStatus(403);
    }

    #[Test]
    #[Group('task')]
    public function qc_can_approve_waiting_approval_task(): void
    {
        $qc = $this->createUser('qc');

        $task = Task::factory()->create([
            'status' => 'waiting_approval',
        ]);

        $this->actingAs($qc)
            ->patch(route('tasks.approve', $task->id))
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id'     => $task->id,
            'status' => 'done',
        ]);
    }
}
