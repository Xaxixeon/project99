<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\StaffUser;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderActivityLogE2ETest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_activity_log_created_when_task_status_changes(): void
    {
        /**
         * === SETUP STAFF ===
         */
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        /**
         * === CREATE ORDER ===
         */
        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        /**
         * === CREATE TASK ===
         */
        $task = Task::factory()->create([
            'order_id'    => $order->id,
            'assigned_to' => $staff->id,
            'status'      => 'pending',
        ]);

        /**
         * === UPDATE TASK STATUS (TRIGGER CONTROLLER LOGIC) ===
         */
        $this->post(route('tasks.status', $task), [
            'status' => 'in_progress',
        ])->assertRedirect();

        /**
         * === ASSERT ORDER ACTIVITY LOG ===
         */
        $this->assertDatabaseHas('order_activity_logs', [
            'order_id'   => $order->id,
            'staff_id'   => $staff->id,
            'from_status' => 'pending',
            'to_status'  => 'designing',
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
