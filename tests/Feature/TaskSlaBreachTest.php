<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\StaffUser;
use App\Models\Task;
use App\Services\TaskSlaService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskSlaBreachTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function task_is_marked_as_breached_when_deadline_is_passed(): void
    {
        /**
         * === SETUP USER ===
         */
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        /**
         * === CREATE TASK WITH PAST DEADLINE ===
         */
        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'in_progress',
            'deadline'    => Carbon::now()->subHours(2), // ❌ sudah lewat
            'started_at'  => Carbon::now()->subHours(4),
        ]);

        /**
         * === CALCULATE SLA ===
         */
        $slaStatus = TaskSlaService::calculate($task);

        $task->sla_status = $slaStatus;
        $task->save();

        /**
         * === ASSERT ===
         */
        $this->assertEquals('breached', $slaStatus);

        $this->assertDatabaseHas('tasks', [
            'id'         => $task->id,
            'sla_status' => 'breached',
        ]);
    }

    /** @test */
    public function task_is_on_track_if_before_deadline(): void
    {
        $staff = $this->makeStaffWithRole('designer');

        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'in_progress',
            'deadline'    => Carbon::now()->addHours(4), // ✅ masih aman
            'started_at'  => Carbon::now()->subHour(),
        ]);

        $slaStatus = TaskSlaService::calculate($task);

        $this->assertEquals('on_track', $slaStatus);
    }

    /** @test */
    public function task_is_at_risk_when_near_deadline(): void
    {
        $staff = $this->makeStaffWithRole('designer');

        $task = Task::factory()->create([
            'assigned_to' => $staff->id,
            'status'      => 'in_progress',
            'deadline'    => Carbon::now()->addMinutes(15), // ⚠️ mepet
            'started_at'  => Carbon::now()->subHours(1),
        ]);

        $slaStatus = TaskSlaService::calculate($task);

        $this->assertEquals('at_risk', $slaStatus);
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

