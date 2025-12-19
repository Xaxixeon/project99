<x-admin.layout>
    <h2 class="text-2xl font-bold mb-4 text-white">
        Invoice {{ $invoice->invoice_no }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-slate-900 p-4 rounded">
            <h3 class="text-lg font-semibold mb-2 text-white">Info Order</h3>
            <p class="text-sm text-slate-300">Order #{{ $invoice->order->id }}</p>
            <p class="text-sm text-slate-300">
                Customer: {{ $invoice->order->customer?->name ?? $invoice->order->customer_name ?? '-' }}
            </p>
            <p class="text-sm text-slate-300">
                Tanggal: {{ $invoice->created_at->format('d M Y') }}
            </p>
        </div>

        <div class="bg-slate-900 p-4 rounded">
            <h3 class="text-lg font-semibold mb-2 text-white">Payment</h3>
            <p class="text-sm text-slate-300 mb-2">
                Amount: <span class="font-bold">Rp {{ number_format($invoice->amount) }}</span>
            </p>
            <p class="text-sm text-slate-300 mb-4">
                Status:
                @if($invoice->status === 'paid')
                    <span class="px-2 py-1 rounded text-xs bg-emerald-600 text-white">PAID</span>
                @elseif($invoice->status === 'partial')
                    <span class="px-2 py-1 rounded text-xs bg-amber-500 text-white">PARTIAL</span>
                @else
                    <span class="px-2 py-1 rounded text-xs bg-red-600 text-white">UNPAID</span>
                @endif
            </p>

            <div class="flex gap-2">
                @if($invoice->status !== 'paid')
                    <form action="{{ route('invoices.mark-paid', $invoice) }}" method="POST">
                        @csrf
                        <button class="px-3 py-2 rounded bg-emerald-600 text-xs text-white">
                            Mark as PAID
                        </button>
                    </form>
                @endif

                @if($invoice->status !== 'unpaid')
                    <form action="{{ route('invoices.mark-unpaid', $invoice) }}" method="POST">
                        @csrf
                        <button class="px-3 py-2 rounded bg-red-600 text-xs text-white">
                            Set UNPAID
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-slate-900 p-4 rounded">
        <h3 class="text-lg font-semibold mb-2 text-white">Ringkasan Order</h3>
        @php
            $firstItem = $invoice->order->items->first();
        @endphp
        <p class="text-sm text-slate-300">
            Produk: {{ $firstItem?->name ?? '-' }}<br>
            Qty: {{ $firstItem?->qty ?? $invoice->order->quantity ?? '-' }}<br>
            Ukuran: {{ $invoice->order->size ?? '-' }}
            @if($invoice->order->width_cm && $invoice->order->height_cm)
                ({{ $invoice->order->width_cm }} × {{ $invoice->order->height_cm }} cm)
            @endif
        </p>
    </div>
</x-admin.layout>
