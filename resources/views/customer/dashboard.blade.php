<x-customer.layout>

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold mb-1 text-white">Welcome, {{ $customer->name }}</h2>
            <p class="text-slate-400 text-sm">Kelola pesanan dan lihat riwayat transaksi kamu.</p>
        </div>
        @if($customer->memberType)
            <span class="px-3 py-2 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-300 border border-emerald-400/30">
                {{ $customer->memberType->name }} Tier
            </span>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass rounded-xl border border-white/10 p-4 shadow-lg shadow-black/30">
            <div class="text-slate-400 text-sm">Total Orders</div>
            <div class="text-3xl font-bold text-white mt-1">{{ $customer->orders()->count() }}</div>
        </div>

        <div class="glass rounded-xl border border-white/10 p-4 shadow-lg shadow-black/30">
            <div class="text-slate-400 text-sm">Orders in Progress</div>
            <div class="text-3xl font-bold text-white mt-1">
                {{ $customer->orders()->whereNotIn('status',['completed'])->count() }}
            </div>
        </div>

        <div class="glass rounded-xl border border-white/10 p-4 shadow-lg shadow-black/30">
            <div class="text-slate-400 text-sm">Completed Orders</div>
            <div class="text-3xl font-bold text-white mt-1">
                {{ $customer->orders()->where('status','completed')->count() }}
            </div>
        </div>
    </div>

    <div class="glass rounded-xl border border-white/10 p-5 shadow-lg shadow-black/30">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-white">Recent Orders</h3>
            <a href="{{ route('customer.orders.index') }}" class="text-xs text-indigo-300 hover:text-indigo-200">Lihat semua</a>
        </div>

        <div class="overflow-hidden rounded-lg border border-white/10">
            <table class="w-full text-sm text-slate-200">
                <tr class="bg-white/5 text-slate-300 uppercase text-xs tracking-wide">
                    <th class="p-3 text-left">Order #</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3"></th>
                </tr>

                @foreach($recentOrders as $order)
                    <tr class="border-t border-white/5 hover:bg-white/5">
                        <td class="p-3 font-semibold">#{{ $order->id }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded-full text-xs bg-indigo-500/10 text-indigo-200 border border-indigo-400/30 capitalize">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-3">
                            Rp {{ number_format($order->total_price ?? $order->total ?? 0) }}
                        </td>
                        <td class="p-3">{{ $order->created_at?->format('d M Y') }}</td>
                        <td class="p-3 text-right">
                            <a href="{{ route('customer.orders.show', $order) }}" class="text-indigo-300 hover:text-indigo-100 text-xs">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>

            @if($recentOrders->isEmpty())
                <div class="p-4 text-slate-400 text-sm">Belum ada pesanan.</div>
            @endif
        </div>
    </div>

</x-customer.layout>
