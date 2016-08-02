@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')
{!! Form::model($organismo,['method' => 'PATCH','route'=>['organismo.update',$organismo->id_organismo]]) !!}
{{ Form::hidden('usuario_registra', $organismo->usuario_registra) }}
{{ Form::hidden('usuario_modifica', Auth::user()->id) }}
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('nombre_organismo', 'Nombre Organismo:') !!}
            {!! Form::text('nombre_organismo',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('id_ministerio', 'Ministerio:') !!}
            {!! Form::select('id_ministerio',$ministerio, $organismo->id_ministerio, array('id'=> 'id_ministerio' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('descripcion', 'Descripcion:') !!}
            {!! Form::textarea('descripcion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1',$organismo->fl_status, ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
        </div>
    </div>
    <div class="col-xs-6">

    </div>
</div>
<div class="form-group">
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    <a href="{{ url('organismo')}}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}
@include('layouts.boxbottom')
@include('organismo.js')
@endsection