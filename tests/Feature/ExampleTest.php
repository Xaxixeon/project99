<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        Product::factory()->create([
            'is_featured' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
