<div>
    <div class="bg-white shadow rounded-lg p-4 space-y-4">
        @forelse($logs as $log)
            <div class="flex items-start space-x-3" wire:key="log-{{ $log->id }}">
                <div class="mt-1">
                    <div class="w-3 h-3 rounded-full 
                        {{ $log->from_status || $log->to_status ? 'bg-blue-500' : 'bg-gray-400' }}">
                    </div>
                </div>

                <div class="flex-1">
                    <div class="text-sm text-gray-700">
                        @if($log->from_status || $log->to_status)
                            <strong>Status:</strong>
                            {{ $log->from_status ?? '-' }} → {{ $log->to_status ?? '-' }}
                        @else
                            <strong>Perubahan data order</strong>
                        @endif
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ optional($log->staff)->name ?? 'System' }} • 
                        {{ $log->created_at->format('d M Y H:i') }}
                    </div>

                    @if($log->before_payload || $log->after_payload)
                        <div class="text-xs text-gray-600 mt-2 space-y-1">
                            @foreach(($log->after_payload ?? []) as $field => $new)
                                <div>
                                    <span class="font-medium">{{ $field }}:</span>
                                    <span class="line-through text-gray-400">{{ $log->before_payload[$field] ?? '—' }}</span>
                                    →
                                    <span>{{ $new }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($log->note)
                        <div class="mt-1 text-xs text-gray-700">
                            Catatan: {{ $log->note }}
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-400">Belum ada aktivitas.</div>
        @endforelse
    </div>
</div>
