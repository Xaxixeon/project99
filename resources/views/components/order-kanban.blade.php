@props(['ordersByStatus', 'statuses', 'mode' => 'default'])

@php
    $compact = ($mode === 'compact');
@endphp

<style>
.kanban-placeholder {
    height: 40px;
    margin: 4px 0;
    border: 2px dashed #9ca3af;
    border-radius: 6px;
}
</style>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
    @foreach($statuses as $status)
        <div class="bg-gray-50 rounded-lg shadow p-3 flex flex-col">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-700">
                    {{ $status }}
                </h2>
                <span class="text-xs text-gray-500">
                    {{ ($ordersByStatus[$status] ?? collect())->count() }}
                </span>
            </div>

            <div class="space-y-2 flex-1 min-h-[100px] js-kanban-column"
                 data-status="{{ $status }}">
                @forelse($ordersByStatus[$status] ?? [] as $order)
                    <div class="bg-white border rounded p-2 text-xs space-y-1 js-order-card {{ $compact ? 'text-[11px] py-1 px-2 leading-tight min-h-[48px]' : '' }}"
                         data-order-id="{{ $order->id }}"
                         data-current-status="{{ $order->status }}">
                        @if($compact)
                            <div class="w-2 h-full absolute left-0 top-0 rounded-l
                                @if($order->status === 'production') bg-blue-500
                                @elseif($order->status === 'qc') bg-yellow-500
                                @elseif($order->status === 'ready') bg-green-600
                                @endif"></div>
                        @endif
                        <div class="font-semibold">
                            #{{ $order->order_code ?? $order->order_no ?? $order->id }}
                        </div>
                        <div class="text-gray-500">
                            {{ $order->title ?? 'No title' }}
                        </div>
                        @if(!$compact)
                            <div class="text-[10px] text-gray-400">
                                {{ $order->product ?? '-' }}
                                @if($order->deadline)
                                    • {{ $order->deadline->format('d M Y') }}
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-xs text-gray-400 italic">Kosong</div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

    let draggedCard = null;
    const placeholder = document.createElement('div');
    placeholder.classList.add('kanban-placeholder');

    // --- Drag start
    document.querySelectorAll('.js-order-card').forEach(card => {
        card.draggable = true;
        card.addEventListener('dragstart', e => {
            draggedCard = card;
            e.dataTransfer.setData('orderId', card.dataset.orderId);
            e.dataTransfer.setData('fromStatus', card.dataset.currentStatus);
        });
    });

    // --- Kolom (status)
    document.querySelectorAll('.js-kanban-column').forEach(col => {
        col.addEventListener('dragover', e => {
            e.preventDefault();
            const after = [...col.querySelectorAll('.js-order-card')].find(card => {
                const box = card.getBoundingClientRect();
                return e.clientY < box.top + box.height / 2;
            });
            if (after == null) {
                col.appendChild(placeholder);
            } else {
                col.insertBefore(placeholder, after);
            }
        });
        col.addEventListener('dragleave', () => {
            placeholder.remove();
        });
        col.addEventListener('drop', e => {
            e.preventDefault();

            const orderId = e.dataTransfer.getData('orderId');
            const fromStatus = e.dataTransfer.getData('fromStatus');
            const toStatus = col.dataset.status;

            // Pindah status
            if (fromStatus !== toStatus) {
                placeholder.remove();
                updateStatus(orderId, toStatus);
                return;
            }

            // Reorder dalam status yang sama
            if (draggedCard) {
                col.insertBefore(draggedCard, placeholder);
                placeholder.remove();
                const newOrderIds = [...col.querySelectorAll('.js-order-card')]
                    .map(el => el.dataset.orderId);
                reorderWithinStatus(toStatus, newOrderIds);
            }
        });
    });

    function updateStatus(orderId, newStatus) {
        fetch(`/orders/${orderId}/status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: newStatus,
                updated_at: Math.floor(Date.now() / 1000)
            })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                alert('Transition not allowed: ' + (data.reason || 'unknown'));
            } else {
                window.location.reload();
            }
        });
    }

    function reorderWithinStatus(status, orderedIds) {
        fetch(`/orders/reorder`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                order_ids: orderedIds
            })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                alert('Reorder failed');
            }
        });
    }

    // Realtime reorder listener
    if (window.Echo) {
        window.Echo.channel('order-channel')
            .listen('.order.priority_changed', (e) => {
                const status = e.status;
                const orderIds = e.order_ids || [];
                const col = document.querySelector(`.js-kanban-column[data-status="${status}"]`);
                if (!col || orderIds.length === 0) return;

                const cardMap = {};
                col.querySelectorAll('.js-order-card').forEach(card => {
                    cardMap[card.dataset.orderId] = card;
                });

                orderIds.forEach(id => {
                    if (cardMap[id]) {
                        col.appendChild(cardMap[id]);
                        cardMap[id].dataset.currentStatus = status;
                    }
                });
            });
    }
});
</script>
