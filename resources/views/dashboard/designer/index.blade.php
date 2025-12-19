<x-admin.layout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <x-stat-card title="Total Antrian" value="{{ $queueCount ?? 0 }}" bg="blue" />
        <x-stat-card title="Sedang Dikerjakan" value="{{ $inProgressCount ?? 0 }}" bg="yellow" />
        <x-stat-card title="Selesai Hari Ini" value="{{ $doneTodayCount ?? 0 }}" bg="green" />
    </div>

    <h2 class="text-xl font-semibold mb-4">Antrian Desain</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($designQueue as $order)
            <x-card>
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="font-semibold text-lg">{{ $order->order_code }}</h3>
                        <p class="text-sm text-gray-600">
                            Customer: {{ $order->customer->name ?? 'Guest' }}
                        </p>
                    </div>
                    <x-order-status status="{{ $order->status }}" />
                </div>

                @if ($order->deadline ?? false)
                    <p class="text-sm text-gray-500 mb-2">
                        Deadline: {{ $order->deadline }}
                    </p>
                @endif

                <x-timeline :steps="[
                    ['title' => 'Order Masuk', 'done' => true],
                    ['title' => 'Desain', 'done' => in_array($order->status, ['designing', 'design_done'])],
                    [
                        'title' => 'Produksi',
                        'done' => in_array($order->status, [
                            'production',
                            'printing',
                            'finishing',
                            'ready',
                            'paid',
                            'completed',
                        ]),
                    ],
                ]" />

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="/order/{{ $order->id }}" class="text-blue-600 underline">
                        Lihat Detail
                    </a>

                    @if ($order->status === 'assigned')
                        <form method="POST" action="/order/{{ $order->id }}/start-design">
                            @csrf
                            <x-button type="primary">Mulai Desain</x-button>
                        </form>
                    @endif

                    @if ($order->status === 'designing')
                        <form method="POST" action="/order/{{ $order->id }}/finish-design">
                            @csrf
                            <x-button type="success">Tandai Selesai</x-button>
                        </form>
                    @endif
                </div>
            </x-card>
        @empty
            <x-card>
                <p class="text-gray-500">Belum ada antrian desain.</p>
            </x-card>
        @endforelse
    </div>
</x-admin.layout>
