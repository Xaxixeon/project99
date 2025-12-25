<x-admin.layout>
    <div class="p-6 space-y-6">

        <h1 class="text-2xl font-bold">Dashboard Overview</h1>

        {{-- KPI --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ($kpi as $label => $value)
                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow">
                    <div class="text-xs text-slate-500 uppercase">
                        {{ str_replace('_', ' ', $label) }}
                    </div>
                    <div class="text-2xl font-bold">{{ $value }}</div>
                </div>
            @endforeach
        </div>

        {{-- TASK STATUS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow">
                <h3 class="font-semibold mb-3">Task Status</h3>
                <ul class="space-y-1 text-sm">
                    @foreach ($taskStatus as $status => $total)
                        <li class="flex justify-between">
                            <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                            <span>{{ $total }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow">
                <h3 class="font-semibold mb-3">Order Pipeline</h3>
                <ul class="space-y-1 text-sm">
                    @foreach ($orderFlow as $status => $total)
                        <li class="flex justify-between">
                            <span>{{ ucfirst($status) }}</span>
                            <span>{{ $total }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- TASK BY DIVISION --}}
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow">
            <h3 class="font-semibold mb-3">Task per Divisi</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                @foreach ($byDivision as $div => $total)
                    <div class="p-3 bg-slate-100 dark:bg-slate-800 rounded">
                        {{ ucfirst($div ?? 'unknown') }}<br>
                        <strong>{{ $total }}</strong>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DEADLINE ALERT --}}
        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl">
            <h3 class="font-semibold text-red-700 mb-2">⚠ Deadline Mendekat</h3>
            <ul class="text-sm space-y-1">
                @forelse($deadline as $task)
                    <li>
                        {{ $task->description ?? $task->task_type }}
                        <span class="text-xs text-red-500">
                            ({{ $task->deadline?->format('d M') }})
                        </span>
                    </li>
                @empty
                    <li class="text-slate-500">Tidak ada</li>
                @endforelse
            </ul>
        </div>

    </div>
</x-admin.layout>
