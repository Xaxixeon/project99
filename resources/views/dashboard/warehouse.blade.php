<x-admin.layout>

<h2 class="text-3xl font-bold mb-6">Warehouse Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg">Packaging Queue</h3>
        <p class="text-3xl font-bold">{{ $packagingCount ?? 0 }}</p>
    </div>

    <div class="bg-gray-800 p-6 rounded shadow">
        <h3 class="text-lg">Ready for Pickup</h3>
        <p class="text-3xl font-bold">{{ $readyCount ?? 0 }}</p>
    </div>
</div>

<h3 class="text-xl font-bold mt-10 mb-4">Packaging Queue</h3>

@include('partials.order-table', ['orders' => $packagingQueue])

</x-admin.layout>
