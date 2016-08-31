@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')
{!! Form::open(['url' => 'organismo']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('nombre_organismo', 'Nombre Organismo:') !!}
            {!! Form::text('nombre_organismo',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('id_ministerio', 'Ministerio:') !!}
            {!! Form::select('id_ministerio',$ministerio, 'default', array('id'=> 'id_ministerio' , 'class'=>'form-control') ) !!}
            <a href="#" class="btn-quick-add" data-toggle="modal" data-target="#myModal">
                nuevo ministerio
            </a>
        </div>
        <div class="form-group" >
            {!! Form::label('descripcion', 'Descripcion:') !!}
            {!! Form::textarea('descripcion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', '1', ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
        </div>
    </div>
    <div class="col-xs-6">

    </div>
</div>
<div class="form-group row-action">
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}

<!-- Modal Ministerio Form -->
@include('ministerio.form_quick')
<!-- End Modal Ministerio Form -->

@include('layouts.boxbottom')
@include('organismo.js')
@endsection