@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')
<div class="row">
    <div class="col-xs-12">
        <div class="pull-right">
            @include('widget.index.filter-informe-ano')
        </div>
        <div class="pull-right">
            @include('widget.index.items-page')
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">&nbsp; </div>
</div>
{!! $grid !!}
@include('compromiso.js-index')
@include('layouts.boxbottom')
@endsection