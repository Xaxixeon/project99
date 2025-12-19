<x-admin.layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-6">Kanban Taking Order</h1>

        @php
            $statuses = [
                'waiting'   => 'Waiting',
                'design'    => 'Design',
                'printing'  => 'Printing',
                'completed' => 'Completed',
            ];
        @endphp

        <div class="grid grid-cols-4 gap-4">
            @foreach($statuses as $key => $label)
                <div class="kanban-column" data-status="{{ $key }}">
                    <h2 class="text-lg font-semibold mb-3 text-slate-700 dark:text-slate-200">{{ $label }}</h2>
                    <div class="space-y-3 min-h-[200px] p-2 bg-slate-100 dark:bg-slate-800 rounded-lg"
                         ondragover="allowDrop(event)"
                         ondrop="dropCard(event, '{{ $key }}')">

                        @foreach($orders->where('kanban_status', $key) as $order)
                            <div class="kanban-card bg-white dark:bg-slate-900 rounded-xl shadow-md p-4 border border-gray-100 dark:border-slate-700 cursor-move"
                                 draggable="true"
                                 ondragstart="dragCard(event)"
                                 data-id="{{ $order->id }}">

                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">{{ $order->order_code ?? ('ORD-'.$order->id) }}</h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mb-2">{{ $order->product_name ?? '-' }}</p>

                                <div class="text-sm text-slate-700 dark:text-slate-200 space-y-1">
                                    <p><strong>Pelanggan:</strong> {{ $order->customer_name ?? '-' }}</p>
                                    <p><strong>Deadline:</strong> {{ optional($order->deadline_date)->format('d M Y') ?? '-' }}</p>
                                    <p><strong>Status:</strong> {{ ucfirst($order->kanban_status) }}</p>
                                </div>

                                <hr class="my-3 border-slate-200 dark:border-slate-700">

                                <div class="flex justify-between mt-2">
                                    <button class="px-3 py-1 border border-gray-400 text-gray-600 dark:border-slate-600 dark:text-slate-200 rounded-md text-sm">Detail</button>
                                    <button class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-500">Aksi</button>
                                </div>
                            </div>
                        @endforeach

                        @if($orders->where('kanban_status', $key)->isEmpty())
                            <div class="text-center text-xs text-slate-500 dark:text-slate-400 py-4">
                                Belum ada kartu.
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        let draggedCard = null;
        function dragCard(event) {
            draggedCard = event.target;
            event.dataTransfer.effectAllowed = "move";
        }
        function allowDrop(event) { event.preventDefault(); }
        function dropCard(event, newStatus) {
            event.preventDefault();
            if (!draggedCard) return;
            event.target.closest(".kanban-column").querySelector(".space-y-3").appendChild(draggedCard);
            let orderId = draggedCard.getAttribute("data-id");
            fetch(`/orders/${orderId}/update-status`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ status: newStatus })
            }).catch(console.error);
        }
    </script>
</x-admin.layout>
