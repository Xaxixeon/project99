@props(['logs'])

<div class="bg-white shadow rounded-lg p-4" data-order-id="{{ $logs->first()->order_id ?? '' }}">
    <ul class="space-y-4">
        @forelse($logs as $log)
            <li class="flex items-start space-x-3">
                <div class="mt-1">
                    @if($log->from_status || $log->to_status)
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    @else
                        <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                    @endif
                </div>

                <div class="flex-1">
                    <div class="text-sm text-gray-700">
                        @if($log->from_status || $log->to_status)
                            <span class="font-semibold">
                                Status berubah:
                                {{ $log->from_status ?? '—' }} →
                                {{ $log->to_status ?? '—' }}
                            </span>
                        @else
                            <span class="font-semibold">Perubahan data order</span>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500">
                        Oleh: {{ optional($log->staff)->name ?? 'System' }}
                        • {{ $log->created_at->format('d M Y H:i') }}
                    </div>

                    @if($log->before_payload || $log->after_payload)
                        <div class="mt-2 text-xs text-gray-600 space-y-1">
                            @foreach(($log->after_payload ?? []) as $field => $newValue)
                                @php
                                    $oldValue = $log->before_payload[$field] ?? null;
                                @endphp
                                <div>
                                    <span class="font-medium">{{ ucfirst($field) }}:</span>
                                    <span class="line-through text-gray-400">{{ $oldValue ?? '—' }}</span>
                                    →
                                    <span>{{ $newValue ?? '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($log->note)
                        <div class="mt-2 text-sm text-gray-700">
                            Catatan: {{ $log->note }}
                        </div>
                    @endif
                </div>
            </li>
        @empty
            <li class="text-sm text-gray-500">Belum ada aktivitas.</li>
        @endforelse
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.querySelector('[data-order-id]');
    const orderId = container?.dataset.orderId;
    if (!orderId || !window.Echo) return;

    window.Echo.channel('order-channel')
        .listen('.order.activity_logged', (e) => {
            if (String(e.order_id) !== String(orderId)) return;
            // fallback: reload to fetch fresh timeline
            fetch(`/orders/${orderId}/activity`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    window.location.reload();
                });
        });
});
</script>
