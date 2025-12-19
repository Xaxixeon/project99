<x-admin.layout>
    <h2 class="text-2xl font-bold mb-4 text-white">Invoices</h2>

    <table class="w-full bg-slate-900 rounded text-sm">
        <tr class="border-b border-slate-700 text-slate-300">
            <th class="p-2 text-left">Invoice #</th>
            <th class="p-2 text-left">Order #</th>
            <th class="p-2 text-left">Customer</th>
            <th class="p-2 text-right">Amount</th>
            <th class="p-2 text-center">Status</th>
            <th class="p-2 text-center">Action</th>
        </tr>

        @foreach($invoices as $inv)
            <tr class="border-b border-slate-800">
                <td class="p-2">{{ $inv->invoice_no }}</td>
                <td class="p-2">#{{ $inv->order_id }}</td>
                <td class="p-2">
                    {{ $inv->order?->customer?->name ?? $inv->order?->customer_name ?? '-' }}
                </td>
                <td class="p-2 text-right">
                    Rp {{ number_format($inv->amount) }}
                </td>
                <td class="p-2 text-center">
                    @if($inv->status === 'paid')
                        <span class="px-2 py-1 rounded text-xs bg-emerald-600 text-white">PAID</span>
                    @elseif($inv->status === 'partial')
                        <span class="px-2 py-1 rounded text-xs bg-amber-500 text-white">PARTIAL</span>
                    @else
                        <span class="px-2 py-1 rounded text-xs bg-red-600 text-white">UNPAID</span>
                    @endif
                </td>
                <td class="p-2 text-center">
                    <a href="{{ route('invoices.show', $inv) }}"
                       class="text-xs px-3 py-1 rounded bg-slate-700 hover:bg-slate-600">
                        Detail
                    </a>
                </td>
            </tr>
        @endforeach
    </table>

    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
</x-admin.layout>
