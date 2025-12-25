<div class="space-y-6">

    {{-- ACTION BAR --}}
    <div class="flex items-center gap-3">
        @can('merge', \App\Models\Task::class)
        <button wire:click="mergeSelected" 
            class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm">
            Merge Selected
        </button>
        @endcan
        <span class="text-sm text-slate-500">
            {{ count($selected) }} task dipilih
        </span>
    </div>

    {{-- TASK TABLE --}}
    <div class="overflow-x-auto bg-white dark:bg-slate-900 rounded-xl shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 dark:bg-slate-800">
                <tr>
                    <th class="p-3"></th>
                    <th>Task</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Deadline</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($tasks as $task)
                    <tr class="border-t">
                        <td class="p-3">
                            <input type="checkbox" wire:model="selected" value="{{ $task->id }}"
                            @cannot('merge', \App\Models\Task::class) disabled @endcannot>
                        </td>

                        <td class="font-semibold">
                            <button wire:click="toggleExpand({{ $task->id }})" class="mr-2 text-slate-400">
                                {{ in_array($task->id, $expanded) ? '▼' : '▶' }}
                            </button>

                            {{ $task->title }}

                            {{-- SUBTASK --}}
                            @if (in_array($task->id, $expanded))
                                <div class="mt-2 ml-6 space-y-1 text-xs text-slate-600">
                                    @foreach ($task->subtasks as $sub)
                                        <div class="flex justify-between">
                                            <span>- {{ $sub->description ?? $sub->task_type }}</span>
                                            <span>{{ $sub->status }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td>'tasks' => Task::with(['subtasks', 'assignee'])</td> // tambahkan assignee
                        <td>{{ $task->assignee?->name ?? 'Unassigned' }}</td>

                        <td>
                            <span
                                class="px-2 py-1 rounded text-xs
                            @if ($task->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                            @elseif($task->status === 'done') bg-green-100 text-green-700 @endif
                        ">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </td>

                        <td>{{ $task->deadline?->format('d M Y') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- COMPLETED --}}
    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
        <h3 class="font-semibold mb-2">Completed</h3>

        <ul class="text-sm text-slate-600 space-y-1">
            @foreach ($completed as $task)
                <li>✔ {{ $task->title }}</li>
            @endforeach
        </ul>
    </div>
</div>
