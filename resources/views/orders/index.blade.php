<x-admin.layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Daftar Pesanan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Kelola pesanan dan status produksi.</p>
        </div>
        <a href="{{ route('orders.create') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-500">
            + Buat Order
        </a>
    </div>

    <x-card>
        <x-table>
            <x-slot name="head">
                <th class="p-3 text-left">Order</th>
                <th class="p-3 text-left">Customer</th>
                <th class="p-3 text-left">Total</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Tanggal</th>
            </x-slot>

            @forelse($orders as $order)
                <tr class="border-t">
                    <td class="p-3 font-semibold text-slate-900 dark:text-slate-100">
                        {{ $order->order_code }}
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">
                        {{ $order->customer->name ?? '-' }}
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">
                        Rp {{ number_format($order->total ?? $order->total_price ?? 0) }}
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200 capitalize">
                        {{ $order->status ?? 'pending' }}
                    </td>
                    <td class="p-3 text-slate-700 dark:text-slate-200">
                        {{ optional($order->created_at)->format('d M Y') }}
                    </td>
                </tr>
            @empty
                <tr class="border-t">
                    <td colspan="5" class="p-4 text-center text-slate-500 dark:text-slate-300">
                        Belum ada pesanan.
                    </td>
                </tr>
            @endforelse
        </x-table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </x-card>
</x-admin.layout>
