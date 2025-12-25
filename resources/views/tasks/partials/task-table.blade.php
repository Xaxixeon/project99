<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-100 dark:bg-slate-800">
            <tr>
                <th class="p-3">
                    <input type="checkbox">
                </th>
                <th>Task</th>
                <th>Owner</th>
                <th>Status</th>
                <th>Divisi</th>
                <th>Deadline</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr class="border-t bg-white dark:bg-slate-900">
                    <td class="p-3">
                        <input type="checkbox" name="merge[]">
                    </td>
                    <td class="font-semibold">
                        {{ $task->title }}

                        {{-- SUBTASK --}}
                        @if ($task->subtasks->count())
                            <ul class="ml-4 mt-2 text-xs text-slate-500">
                                @foreach ($task->subtasks as $sub)
                                    <li>- {{ $sub->title }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td>{{ $task->owner->name ?? '-' }}</td>
                    <td>
                        <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700">
                            {{ ucfirst($task->status) }}
                        </span>
                    </td>
                    <td>{{ $task->division }}</td>
                    <td>{{ optional($task->deadline)->format('d M Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="p-3">
    <button class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm">
        Merge Selected Task
    </button>
</div>
