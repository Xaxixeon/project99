@extends('layouts.app')
@section('page-title','Admin Dashboard')
@section('panel-title','Admin')
@section('panel-subtitle','Overview & management')
@section('content')
@include('panel.layouts.header')
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<div class="p-4 bg-white rounded shadow">Users<br><span class="text-2xl font-bold">1,234</span></div>
<div class="p-4 bg-white rounded shadow">Orders<br><span class="text-2xl font-bold">456</span></div>
<div class="p-4 bg-white rounded shadow">Revenue<br><span class="text-2xl font-bold">$12,345</span></div>
</div>
@endsection
