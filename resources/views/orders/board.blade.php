@extends('layouts.app')

@section('content')
<div class="px-4 py-6">
    <h1 class="text-2xl font-semibold mb-4">Order Board</h1>
    <x-order-kanban :ordersByStatus="$orders" :statuses="$statuses" />
</div>
@endsection
