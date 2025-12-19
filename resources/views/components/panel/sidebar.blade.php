@php
    $user = auth()->user();
    $role = $user ? $user->roles()->first()->name ?? '' : '';
@endphp

<aside class="w-64 bg-white border-r">
    <div class="p-4 font-bold text-lg border-b">Grafikita Panel</div>
    <nav class="p-3 space-y-1">
        <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('dashboard') }}">Home</a>

        @if ($role === 'admin')
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        @endif

        @if ($role === 'customer_service')
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('cs.dashboard') }}">CS - New Orders</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('cs.pending') }}">CS - Pending</a>
        @endif

        @if ($role === 'designer')
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('designer.dashboard') }}">Designer
                Queue</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('designer.revisions') }}">Revisions</a>
        @endif

        @if ($role === 'production')
            <a class="block px-3 py-2 rounded hover:bg-gray-100"
                href="{{ route('production.dashboard') }}">Production</a>
        @endif

        @if ($role === 'cashier')
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('cashier.dashboard') }}">Cashier</a>
        @endif

        @if ($role === 'warehouse')
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('warehouse.dashboard') }}">Warehouse</a>
        @endif

        @if ($role === 'marketing')
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('marketing.dashboard') }}">Marketing</a>
        @endif

        @if ($role === 'manager')
            <a class="block px-3 py-2 rounded hover:bg-gray-100" href="{{ route('manager.dashboard') }}">Manager</a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full mt-3 px-3 py-2 text-left rounded hover:bg-gray-100">Logout</button>
        </form>
    </nav>
</aside>
