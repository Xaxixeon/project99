<div class="overflow-x-auto">
    <table class="min-w-full bg-white dark:bg-slate-900 border dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100">
        <thead class="bg-gray-100 dark:bg-slate-800">
            <tr>
                {{ $head }}
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
