<div class="mb-4 flex flex-wrap items-center gap-2">
    <input type="text"
           wire:model.debounce.500ms="search"
           class="border rounded px-2 py-1 text-xs"
           placeholder="Cari order no / judul / produk">

    <select wire:model="statusFilter"
            class="border rounded px-2 py-1 text-xs">
        <option value="">Semua status</option>
        @foreach($statuses as $s)
            <option value="{{ $s }}">{{ $s }}</option>
        @endforeach
    </select>

    <input type="date" wire:model="dateFrom" class="border rounded px-2 py-1 text-xs">
    <input type="date" wire:model="dateTo" class="border rounded px-2 py-1 text-xs">
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4" x-data>
    @foreach($statuses as $status)
        <div class="bg-gray-50 rounded-lg shadow p-3 flex flex-col" wire:key="column-{{ $status }}">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700">
                    {{ $status }}
                </h2>
                <span class="text-xs text-gray-500">
                    {{ count($columns[$status] ?? []) }}
                </span>
            </div>

            <div class="space-y-2 flex-1 min-h-[80px]"
                 x-data="kanbanColumn('{{ $status }}')"
                 x-on:drop.prevent="onDrop($event)"
                 x-on:dragover.prevent>
                @foreach($columns[$status] as $order)
                    <div class="bg-white border rounded p-2 text-xs space-y-1 js-order-card"
                         draggable="true"
                         x-on:dragstart="onDragStart($event, {{ $order['id'] }}, '{{ $status }}')"
                         data-order-id="{{ $order['id'] }}"
                         wire:key="order-{{ $order['id'] }}">
                        <div class="font-semibold">
                            #{{ $order['order_no'] }}
                        </div>
                        <div class="text-gray-500">
                            {{ $order['title'] ?? 'No title' }}
                        </div>
                        <div class="text-[10px] text-gray-400">
                            {{ $order['product'] ?? '-' }}
                            @if($order['deadline'])
                                • {{ $order['deadline'] }}
                            @endif
                        </div>
                        @if(!empty($order['customer']))
                            <div class="text-[10px] text-gray-400">
                                {{ $order['customer'] }}
                            </div>
                        @endif
                        @if(!empty($order['sla_status']))
                            <span class="inline-block px-1 py-[1px] rounded text-[9px]
                                @if($order['sla_status'] === 'breached') bg-red-100 text-red-700
                                @elseif($order['sla_status'] === 'at_risk') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700
                                @endif">
                                SLA: {{ $order['sla_status'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('kanbanColumn', (status) => ({
            draggedId: null,
            fromStatus: null,

            onDragStart(event, id, from) {
                this.draggedId = id;
                this.fromStatus = from;
            },

            onDrop(event) {
                if (!this.draggedId) return;

                const toStatus = status;

                if (this.fromStatus !== toStatus) {
                    Livewire.emit('moveToStatus', this.draggedId, toStatus);
                } else {
                    const ids = [...event.currentTarget.querySelectorAll('.js-order-card')]
                        .map(el => el.dataset.orderId);

                    Livewire.emit('reorderColumn', status, ids);
                }

                this.draggedId = null;
                this.fromStatus = null;
            }
        }))
    });
</script>
