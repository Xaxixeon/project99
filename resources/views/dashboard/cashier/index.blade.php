<x-admin.layout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <x-stat-card title="Pending Pembayaran" value="{{ $pendingPayments->count() ?? 0 }}" bg="yellow" />
        <x-stat-card title="Lunas Hari Ini" value="{{ $paidTodayCount ?? 0 }}" bg="green" />
        <x-stat-card title="Omzet Hari Ini" value="Rp {{ number_format($revenueToday ?? 0) }}" bg="blue" />
    </div>

    <x-card>
        <h2 class="text-lg font-semibold mb-4">Order Siap Dibayar</h2>

        @if ($pendingPayments->isEmpty())
            <p class="text-gray-500">Belum ada order yang siap dibayar.</p>
        @else
            <x-table>
                <x-slot name="head">
                    <th class="p-3 text-left">Order</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Aksi</th>
                </x-slot>

                @foreach ($pendingPayments as $order)
                    <tr class="border-t">
                        <td class="p-3">{{ $order->order_code }}</td>
                        <td class="p-3">{{ $order->customer->name ?? '-' }}</td>
                        <td class="p-3">Rp {{ number_format($order->total) }}</td>
                        <td class="p-3">
                            <x-order-status status="{{ $order->status }}" />
                        </td>
                        <td class="p-3">
                            <form method="POST" action="/order/{{ $order->id }}/pay">
                                @csrf
                                <x-button type="success">Tandai Sudah Dibayar</x-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </x-card>
</x-admin.layout>
