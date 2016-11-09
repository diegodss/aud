@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')

<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-danger">
            <p>{{ $mensaje }}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class = "form-group">

            <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
        </div>
    </div>
</div>

@include('layouts.boxbottom')

@endsection