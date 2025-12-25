<?php

namespace App\Services;

use App\Models\Task;
use Carbon\Carbon;

class TaskSlaService
{
    public static function calculate(Task $task): ?string
    {
        if (!$task->deadline) {
            return null;
        }

        // Task selesai
        if ($task->status === 'done') {
            return $task->completed_at && $task->completed_at <= $task->deadline
                ? 'completed_on_time'
                : 'completed_late';
        }

        $now = Carbon::now();

        if ($now->greaterThan($task->deadline)) {
            return 'breached';
        }

        if ($now->diffInHours($task->deadline, false) <= 24) {
            return 'at_risk';
        }

        return 'on_track';
    }
}
