@php
$role = auth()->check() ? auth()->user()->roles()->first()->name ?? null : null;
@endphp

<div class="w-64 min-h-screen bg-slate-800 text-white p-5">
    <div class="mb-6">
        <a href="{{ route('dashboard') ?? url('/') }}" class="text-lg font-bold">{{ config('app.name') }}</a>
    </div>

    <nav class="space-y-2">
        <a href="{{ url('/') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Home</a>

        @if($role === 'admin')
            <a href="{{ url('/dashboard/admin') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Admin</a>
        @endif

        @if($role === 'customer_service')
            <a href="{{ url('/dashboard/customer-service') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Customer Service</a>
        @endif

        @if($role === 'designer' || auth()->user()->hasRole('admin'))
            <a href="{{ url('/dashboard/designer') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Designer</a>
            <a href="{{ url('/dashboard/designer/revisions') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Revisions</a>
        @endif

        @if($role === 'production')
            <a href="{{ url('/dashboard/production') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Production</a>
        @endif

        @if($role === 'cashier')
            <a href="{{ url('/dashboard/cashier') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Cashier</a>
        @endif

        @if($role === 'warehouse')
            <a href="{{ url('/dashboard/warehouse') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Warehouse</a>
        @endif

        @if($role === 'marketing')
            <a href="{{ url('/dashboard/marketing') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Marketing</a>
        @endif

        @if($role === 'manager')
            <a href="{{ url('/dashboard/manager') }}" class="block px-3 py-2 rounded hover:bg-slate-700">Manager</a>
        @endif
    </nav>

    <div class="mt-6 border-t border-slate-700 pt-4">
        <div class="text-sm">Logged in as:</div>
        <div class="font-medium">{{ auth()->user()->name ?? 'Guest' }}</div>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button class="w-full text-left px-3 py-2 rounded hover:bg-slate-700">Logout</button>
        </form>
    </div>
</div>
