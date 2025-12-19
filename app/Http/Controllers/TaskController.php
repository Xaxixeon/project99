<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['order', 'assignee'])
            ->latest()
            ->paginate(30);

        return view('tasks.index', compact('tasks'));
    }

    public function updateStatus(Request $request, Task $task)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,waiting_approval,done',
        ]);

        $task->status = $data['status'];
        $task->assigned_to = $task->assigned_to ?? Auth::id();
        $task->save();

        // Update order status based on task type/status (simple rules)
        $order = $task->order;
        $prev  = $order->order_status;
        $new   = $prev;

        if ($task->task_type === 'create_design') {
            if ($task->status === 'in_progress') {
                $new = 'designing';
            } elseif ($task->status === 'done') {
                $new = 'ready_to_print';
                // optionally auto create print task if none
                if (!$order->tasks()->where('task_type', 'print_job')->exists()) {
                    Task::create([
                        'order_id'  => $order->id,
                        'role'      => 'operator',
                        'task_type' => 'print_job',
                        'status'    => 'pending',
                        'description' => 'Cetak setelah desain siap.',
                    ]);
                }
            }
        }

        if ($task->task_type === 'print_job') {
            if ($task->status === 'in_progress') {
                $new = 'printing';
            } elseif ($task->status === 'done') {
                $new = 'finishing';
                // mark done if no finishing tasks
                if (!$order->tasks()->where('task_type', 'finishing')->exists()) {
                    $new = 'done';
                }
            }
        }

        if ($prev !== $new) {
            $order->order_status = $new;
            $order->status = $new; // compatibility
            $order->save();

            OrderStatusLog::create([
                'order_id'        => $order->id,
                'previous_status' => $prev,
                'new_status'      => $new,
                'changed_by'      => Auth::id(),
            ]);
        }

        return back()->with('success', 'Status tugas diperbarui.');
    }
}
