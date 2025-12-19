@extends('layouts.app')
@section('page-title','Cashier')
@section('panel-title','Cashier')
@section('panel-subtitle','Invoices & payments')
@section('content')
@include('panel.layouts.header')
<div class="bg-white p-4 rounded shadow"><h4 class="mb-2 font-semibold">Pending invoices</h4><table class="w-full text-sm"><thead><tr><th>Invoice</th><th>Customer</th><th>Amount</th></tr></thead><tbody><tr><td>#INV-1001</td><td>John</td><td>$120</td></tr><tr><td>#INV-1002</td><td>Mary</td><td>$75</td></tr></tbody></table></div>
@endsection
