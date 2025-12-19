@extends('layouts.app')
@section('page-title','Designer Dashboard')
@section('panel-title','Designer')
@section('panel-subtitle','File previews & revisions')
@section('content')
@include('panel.layouts.header')
<div class="bg-white p-4 rounded shadow"><h4 class="mb-2 font-semibold">Recent designs</h4><div class="grid grid-cols-1 md:grid-cols-3 gap-4"><div class="p-3 border rounded">Preview 1</div><div class="p-3 border rounded">Preview 2</div><div class="p-3 border rounded">Preview 3</div></div></div>
@endsection
