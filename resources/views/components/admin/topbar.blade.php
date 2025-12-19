@php
    use Illuminate\Support\Facades\Auth;
    $staff = Auth::guard('staff')->user();
    $roleName = $staff?->roles()->first()->name ?? '-';
@endphp

<header class="w-full bg-white dark:bg-gray-800 shadow px-6 py-3 flex justify-between items-center">

    <div>
        <h1 class="text-2xl font-semibold">
            {{ ucfirst( last(explode('.', request()->route()->getName())) ) }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Logged in as: {{ $staff?->name }} ({{ $roleName }})
        </p>
    </div>

    <div class="flex items-center space-x-4" x-data="{ openNotif:false, notif:[], searchResults:[], searching:false, query:'', init(){ setInterval(() => { fetch('{{ route('admin.notif.poll') }}').then(res => res.json()).then(data => { this.notif = data.map(n => ({ title: n.action ?? 'Activity', time: new Date(n.created_at).toLocaleTimeString() })); }); }, 5000); } }" x-init="init()">

        {{-- Global Search (AJAX) --}}
        <div class="relative">
            <input
                type="text"
                placeholder="Search orders, customers..."
                class="px-3 py-2 rounded border bg-gray-100 dark:bg-gray-700 dark:text-gray-200 text-sm w-60"
                x-model="query"
                @input.debounce.500ms="
                    if(query.length < 2){ searchResults=[]; return; }
                    searching = true;
                    fetch('{{ route('admin.search') }}?q=' + encodeURIComponent(query))
                        .then(r => r.json())
                        .then(data => { searchResults = data; })
                        .finally(() => searching=false);
                "
            />
            <div
                class="absolute mt-1 w-80 bg-white dark:bg-gray-800 rounded shadow-lg z-30 text-sm"
                x-show="searchResults.length > 0"
            >
                <template x-for="item in searchResults" :key="item.url">
                    <a :href="item.url"
                       class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span class="font-semibold" x-text="item.type"></span> -
                        <span x-text="item.label"></span>
                    </a>
                </template>
            </div>
        </div>

        {{-- Compact Mode --}}
        <button @click="toggleCompact()"
                class="px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">
            🗜️
        </button>

        {{-- Dark Mode Toggle --}}
        <button @click="toggleTheme()"
                class="px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 text-sm">
            🌓
        </button>

        {{-- Notifications --}}
        <div class="relative">
            <button @click="openNotif = !openNotif"
                    class="relative px-3 py-2 rounded bg-gray-100 dark:bg-gray-700">
                🔔
                <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1"
                      x-show="notif.length > 0">
                    <span x-text="notif.length"></span>
                </span>
            </button>

            <div x-show="openNotif"
                 @click.outside="openNotif=false"
                 class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 shadow-lg rounded-lg z-40 text-sm">
                <div class="px-4 py-2 border-b dark:border-gray-700 font-semibold">
                    Notifications
                </div>
                <div class="max-h-64 overflow-y-auto">
                    <template x-for="n in notif">
                        <div class="px-4 py-2 border-b dark:border-gray-700">
                            <div x-text="n.title"></div>
                            <div class="text-xs text-gray-500" x-text="n.time"></div>
                        </div>
                    </template>
                    <p class="px-4 py-3 text-xs text-gray-500" x-show="notif.length === 0">
                        No notifications
                    </p>
                </div>
            </div>
        </div>

        {{-- Avatar / Logout --}}
        <div class="flex items-center space-x-2">
            <div
                class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm">
                {{ strtoupper(substr($staff?->name ?? 'A', 0, 1)) }}
            </div>

            <form action="{{ route('staff.logout') }}" method="POST">
                @csrf
                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                    Logout
                </button>
            </form>
        </div>
    </div>

</header>
