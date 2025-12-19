@extends('layouts.app')

@section('content')
<div class="px-4 py-6 space-y-6">
    <h1 class="text-2xl font-semibold mb-4">Manager Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Average lead time</div>
            <div class="text-2xl font-bold">
                {{ $avgLeadTime ? number_format($avgLeadTime, 1) . ' jam' : '-' }}
            </div>
        </div>

        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Total orders</div>
            <div class="text-2xl font-bold">
                {{ array_sum($byStatus) }}
            </div>
        </div>

        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Done</div>
            <div class="text-2xl font-bold">
                {{ $byStatus['done'] ?? 0 }}
            </div>
        </div>

        <div class="bg-white rounded shadow p-4">
            <div class="text-xs text-gray-500">Overdue</div>
            <div class="text-2xl font-bold text-red-500">
                {{ $byStatus['overdue'] ?? 0 }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded shadow p-4">
        <h2 class="text-sm font-semibold mb-2">Orders per status</h2>
        <table class="min-w-full text-xs">
            <thead>
                <tr class="border-b text-gray-500">
                    <th class="text-left py-1">Status</th>
                    <th class="text-right py-1">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($byStatus as $status => $total)
                    <tr class="border-b">
                        <td class="py-1">{{ $status }}</td>
                        <td class="py-1 text-right">{{ $total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded shadow p-4">
        <h2 class="text-sm font-semibold mb-2">Orders per Day (7 hari terakhir)</h2>
        <canvas id="ordersPerDay"></canvas>
    </div>

    <div class="bg-white rounded shadow p-4 mt-6">
        <h2 class="text-sm font-semibold mb-2">Revenue per Month</h2>
        <canvas id="revenuePerMonth"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ordersPerDayCtx = document.getElementById('ordersPerDay');
    const revenueCtx = document.getElementById('revenuePerMonth');

    if (ordersPerDayCtx) {
        new Chart(ordersPerDayCtx, {
            type: 'line',
            data: {
                labels: @json($perDay->pluck('d')),
                datasets: [{
                    label: 'Orders',
                    data: @json($perDay->pluck('total')),
                    borderWidth: 2,
                    fill: false,
                }]
            }
        });
    }

    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json($revenue->pluck('m')),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: @json($revenue->pluck('amount')),
                    borderWidth: 1,
                }]
            }
        });
    }
});
</script>
@endsection
