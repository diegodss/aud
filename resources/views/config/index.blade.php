@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')
<div class="row">
    <div class="col-xs-12">
        test
    </div>
</div>
<div class="row">
    <div class="col-xs-12">&nbsp; </div>
</div>

@include('layouts.boxbottom')
@endsection