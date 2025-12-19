<x-admin.layout>
    <h2 class="text-xl font-semibold mb-6">Antrian Produksi</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($productionQueue as $order)
            <x-card>
                <div class="flex justify-between">
                    <div>
                        <h3 class="font-semibold text-lg">{{ $order->order_code }}</h3>
                        <p class="text-sm text-gray-600">Customer: {{ $order->customer->name ?? '-' }}</p>
                    </div>
                    <x-order-status status="{{ $order->status }}" />
                </div>

                <div class="mt-4">
                    <x-timeline :steps="[
                        ['title' => 'Desain', 'done' => $order->status === 'design_done' || $order->status !== 'pending'],
                        ['title' => 'Produksi', 'done' => $order->status === 'production' || $order->status === 'printing'],
                        ['title' => 'Finishing', 'done' => $order->status === 'finishing'],
                        ['title' => 'Siap Bayar', 'done' => $order->status === 'ready'],
                    ]" />
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @if($order->status === 'design_done')
                        <form method="POST" action="/order/{{ $order->id }}/start-production">
                            @csrf
                            <x-button type="primary">Mulai Produksi</x-button>
                        </form>
                    @endif

                    @if($order->status === 'production')
                        <form method="POST" action="/order/{{ $order->id }}/print">
                            @csrf
                            <x-button type="warning">Print</x-button>
                        </form>
                    @endif

                    @if($order->status === 'printing')
                        <form method="POST" action="/order/{{ $order->id }}/finish">
                            @csrf
                            <x-button type="success">Finishing</x-button>
                        </form>
                    @endif

                    @if($order->status === 'finishing')
                        <form method="POST" action="/order/{{ $order->id }}/ready">
                            @csrf
                            <x-button type="green">Siap Bayar</x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        @endforeach
    </div>
</x-admin.layout>
