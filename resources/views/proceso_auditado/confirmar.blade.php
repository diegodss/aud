@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')

{!! Form::open(['url' => 'proceso_auditado/form/filtro', 'name' => 'proceso_auditadoForm', 'id' => 'proceso_auditadoForm']) !!}
<input type="hidden" name="area_proceso_auditado" id="area_proceso_auditado" value="{{ $area_proceso_auditado }}" />
Confirmar
Has elegido editar un: {{ $tipo }}

{{ $tipo }} Elegido: {{ $proceso_auditaro_unidad }}

<div class="form-group">
    {!! Form::submit('Iniciar proceso auditado', ['class' => 'btn btn-success']) !!}
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}

@include('layouts.boxbottom')
@endsection