@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;

    $staff = Auth::guard('staff')->user();
    $roleName = $staff?->roles()->first()->name ?? null;

    $menu = [
        ['heading' => 'Akun'],
        ['name' => 'Daftar User', 'route' => 'admin.customers.index', 'icon' => 'user-group', 'roles' => ['superadmin','admin','marketing']],
        ['name' => 'Daftar Staff', 'route' => 'admin.staff.index', 'icon' => 'users', 'roles' => ['superadmin']],

        ['heading' => 'Gudang'],
        ['name' => 'Data Produk', 'route' => 'admin.products.index', 'icon' => 'cube', 'roles' => ['superadmin','admin']],
        ['name' => 'Kategori Produk', 'route' => null, 'icon' => 'folder', 'roles' => ['superadmin','admin']],

        ['heading' => 'Harga'],
        ['name' => 'Harga Produk', 'route' => 'admin.pricing.index', 'icon' => 'tag', 'roles' => ['superadmin','admin','marketing']],
        ['name' => 'Harga Tier Member', 'route' => 'admin.member.index', 'icon' => 'identification', 'roles' => ['superadmin','admin','marketing']],

        ['heading' => 'Orderan'],
        ['name' => 'Purchase Order', 'route' => 'orders.index', 'icon' => 'shopping-bag', 'roles' => ['superadmin','admin']],
        ['name' => 'Tugas Order', 'route' => null, 'icon' => 'clipboard', 'roles' => ['superadmin','admin']],

        ['heading' => 'Metode Pembayaran'],
        ['name' => 'Metode Pembayaran', 'route' => 'invoices.index', 'icon' => 'credit-card', 'roles' => ['superadmin','admin','cashier']],

        ['heading' => 'Keuangan (COA)'],
        ['name' => 'Pendapatan Harian', 'route' => null, 'icon' => 'chart', 'roles' => ['superadmin','admin','manager']],
        ['name' => 'Laporan Pembayaran', 'route' => null, 'icon' => 'receipt', 'roles' => ['superadmin','admin','manager']],
        ['name' => 'Pengeluaran', 'route' => null, 'icon' => 'shopping-cart', 'roles' => ['superadmin','admin','manager']],

        ['heading' => 'Riwayat'],
        ['name' => 'Riwayat Pesanan', 'route' => null, 'icon' => 'history', 'roles' => ['superadmin','admin']],
        ['name' => 'Riwayat Pembayaran', 'route' => null, 'icon' => 'history', 'roles' => ['superadmin','admin','cashier']],
        ['name' => 'Log Aktivitas Staff', 'route' => null, 'icon' => 'clipboard', 'roles' => ['superadmin','admin']],

        ['heading' => 'Pengaturan Laman'],
        ['name' => 'Jadwal Konten', 'route' => null, 'icon' => 'calendar', 'roles' => ['superadmin','admin','marketing']],
        ['name' => 'Event & Promo', 'route' => null, 'icon' => 'sparkles', 'roles' => ['superadmin','admin','marketing']],
        ['name' => 'Banner / Gambar', 'route' => null, 'icon' => 'image', 'roles' => ['superadmin','admin','marketing']],
        ['name' => 'Konten Lainnya', 'route' => null, 'icon' => 'book-open', 'roles' => ['superadmin','admin','marketing']],

        ['heading' => 'Pengaturan Sistem'],
        ['name' => 'System Overview', 'route' => 'admin.system', 'icon' => 'chip', 'roles' => ['superadmin']],

        ['heading' => 'Tentang Aplikasi'],
        ['name' => 'Tentang', 'route' => null, 'icon' => 'info', 'roles' => ['superadmin','admin']],
    ];

    $menu = array_filter($menu, function ($item) use ($roleName) {
        if (isset($item['heading'])) return true;
        return in_array($roleName, $item['roles'] ?? []);
    });
@endphp

<aside 
    class="bg-[#111827] dark:bg-gray-900 text-gray-300 min-h-screen
           transition-all duration-200 border-r border-gray-800"
    :class="sidebarOpen ? 'w-64' : 'w-20'">

    {{-- Header --}}
    <div class="p-5 flex items-center justify-between">
        <span class="font-bold text-xl tracking-wide" x-show="sidebarOpen">Xeon Panel</span>
        <button @click="toggleSidebar"
                class="text-gray-400 hover:text-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                 :class="sidebarOpen ? '' : 'rotate-180'" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 19l-7-7 7-7" />
            </svg>
        </button>
    </div>

    {{-- MENU --}}
    <nav class="mt-4 space-y-1">

        @foreach ($menu as $item)
            @if(isset($item['heading']))
                <div class="px-5 py-2 text-[11px] uppercase tracking-wide text-gray-500">{{ $item['heading'] }}</div>
                @continue
            @endif
            @if($item['route'] && !Route::has($item['route'])) @continue @endif
            @php $active = $item['route'] ? request()->routeIs($item['route']) : false; @endphp

            @if($item['route'])
                <a href="{{ route($item['route']) }}"
                   class="
                       flex items-center px-4 py-3 mx-2 rounded-lg
                       transition-all duration-150 cursor-pointer
                       hover:bg-gray-800 hover:text-white
                       {{ $active ? 'bg-indigo-600 text-white' : '' }}
                   ">
                    <x-admin.icon :name="$item['icon']" class="w-6 h-6 flex-none" />
                    <span class="ml-3 text-sm font-medium whitespace-nowrap"
                          x-show="sidebarOpen">
                        {{ $item['name'] }}
                    </span>
                </a>
            @else
                <div class="flex items-center px-4 py-3 mx-2 rounded-lg text-gray-500 bg-gray-800/30 cursor-not-allowed">
                    <x-admin.icon :name="$item['icon']" class="w-6 h-6 flex-none" />
                    <span class="ml-3 text-sm font-medium whitespace-nowrap" x-show="sidebarOpen">
                        {{ $item['name'] }} <span class="text-[10px] text-gray-500">(soon)</span>
                    </span>
                </div>
            @endif

        @endforeach
    </nav>
</aside>
