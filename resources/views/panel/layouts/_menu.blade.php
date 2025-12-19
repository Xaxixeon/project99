@php $role = Auth::user()->role ?? 'guest'; @endphp

<a href="{{ route($role . '.dashboard') ?? route('home') }}"
    class="block px-3 py-2 rounded hover:bg-slate-700">Dashboard</a>

@if ($role === 'admin')
    <a href="{{ route('admin.products.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Products</a>
    <a href="{{ route('admin.orders.index') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Orders</a>
    <a href="{{ url('/admin/users') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Users</a>
@endif

@if ($role === 'customer_service')
    <a href="{{ route('cs.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">New Orders</a>
@endif

@if ($role === 'designer')
    <a href="{{ route('designer.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Design Queue</a>
@endif

@if ($role === 'production')
    <a href="{{ route('production.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Job Board</a>
@endif

@if ($role === 'cashier')
    <a href="{{ route('cashier.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Invoices</a>
@endif

@if ($role === 'warehouse')
    <a href="{{ route('warehouse.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Inventory</a>
@endif

@if ($role === 'marketing')
    <a href="{{ route('marketing.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Campaigns</a>
@endif

@if ($role === 'manager')
    <a href="{{ route('manager.dashboard') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Reports</a>
@endif
