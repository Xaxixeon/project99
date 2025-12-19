<x-admin.layout>

<h2 class="text-3xl font-bold mb-6">System Overview</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="bg-gray-800 text-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold">PHP Version</h3>
        <p class="text-3xl font-bold mt-2">{{ $server['php_version'] }}</p>
    </div>

    <div class="bg-gray-800 text-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold">Laravel Version</h3>
        <p class="text-3xl font-bold mt-2">{{ $server['laravel_version'] }}</p>
    </div>

    <div class="bg-gray-800 text-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold">Memory Usage</h3>
        <p class="text-3xl font-bold mt-2">{{ $server['memory_usage'] }}</p>
    </div>

    <div class="bg-gray-800 text-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold">Disk Total</h3>
        <p class="text-3xl font-bold mt-2">{{ $server['disk_total'] }}</p>
    </div>

    <div class="bg-gray-800 text-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold">Disk Free</h3>
        <p class="text-3xl font-bold mt-2">{{ $server['disk_free'] }}</p>
    </div>

    <div class="bg-gray-800 text-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-semibold">Disk Used</h3>
        <p class="text-xl font-bold mt-2">{{ $server['disk_used_percent'] }}%</p>
        <div class="mt-3 w-full bg-gray-700 rounded-full h-2">
            <div class="bg-red-500 h-2 rounded-full" style="width: {{ $server['disk_used_percent'] }}%"></div>
        </div>
    </div>

</div>

</x-admin.layout>
