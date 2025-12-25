<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EnumFactorySafetyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function factories_do_not_violate_enum_or_check_constraints(): void
    {
        // Jalankan semua factory penting
        $this->assertFactorySafe(\App\Models\Task::class);
        $this->assertFactorySafe(\App\Models\Order::class);
        // Tambah model lain di sini jika perlu
    }

    /**
     * Assert satu model factory aman terhadap ENUM / CHECK
     */
    protected function assertFactorySafe(string $modelClass, int $times = 5): void
    {
        $model = new $modelClass;
        $table = $model->getTable();

        for ($i = 0; $i < $times; $i++) {
            try {
                $modelClass::factory()->create();
            } catch (\Throwable $e) {
                $this->fail(
                    "Factory for {$modelClass} violated ENUM/CHECK constraint.\n"
                        . "Table: {$table}\n"
                        . "Error: {$e->getMessage()}"
                );
            }
        }

        $this->assertTrue(true); // lolos tanpa exception
    }
}

