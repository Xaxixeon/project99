<x-admin.layout>
    <div class="p-4 space-y-6">
        <livewire:gose-task-board />
        
        {{-- HEADER --}}
        @include('tasks.partials.header')

        {{-- PRE ORDER --}}
        <section class="bg-white dark:bg-slate-900 rounded-xl shadow">
            <h2 class="px-4 py-3 font-semibold text-slate-800 dark:text-white">
                Pre-Order (Belum Dikerjakan)
            </h2>
            @include('tasks.partials.preorder-table')
        </section>

        {{-- TASK MANAGEMENT --}}
        <section class="bg-white dark:bg-slate-900 rounded-xl shadow">
            <h2 class="px-4 py-3 font-semibold text-slate-800 dark:text-white">
                Task Management
            </h2>
            @include('tasks.partials.task-table')
        </section>

        {{-- COMPLETED --}}
        <section class="bg-white dark:bg-slate-900 rounded-xl shadow">
            <h2 class="px-4 py-3 font-semibold text-slate-800 dark:text-white">
                Completed
            </h2>
            @include('tasks.partials.completed-table')
        </section>
    </div>
    
</x-admin.layout>
