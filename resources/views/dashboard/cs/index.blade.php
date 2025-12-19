<x-admin.layout>
    <h2 class="text-xl font-semibold mb-4">Order Masuk</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl-grid-cols-3 gap-4">
        @foreach ($newOrders as $order)
            <x-card>
                <h3 class="font-semibold text-lg">{{ $order->order_code }}</h3>
                <p class="text-sm text-gray-600">{{ $order->customer->name ?? 'Guest' }}</p>

                <div class="mt-2">
                    <x-order-status status="{{ $order->status }}" />
                </div>

                <div class="mt-4 space-x-2">
                    <a href="/order/{{ $order->id }}" class="text-blue-600 underline">Lihat Detail</a>

                    <form action="/order/{{ $order->id }}/assign-designer" method="POST" class="inline">
                        @csrf
                        <x-button type="primary">Assign Designer</x-button>
                    </form>
                </div>
            </x-card>
        @endforeach
    </div>
</x-admin.layout>
