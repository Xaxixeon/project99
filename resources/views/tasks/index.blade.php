<x-admin.layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Tugas Order</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Antrian tugas designer / operator.</p>
        </div>
    </div>

    <x-card>
        <x-table>
            <x-slot name="head">
                <th class="p-3 text-left">Order</th>
                <th class="p-3 text-left">Tugas</th>
                <th class="p-3 text-left">Role</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Aksi</th>
            </x-slot>

            @forelse($tasks as $task)
                <tr class="border-t">
                    <td class="p-3 text-slate-900 dark:text-slate-100">
                        {{ $task->order->order_code ?? '-' }}
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">
                        {{ ucfirst(str_replace('_',' ', $task->task_type ?? '-')) }}
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">
                        {{ ucfirst($task->role) }}
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">
                        {{ str_replace('_',' ', $task->status) }}
                    </td>
                    <td class="p-3">
                        <form method="POST" action="{{ route('tasks.status', $task) }}" class="flex gap-2 items-center">
                            @csrf
                            <select name="status" class="rounded border border-gray-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm">
                                @foreach(['pending','in_progress','waiting_approval','done'] as $st)
                                    <option value="{{ $st }}" @selected($task->status === $st)>{{ $st }}</option>
                                @endforeach
                            </select>
                            <button class="px-3 py-1.5 rounded bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-500">Update</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="border-t">
                    <td colspan="5" class="p-4 text-center text-slate-500 dark:text-slate-300">
                        Belum ada tugas.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $tasks->links() }}
        </div>
    </x-card>
</x-admin.layout>
