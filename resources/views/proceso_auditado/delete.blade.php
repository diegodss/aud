@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

{!! Form::open(['method' => 'DELETE', 'route'=>['proceso_auditado.destroy', $proceso_auditado->id_proceso_auditado]]) !!}
<div class="form-group">
    <div class="alert alert-success">¿Quieres eliminar el registro?</div>
</div>
<div class="form-group">
    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
    <a href="{{ url('proceso_auditado')}}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}

@include('layouts.boxbottom')
@stop