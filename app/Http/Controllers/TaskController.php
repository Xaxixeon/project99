<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\Task;
use App\Services\AuditLogService;
use App\Services\TaskSlaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * List all tasks
     */
    public function index()
    {
        $tasks = Task::with(['order', 'assignee'])
            ->latest()
            ->paginate(30);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Merge multiple tasks into one parent task
     */
    public function mergeTasks(Request $request)
    {
        $this->authorize('merge', Task::class);

        $data = $request->validate([
            'task_ids'   => ['required', 'array', 'min:2'],
            'task_ids.*' => ['exists:tasks,id'],
            'title'      => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($data) {

            // Ambil hanya task utama (bukan subtask)
            $tasks = Task::whereIn('id', $data['task_ids'])
                ->whereNull('parent_id')
                ->get();

            if ($tasks->count() < 2) {
                abort(422, 'Minimal 2 task untuk merge.');
            }

            $orderId = $tasks->pluck('order_id')->filter()->first();

            // Buat parent task
            $parentTask = Task::create([
                'order_id'    => $orderId,
                'title'       => $data['title']
                    ?? 'Merged Task - ' . now()->format('d M Y H:i'),
                'status'      => 'pending',
                'priority'    => 'medium',
                'group_key'   => 'MERGE-' . strtoupper(uniqid()),
                'description' => 'Task gabungan',
            ]);

            // Jadikan task terpilih sebagai subtask
            foreach ($tasks as $task) {
                $oldData = $task->toArray();

                $task->update([
                    'parent_id' => $parentTask->id,
                ]);

                // Ambil deadline tercepat untuk parent
                if ($task->deadline) {
                    $parentTask->deadline = $parentTask->deadline
                        ? min($parentTask->deadline, $task->deadline)
                        : $task->deadline;
                }

                // Audit child task
                AuditLogService::log(
                    'merge_task_child',
                    'Task',
                    $task->id,
                    $oldData,
                    $task->toArray()
                );
            }

            $parentTask->save();

            // Audit parent task
            AuditLogService::log(
                'merge_task',
                'Task',
                $parentTask->id,
                ['merged_task_ids' => $tasks->pluck('id')->toArray()],
                $parentTask->toArray()
            );
        });

        return back()->with('success', 'Task berhasil digabung.');
    }

    /**
     * Update task status, SLA, audit log, and sync order status
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('updateStatus', $task);

        // Simpan kondisi awal untuk audit
        $oldData = [
            'status'      => $task->status,
            'assigned_to' => $task->assigned_to,
            'sla_status'  => $task->sla_status,
        ];

        $data = $request->validate([
            'status' => ['required', 'in:pending,in_progress,waiting_approval,done'],
        ]);

        $user      = auth('staff')->user();
        $newStatus = $data['status'];

        /**
         * === UPDATE TASK ===
         */
        $task->status      = $newStatus;
        $task->assigned_to = $task->assigned_to ?? $user->id;

        if ($newStatus === 'in_progress' && !$task->started_at) {
            $task->started_at = now();
        }

        if ($newStatus === 'done') {
            $task->completed_at = now();
        }

        // Hitung SLA
        $task->sla_status = TaskSlaService::calculate($task);
        $task->save();

        // Audit task update
        AuditLogService::log(
            'update_task_status',
            'Task',
            $task->id,
            $oldData,
            $task->toArray()
        );

        /**
         * === SYNC ORDER STATUS ===
         */
        $order = $task->order;

        if ($order) {
            $previousStatus = $order->order_status;
            $newOrderStatus = $previousStatus;

            // DESIGN FLOW
            if ($task->task_type === 'create_design') {
                if ($newStatus === 'in_progress') {
                    $newOrderStatus = 'designing';
                } elseif ($newStatus === 'done') {
                    $newOrderStatus = 'ready_to_print';

                    // Auto-create print task jika belum ada
                    if (!$order->tasks()->where('task_type', 'print_job')->exists()) {
                        Task::create([
                            'order_id'    => $order->id,
                            'role'        => 'operator',
                            'task_type'   => 'print_job',
                            'status'      => 'pending',
                            'description' => 'Cetak setelah desain siap.',
                        ]);
                    }
                }
            }

            // PRINT FLOW
            if ($task->task_type === 'print_job') {
                if ($newStatus === 'in_progress') {
                    $newOrderStatus = 'printing';
                } elseif ($newStatus === 'done') {
                    $newOrderStatus = 'finishing';

                    if (!$order->tasks()->where('task_type', 'finishing')->exists()) {
                        $newOrderStatus = 'done';
                    }
                }
            }

            // Simpan perubahan order jika ada perubahan status
            if ($previousStatus !== $newOrderStatus) {
                $order->update([
                    'order_status' => $newOrderStatus,
                    'status'       => $newOrderStatus, // backward compatibility
                ]);

                OrderStatusLog::create([
                    'order_id'        => $order->id,
                    'previous_status' => $previousStatus,
                    'new_status'      => $newOrderStatus,
                    'changed_by'      => $user->id,
                ]);
            }
        }

        return back()->with('success', 'Status tugas diperbarui.');
    }
}
