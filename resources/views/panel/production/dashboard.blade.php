@extends('layouts.app')
@section('page-title','Production')
@section('panel-title','Production')
@section('panel-subtitle','Jobs & status boards')
@section('content')
@include('panel.layouts.header')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div class="bg-white p-4 rounded shadow"><h4 class="font-semibold">Active jobs</h4><ol class="list-decimal ml-5 text-sm"><li>Job A — printing</li><li>Job B — assembly</li></ol></div><div class="bg-white p-4 rounded shadow"><h4 class="font-semibold">Machine status</h4><p class="text-sm text-gray-600">All systems nominal</p></div></div>
@endsection
