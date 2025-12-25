<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Role;
use App\Models\StaffUser;
use App\Models\Task;
use Tests\TestCase;

class SecurityBreachTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function staff_cannot_merge_tasks(): void
    {
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        $taskA = Task::factory()->create();
        $taskB = Task::factory()->create();

        $this->post(route('tasks.merge'), [
            'task_ids' => [$taskA->id, $taskB->id],
        ])->assertStatus(403);
    }

    /** @test */
    public function staff_cannot_update_task_assigned_to_other_user(): void
    {
        $staffA = $this->makeStaffWithRole('designer');
        $staffB = $this->makeStaffWithRole('designer');

        $this->actingAs($staffA, 'staff');

        $task = Task::factory()->create([
            'assigned_to' => $staffB->id,
            'status'      => 'pending',
        ]);

        $this->post(route('tasks.status', $task), [
            'status' => 'in_progress',
        ])->assertStatus(403);
    }

    /** @test */
    public function non_qc_cannot_mark_task_done(): void
    {
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'waiting_approval',
        ]);

        $this->post(route('tasks.status', $task), [
            'status' => 'done',
        ])->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_task_endpoints(): void
    {
        $task = Task::factory()->create();

        $this->post(route('tasks.status', $task), [
            'status' => 'in_progress',
        ])->assertRedirect('/staff/login');
    }

    /** @test */
    public function invalid_status_update_is_rejected(): void
    {
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'pending',
        ]);

        $this->post(route('tasks.status', $task), [
            'status' => 'hacked_status',
        ])->assertStatus(422);
    }

    /**
     * Helper
     */
    protected function makeStaffWithRole(string $roleName): StaffUser
    {
        Role::factory()->create(['name' => $roleName]);

        $user = StaffUser::factory()->create();
        $user->assignRole($roleName);

        return $user;
    }
}
