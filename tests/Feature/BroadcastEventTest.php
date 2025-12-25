<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Events\OrderUpdated;
use App\Models\Order;
use App\Models\Role;
use App\Models\StaffUser;
use App\Models\Task;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BroadcastEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_updated_event_is_broadcast_when_task_status_changes(): void
    {
        Event::fake();

        /**
         * === SETUP STAFF ===
         */
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        /**
         * === CREATE ORDER + TASK ===
         */
        $order = Order::factory()->create(['status' => 'pending']);

        $task = Task::factory()->create([
            'order_id'    => $order->id,
            'assigned_to' => $staff->id,
            'status'      => 'pending',
        ]);

        /**
         * === UPDATE TASK STATUS (TRIGGER EVENT) ===
         */
        $this->post(route('tasks.status', $task), [
            'status' => 'in_progress',
        ])->assertRedirect();

        /**
         * === ASSERT EVENT BROADCASTED ===
         */
        Event::assertDispatched(OrderUpdated::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });
    }

    /** @test */
    public function order_updated_event_uses_correct_broadcast_channel(): void
    {
        $order = Order::factory()->make();

        $event = new OrderUpdated($order);

        $channels = $event->broadcastOn();

        $this->assertNotEmpty($channels);

        $this->assertEquals(
            'dashboard',
            $channels[0]->name
        );
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
