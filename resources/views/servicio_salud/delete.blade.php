@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

{!! Form::open(['method' => 'DELETE', 'route'=>['servicio_salud.destroy', $servicio_salud->id_servicio_salud]]) !!}
<div class="form-group">
    <div class="alert alert-success">¿Quieres eliminar el registro?</div>
</div>
<div class="form-group">
    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}

@include('layouts.boxbottom')
@stop