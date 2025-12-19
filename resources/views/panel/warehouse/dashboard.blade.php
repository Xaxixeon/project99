@extends('layouts.app')
@section('page-title','Warehouse')
@section('panel-title','Warehouse')
@section('panel-subtitle','Stock & movements')
@section('content')
@include('panel.layouts.header')
<div class="bg-white p-4 rounded shadow"><h4 class="mb-2 font-semibold">Stock overview</h4><div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm"><div class="p-3 border rounded">Product A<br><strong>120</strong></div><div class="p-3 border rounded">Product B<br><strong>20</strong></div><div class="p-3 border rounded">Product C<br><strong>0</strong></div></div></div>
@endsection
