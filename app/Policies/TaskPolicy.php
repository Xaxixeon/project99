<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\StaffUser;

class TaskPolicy
{
    /**
     * Lihat task
     */
    public function view(StaffUser $user, Task $task): bool
    {
        if ($user->hasAnyRole(['superadmin', 'admin', 'manager'])) {
            return true;
        }

        if ($task->assigned_to === $user->id) {
            return true;
        }

        return $task->role === $user->role;
    }

    /**
     * Assign task ke staff lain
     */
    public function assign(StaffUser $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Merge beberapa task
     */
    public function merge(StaffUser $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Update status task
     */
    public function updateStatus(StaffUser $user, Task $task): bool
    {
        if ($user->hasAnyRole(['admin', 'manager'])) {
            return true;
        }

        if (
            $user->hasRole('qc') &&
            $task->status === 'waiting_approval'
        ) {
            return true;
        }

        if ($task->assigned_to === $user->id) {
            return $task->status !== 'done';
        }

        return false;
    }

    /**
     * Update deadline task
     */
    public function updateDeadline(StaffUser $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Hapus task
     */
    public function delete(StaffUser $user): bool
    {
        return $user->hasRole('admin');
    }
}
