<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Order;
use App\Models\Role;
use App\Models\StaffUser;
use Tests\TestCase;

class DashboardAggregationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_dashboard_shows_correct_order_aggregation(): void
    {
        /**
         * === SETUP ADMIN ===
         */
        $admin = $this->makeStaffWithRole('admin');
        $this->actingAs($admin, 'staff');

        /**
         * === SEED ORDERS ===
         */
        Order::factory()->count(3)->create(['status' => 'pending']);
        Order::factory()->count(2)->create(['status' => 'confirmed']);
        Order::factory()->count(4)->create(['status' => 'production']);
        Order::factory()->count(1)->create(['status' => 'printing']);
        Order::factory()->count(5)->create(['status' => 'completed']);
        Order::factory()->count(2)->create(['status' => 'cancelled']);

        /**
         * === HIT DASHBOARD ===
         */
        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        /**
         * === ASSERT AGGREGATION ===
         */
        $response->assertViewHas('totalOrders', 17);

        $response->assertViewHas('pendingOrders', 3);

        // Paid biasanya completed
        $response->assertViewHas('paidOrders', 5);

        // Processing = confirmed + production + printing
        $response->assertViewHas('processingOrders', 7);
    }

    /** @test */
    public function non_admin_cannot_access_admin_dashboard(): void
    {
        $staff = $this->makeStaffWithRole('designer');
        $this->actingAs($staff, 'staff');

        $this->get(route('admin.dashboard'))
            ->assertStatus(403);
    }

    /** @test */
    public function dashboard_handles_empty_orders_gracefully(): void
    {
        $admin = $this->makeStaffWithRole('admin');
        $this->actingAs($admin, 'staff');

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);

        $response->assertViewHas('totalOrders', 0);
        $response->assertViewHas('pendingOrders', 0);
        $response->assertViewHas('paidOrders', 0);
        $response->assertViewHas('processingOrders', 0);
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
