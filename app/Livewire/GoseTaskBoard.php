<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class GoseTaskBoard extends Component
{
    public array $selected = [];
    public array $expanded = [];

    protected $listeners = [
        'taskUpdated' => '$refresh',
    ];

    public function toggleExpand(int $taskId): void
    {
        if (in_array($taskId, $this->expanded)) {
            $this->expanded = array_diff($this->expanded, [$taskId]);
        } else {
            $this->expanded[] = $taskId;
        }
    }

    public function mergeSelected(): void
    {
        if (count($this->selected) < 2) {
            $this->dispatch('notify', type: 'error', message: 'Pilih minimal 2 task');
            return;
        }

        DB::transaction(function () {

            $tasks = Task::whereIn('id', $this->selected)
                ->whereNull('parent_id')
                ->get();

            if ($tasks->count() < 2) {
                return;
            }

            $parent = Task::create([
                'order_id'   => $tasks->pluck('order_id')->filter()->first(),
                'title'      => 'Merged Task',
                'status'     => 'pending',
                'priority'   => 'medium',
                'group_key'  => 'MERGE-' . strtoupper(uniqid()),
                'description' => 'Task gabungan',
            ]);

            foreach ($tasks as $task) {
                $task->update(['parent_id' => $parent->id]);
            }
        });

        $this->selected = [];
        $this->dispatch('notify', type: 'success', message: 'Task berhasil digabung');
    }

    public function render()
    {
        return view('livewire.gose-task-board', [
            'tasks' => Task::with('subtasks.assignee')
                ->whereNull('parent_id')
                ->where('status', '!=', 'done')
                ->latest()
                ->get(),

            'completed' => Task::where('status', 'done')
                ->whereNull('parent_id')
                ->latest()
                ->get(),
        ]);
    }
}
