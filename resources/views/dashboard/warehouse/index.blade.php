<x-admin.layout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <x-stat-card title="Total SKU" value="{{ $inventories->count() ?? 0 }}" bg="blue" />
        <x-stat-card title="Stok Menipis" value="{{ $lowStockCount ?? 0 }}" bg="red" />
        <x-stat-card title="Gudang" value="{{ $warehousesCount ?? 0 }}" bg="green" />
    </div>

    <x-card class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Daftar Stok</h2>

        <x-table>
            <x-slot name="head">
                <th class="p-3 text-left">Produk / Material</th>
                <th class="p-3 text-left">Gudang</th>
                <th class="p-3 text-left">Jumlah</th>
                <th class="p-3 text-left">Status</th>
            </x-slot>

            @foreach ($inventories as $inv)
                <tr class="border-t">
                    <td class="p-3">{{ $inv->product->name ?? '-' }}</td>
                    <td class="p-3">{{ $inv->warehouse->name ?? '-' }}</td>
                    <td class="p-3">{{ $inv->quantity }}</td>
                    <td class="p-3">
                        @if (isset($inv->min_stock) && $inv->quantity <= $inv->min_stock)
                            <x-badge color="red">Menipis</x-badge>
                        @else
                            <x-badge color="green">Aman</x-badge>
                        @endif
                    </td>
                </tr>
            @endforeach
        </x-table>
    </x-card>

    <x-card>
        <h2 class="text-lg font-semibold mb-4">Pergerakan Stok Terbaru</h2>
        <ul class="space-y-2 text-sm">
            @forelse($stockMovements as $mv)
                <li class="flex justify-between border-b pb-1">
                    <span>
                        {{ $mv->created_at }}
                        {{ ucfirst($mv->type) }}
                        ({{ $mv->quantity }})
                        {{ $mv->inventory->product->name ?? '' }}
                    </span>
                    <span class="text-gray-500">
                        {{ $mv->user->name ?? '' }}
                    </span>
                </li>
            @empty
                <li class="text-gray-500">Belum ada pergerakan stok.</li>
            @endforelse
        </ul>
    </x-card>
</x-admin.layout>
