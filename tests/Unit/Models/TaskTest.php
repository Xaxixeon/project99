<?php

namespace Tests\Unit\Models;

use App\Models\Task;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskTest extends TestCase
{
    #[Test]
    public function task_has_default_status()
    {
        $task = new Task();

        $this->assertNotNull($task);
    }
}
