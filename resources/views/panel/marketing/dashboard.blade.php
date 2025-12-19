@extends('layouts.app')
@section('page-title','Marketing')
@section('panel-title','Marketing')
@section('panel-subtitle','Campaigns & analytics')
@section('content')
@include('panel.layouts.header')
<div class="bg-white p-4 rounded shadow"><h4 class="font-semibold">Active campaigns</h4><ul class="text-sm"><li>Campaign A — running</li><li>Campaign B — paused</li></ul></div>
@endsection
panel-layout>
