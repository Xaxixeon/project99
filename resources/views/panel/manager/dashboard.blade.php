@extends('layouts.app')
@section('page-title','Manager')
@section('panel-title','Manager')
@section('panel-subtitle','Overview & approvals')
@section('content')
@include('panel.layouts.header')
<div class="bg-white p-4 rounded shadow"><h4 class="mb-2 font-semibold">Pending approvals</h4><ul class="text-sm"><li>Budget approval — $5,000</li><li>Hire request — 1 position</li></ul></div>
@endsection
