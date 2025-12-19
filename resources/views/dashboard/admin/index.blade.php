<x-admin.layout>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-stat-card title="Total Order" value="{{ $totalOrders }}" bg="blue" :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-6 w-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 7h18M3 12h18M3 17h18\' /></svg>'" />
        <x-stat-card title="Produksi Berjalan" value="{{ $production }}" bg="yellow" :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-6 w-6\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path d=\'M13 10V3L4 14h7v7l9-11h-7z\' /></svg>'" />
        <x-stat-card title="Pending Pembayaran" value="{{ $pendingPayments }}" bg="red" :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-6 w-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path d=\'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'" />
        <x-stat-card title="User Aktif" value="{{ $totalUsers }}" bg="green" :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-6 w-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path d=\'M17 20h5V4H2v16h5\'/><path d=\'M12 11a3 3 0 100-6 3 3 0 000 6zM2 20a4 4 0 018 0\'/></svg>'" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <x-stat-card title="Total Paid" value="Rp {{ number_format($invoicesTotalPaid ?? 0) }}" bg="emerald" :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-6 w-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path d=\'M4 6h16M4 10h16M4 14h16M4 18h16\'/></svg>'" />
        <x-stat-card title="Total Unpaid" value="Rp {{ number_format($invoicesUnpaid ?? 0) }}" bg="red" :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-6 w-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path d=\'M6 18L18 6M6 6l12 12\'/></svg>'" />
        <x-stat-card title="Revenue This Month" value="Rp {{ number_format($monthlyRevenue ?? 0) }}" bg="sky" :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' class=\'h-6 w-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path d=\'M4 12l4 4 8-8\'/></svg>'" />
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
        <x-card class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Kelola Pesanan</p>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Order & Tracking</h3>
            </div>
            <a href="{{ route('orders.index') }}"
               class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500">
                Buka Pesanan
            </a>
        </x-card>
        <x-card class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Pembayaran</p>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Invoice & Pelunasan</h3>
            </div>
            <a href="{{ route('invoices.index') }}"
               class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-500">
                Kelola Payment
            </a>
        </x-card>
        <x-card class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Produk</p>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Produk & Harga</h3>
            </div>
            <a href="{{ route('admin.products.index') }}"
               class="px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-semibold hover:bg-sky-500">
                Kelola Produk
            </a>
        </x-card>
    </div>

    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-4">Order Terbaru</h2>
        <x-card>
            <x-table>
                <x-slot name="head">
                    <th class="p-3 text-left">Order</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </x-slot>

                @foreach ($recentOrders as $order)
                    <tr class="border-t">
                        <td class="p-3">{{ $order->order_code }}</td>
                        <td class="p-3">{{ $order->customer->name ?? '-' }}</td>
                        <td class="p-3">Rp {{ number_format($order->total) }}</td>
                        <td class="p-3">
                            <x-order-status status="{{ $order->status }}" />
                        </td>
                        <td class="p-3">
                            <a href="/order/{{ $order->id }}" class="text-blue-600 underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>
</x-admin.layout>
