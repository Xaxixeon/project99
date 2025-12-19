<x-admin.layout>
    <h2 class="text-2xl font-bold mb-4 text-white">Finance Summary</h2>

    <form method="GET" class="flex gap-2 mb-4">
        <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
            class="px-2 py-1 rounded bg-slate-900 border-slate-700 text-sm text-white">
        <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
            class="px-2 py-1 rounded bg-slate-900 border-slate-700 text-sm text-white">
        <button class="px-3 py-1 rounded bg-sky-600 text-sm text-white">
            Filter
        </button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-900 p-4 rounded">
            <div class="text-xs text-slate-400">Total Revenue</div>
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
        </div>
    </div>

    <h3 class="text-xl font-semibold mb-2 text-white">By Product</h3>
    <table class="w-full bg-slate-900 rounded text-sm mb-6">
        <tr class="border-b border-slate-700 text-slate-300">
            <th class="p-2 text-left">Product</th>
            <th class="p-2 text-right">Revenue</th>
            <th class="p-2 text-right">Profit</th>
        </tr>
        @foreach($byProduct as $row)
            <tr class="border-b border-slate-800">
                <td class="p-2">{{ $row->product?->name ?? 'Unknown' }}</td>
                <td class="p-2 text-right">Rp {{ number_format($row->revenue) }}</td>
                <td class="p-2 text-right">Rp {{ number_format($row->profit) }}</td>
            </tr>
        @endforeach
    </table>

    <h3 class="text-xl font-semibold mb-2 text-white">By Customer</h3>
    <table class="w-full bg-slate-900 rounded text-sm">
        <tr class="border-b border-slate-700 text-slate-300">
            <th class="p-2 text-left">Customer</th>
            <th class="p-2 text-right">Revenue</th>
            <th class="p-2 text-right">Profit</th>
        </tr>
        @foreach($byCustomer as $row)
            <tr class="border-b border-slate-800">
                <td class="p-2">{{ $row->customer?->name ?? 'Guest' }}</td>
                <td class="p-2 text-right">Rp {{ number_format($row->revenue) }}</td>
                <td class="p-2 text-right">Rp {{ number_format($row->profit) }}</td>
            </tr>
        @endforeach
    </table>
</x-admin.layout>
