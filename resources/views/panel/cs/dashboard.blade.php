@extends('layouts.app')
@section('page-title','Customer Service')
@section('panel-title','Customer Service')
@section('panel-subtitle','Orders & tickets')
@section('content')
@include('panel.layouts.header')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="bg-white p-4 rounded shadow"><h4 class="font-semibold mb-2">New orders</h4><ul class="space-y-1 text-sm text-gray-700"><li>Order #1001 — John Doe</li><li>Order #1002 — Mary Jane</li><li>Order #1003 — Acme Co.</li></ul></div>
<div class="bg-white p-4 rounded shadow"><h4 class="font-semibold mb-2">Assigned</h4><p class="text-sm text-gray-600">You have 3 assigned tickets.</p></div>
</div>
@endsection
