<x-panel-layout :title="$title ?? 'CS - Pending'">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-bold">Pending / Assigned</h3>
        <ul>
            @forelse($orders as $o)
                <li>#{{ $o->id }} — {{ $o->status }} — assigned to {{ $o->assigned_to ?? '—' }}</li>
            @empty
                <li>No orders</li>
            @endforelse
        </ul>
    </div>
</x-panel-layout>
