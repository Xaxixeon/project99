<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StaffUser;
use App\Models\Task;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected TaskPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new TaskPolicy();
    }

    #[Test]
    public function admin_can_merge_tasks()
    {
        $admin = $this->makeStaffWithRole('admin');

        $this->assertTrue(
            $this->policy->merge($admin)
        );
    }

    #[Test]
    public function staff_cannot_merge_tasks()
    {
        $staff = $this->makeStaffWithRole('designer');

        $this->assertFalse(
            $this->policy->merge($staff)
        );
    }

    #[Test]
    public function staff_can_update_own_task_but_not_done()
    {
        $staff = $this->makeStaffWithRole('designer');

        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status' => 'in_progress',
        ]);

        $this->assertTrue(
            $this->policy->updateStatus($staff, $task)
        );

        $task->status = 'done';

        $this->assertFalse(
            $this->policy->updateStatus($staff, $task)
        );
    }

    #[Test]
    public function qc_can_approve_waiting_approval_task()
    {
        $qc = $this->makeStaffWithRole('qc');

        $task = Task::factory()->create([
            'status' => 'waiting_approval',
        ]);

        $this->assertTrue(
            $this->policy->updateStatus($qc, $task)
        );
    }

    #[Test]
    public function manager_can_update_any_task()
    {
        $manager = $this->makeStaffWithRole('manager');

        $task = Task::factory()->create([
            'status' => 'done',
        ]);

        $this->assertTrue(
            $this->policy->updateStatus($manager, $task)
        );
    }

    protected function makeStaffWithRole(string $roleName): StaffUser
    {
        $role = Role::factory()->create(['name' => $roleName]);

        $user = StaffUser::factory()->create();
        $user->roles()->attach($role);

        return $user->fresh('roles');
        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status' => 'in_progress',
        ]);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     

        $this->assertTrue(
            $this->policy->updateStatus($staff, $task)
        );
    }
}
