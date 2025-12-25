<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StaffUser;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogE2ETest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function audit_log_is_created_when_task_status_is_updated(): void
    {
        /**
         * === SETUP USER ===
         */
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        /**
         * === CREATE TASK ===
         */
        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'pending',
        ]);

        /**
         * === UPDATE STATUS (CONTROLLER) ===
         */
        $this->post(
            route('tasks.status', $task),
            ['status' => 'in_progress']
        )->assertRedirect();

        /**
         * === ASSERT AUDIT LOG ===
         */
        $this->assertDatabaseHas('audit_logs', [
            'action'    => 'update_task_status',
            'model'     => 'Task',
            'model_id'  => $task->id,
        ]);
    }

    /** @test */
    public function audit_log_is_created_when_tasks_are_merged(): void
    {
        /**
         * === SETUP MANAGER ===
         */
        $manager = $this->makeStaffWithRole('manager');
        $this->actingAs($manager, 'staff');

        /**
         * === CREATE TASKS ===
         */
        $taskA = Task::factory()->create();
        $taskB = Task::factory()->create();

        /**
         * === MERGE TASKS ===
         */
        $this->post(route('tasks.merge'), [
            'task_ids' => [$taskA->id, $taskB->id],
            'title'    => 'Merged From Test',
        ])->assertRedirect();

        /**
         * === ASSERT PARENT AUDIT ===
         */
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'merge_task',
            'model'  => 'Task',
        ]);

        /**
         * === ASSERT CHILD AUDIT ===
         */
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'merge_task_child',
            'model'  => 'Task',
            'model_id' => $taskA->id,
        ]);
    }

    /**
     * Helper: create staff user with role
     */
    protected function makeStaffWithRole(string $roleName): StaffUser
    {
        Role::factory()->create(['name' => $roleName]);

        $user = StaffUser::factory()->create();
        $user->assignRole($roleName);

        return $user;
    }
}
