@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')
<div class="row">
    <div class="col-xs-12">
        <div class="pull-left">
            @can('userAction', 'subsecretaria-create')
            <a href="{{url('/subsecretaria/create')}}" class="btn btn-success" >Nueva Subsecretaria</a>
            @endcan
        </div>
        <div class="pull-right">
            @include('widget.index.filter-general')
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
@include('layouts.boxbottom')
@endsection