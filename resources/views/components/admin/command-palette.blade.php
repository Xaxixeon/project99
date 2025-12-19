<div 
    x-data="{ open: false, query: '', commands: [
        {name: 'Dashboard', url: '{{ route('dashboard.admin') }}'},
        {name: 'Staff Management', url: '{{ route('admin.staff.index') }}'},
        {name: 'Customers', url: '{{ route('admin.customers.index') }}'},
        {name: 'Instansi', url: '{{ route('admin.instansi.index') }}'},
        {name: 'Member Types', url: '{{ route('admin.member.index') }}'},
        {name: 'Pricing', url: '{{ route('admin.pricing.index') }}'},
        {name: 'Orders', url: '{{ route('orders.index') }}'},
    ] }"

    x-init="
        document.addEventListener('keydown', e => {
            if (e.ctrlKey && e.key === 'k') {
                e.preventDefault();
                open = true;
                $nextTick(() => { $refs.search.focus(); });
            }
            if (e.key === 'Escape') open = false;
        });
    "
>

    {{-- Overlay --}}
    <div 
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black/40 z-40"
        @click="open = false">
    </div>

    {{-- Modal --}}
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 flex items-start justify-center pt-32 z-50"
    >
        <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-xl shadow-xl p-6">

            <input 
                x-ref="search"
                x-model="query"
                type="text" 
                placeholder="Search command..."
                class="w-full px-4 py-2 border rounded bg-gray-100 dark:bg-gray-700 dark:text-gray-200"
            >

            <div class="mt-4 max-h-64 overflow-y-auto">
                <template x-for="cmd in commands.filter(c => c.name.toLowerCase().includes(query.toLowerCase()))">
                    <a 
                        :href="cmd.url"
                        class="block px-4 py-2 rounded hover:bg-gray-200 dark:hover:bg-gray-700"
                        x-text="cmd.name"
                    ></a>
                </template>

                <p x-show="commands.filter(c => c.name.toLowerCase().includes(query.toLowerCase())).length === 0"
                   class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">
                    No results found
                </p>
            </div>

        </div>
    </div>

</div>
