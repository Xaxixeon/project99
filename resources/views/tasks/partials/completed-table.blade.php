<div class="overflow-x-auto opacity-80">
<table class="min-w-full text-sm">
    <thead class="bg-slate-100 dark:bg-slate-800">
        <tr>
            <th class="p-3 text-left">Task</th>
            <th>Owner</th>
            <th>Completed At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($completed as $task)
        <tr class="border-t">
            <td class="p-3">{{ $task->title }}</td>
            <td>{{ $task->owner->name ?? '-' }}</td>
            <td>{{ $task->updated_at->format('d M Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
