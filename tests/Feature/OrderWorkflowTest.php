<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Role;
use App\Models\StaffUser;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function full_order_workflow_until_completed(): void
    {
        /**
         * === SETUP STAFF ===
         */
        $designer = $this->makeStaffWithRole('designer');
        $operator = $this->makeStaffWithRole('operator');
        $qc       = $this->makeStaffWithRole('qc');

        /**
         * === CREATE ORDER ===
         */
        $order = Order::factory()->create([
            'status' => 'pending',
        ]);

        /**
         * === CREATE DESIGN TASK ===
         */
        $designTask = Task::factory()->create([
            'order_id'    => $order->id,
            'task_type'   => 'create_design',
            'assigned_to' => $designer->id,
            'status'      => 'pending',
        ]);

        /**
         * === DESIGNER START DESIGN ===
         */
        $this->actingAs($designer, 'staff')
            ->post(route('tasks.status', $designTask), [
                'status' => 'in_progress',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id'           => $order->id,
            'order_status' => 'designing',
        ]);

        /**
         * === DESIGNER FINISH DESIGN ===
         */
        $this->actingAs($designer, 'staff')
            ->post(route('tasks.status', $designTask), [
                'status' => 'done',
            ])
            ->assertRedirect();

        // Print task auto-created
        $printTask = Task::where('order_id', $order->id)
            ->where('task_type', 'print_job')
            ->first();

        $this->assertNotNull($printTask);

        /**
         * === OPERATOR START PRINT ===
         */
        $this->actingAs($operator, 'staff')
            ->post(route('tasks.status', $printTask), [
                'status' => 'in_progress',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id'           => $order->id,
            'order_status' => 'printing',
        ]);

        /**
         * === OPERATOR FINISH PRINT ===
         */
        $this->actingAs($operator, 'staff')
            ->post(route('tasks.status', $printTask), [
                'status' => 'waiting_approval',
            ])
            ->assertRedirect();

        /**
         * === QC APPROVE ===
         */
        $this->actingAs($qc, 'staff')
            ->post(route('tasks.status', $printTask), [
                'status' => 'done',
            ])
            ->assertRedirect();

        /**
         * === FINAL ASSERT ===
         */
        $this->assertDatabaseHas('orders', [
            'id'           => $order->id,
            'order_status' => 'done',
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
