<x-admin.layout>
    <h2 class="text-2xl font-bold mb-2 text-white">Manager Dashboard</h2>
    <p class="text-sm text-slate-400 mb-4">
        Periode: {{ $from->format('d M Y') }} - {{ $to->format('d M Y') }}
    </p>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">Total Orders (bulan ini)</div>
            <div class="text-2xl font-bold text-white">{{ $totalOrders }}</div>
        </div>
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">Completed</div>
            <div class="text-2xl font-bold text-emerald-400">{{ $doneOrders }}</div>
        </div>
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">In Progress</div>
            <div class="text-2xl font-bold text-sky-400">{{ $inProgress }}</div>
        </div>
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">Margin</div>
            <div class="text-2xl font-bold text-amber-400">{{ $marginPercent }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">Revenue</div>
            <div class="text-2xl font-bold text-emerald-400">
                Rp {{ number_format($totalRevenue) }}
            </div>
        </div>
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">Total Cost</div>
            <div class="text-2xl font-bold text-red-400">
                Rp {{ number_format($totalCost) }}
            </div>
        </div>
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">Total Profit</div>
            <div class="text-2xl font-bold text-sky-400">
                Rp {{ number_format($totalProfit) }}
            </div>
            <div class="text-xs text-slate-500 mt-1">
                Avg per order: Rp {{ number_format($avgProfitPerOrder) }}
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-slate-900 p-4 rounded">
            <h3 class="text-lg font-semibold mb-2 text-white">Top Products by Profit</h3>
            <table class="w-full text-sm">
                @forelse($topProducts as $row)
                    <tr class="border-b border-slate-800">
                        <td class="p-2">
                            {{ $row->product?->name ?? 'Unknown' }}
                        </td>
                        <td class="p-2 text-right text-slate-300">
                            {{ $row->order_count }} orders
                        </td>
                        <td class="p-2 text-right text-emerald-400">
                            Rp {{ number_format($row->total_profit) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-3 text-center text-slate-500">Tidak ada data produk.</td>
                    </tr>
                @endforelse
            </table>
        </div>

        <div class="bg-slate-900 p-4 rounded">
            <h3 class="text-lg font-semibold mb-2 text-white">Top Customers by Revenue</h3>
            <table class="w-full text-sm">
                @forelse($topCustomers as $row)
                    <tr class="border-b border-slate-800">
                        <td class="p-2">
                            {{ $row->customer?->name ?? 'Guest' }}
                        </td>
                        <td class="p-2 text-right text-slate-300">
                            Rp {{ number_format($row->revenue) }}
                        </td>
                        <td class="p-2 text-right text-sky-400">
                            Rp {{ number_format($row->profit) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-3 text-center text-slate-500">Tidak ada data customer.</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </div>
</x-admin.layout>
