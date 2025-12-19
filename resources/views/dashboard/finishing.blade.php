<x-admin.layout>

<h2 class="text-3xl font-bold mb-6">Finishing Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg">Finishing Queue</h3>
        <p class="text-3xl font-bold">{{ $finishingCount ?? 0 }}</p>
    </div>
</div>

<h3 class="text-xl font-bold mt-10 mb-4">Finishing Jobs</h3>

@include('partials.order-table', ['orders' => $finishingQueue])

</x-admin.layout>
