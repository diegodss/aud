@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')
{!! Form::open(['url' => 'proceso_auditado']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}

<input type="hidden" name="area_proceso_auditado" id="area_proceso_auditado" value="{{ $area_proceso_auditado }}" />



<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('objetivo_auditoria', 'objetivo_auditoria:') !!}
            {!! Form::select('objetivo_auditoria',[null=>'Seleccione']+$objetivo_auditoria, 'default', array('id'=> 'objetivo_auditoria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('actividad_auditoria', 'actividad_auditoria:') !!}
            {!! Form::select('actividad_auditoria',[null=>'Seleccione']+$actividad_auditoria, 'default', array('id'=> 'actividad_auditoria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('tipo_auditoria', 'tipo_auditoria:') !!}
            {!! Form::select('tipo_auditoria',[null=>'Seleccione']+$tipo_auditoria, 'default', array('id'=> 'tipo_auditoria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('codigo_caigg', 'codigo_caigg:') !!}
            {!! Form::text('codigo_caigg',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('id_proceso', 'Proceso Transversal:') !!}
            {!! Form::select('id_proceso',[null=>'Seleccione']+$proceso, 'default', array('id'=> 'id_proceso' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('nomenclatura', 'Programa:') !!}
            {!! Form::select('nomenclatura',[null=>'Seleccione']+$nomenclatura, 'default', array('id'=> 'nomenclatura' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('tipo_informe', 'tipo_informe:') !!}
            {!! Form::select('tipo_informe',[null=>'Seleccione']+$tipo_informe, 'default', array('id'=> 'tipo_informe' , 'class'=>'form-control') ) !!}
        </div>

    </div>
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('ano', 'ano:') !!}
            {!! Form::select('ano',[null=>'Seleccione']+$ano, 'default', array('id'=> 'ano' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fecha', 'fecha:') !!}
            {!! Form::text('fecha',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('proceso', 'Proceso:') !!}
            {!! Form::textarea('proceso',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('numero_informe', 'numero_informe:') !!}
            {!! Form::text('numero_informe',null,['class'=>'form-control']) !!}
            {!! Form::select('numero_informe_unidad',[null=>'Seleccione']+$numero_informe_unidad, 'default', array('id'=> 'numero_informe_unidad' , 'class'=>'form-control') ) !!}
        </div>
    </div>
</div>
<div class="form-group row-action">
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    <a href="{{ url('organismo')}}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}

<!-- Modal Ministerio Form -->
@include('proceso.form_quick')
<!-- End Modal Ministerio Form -->

@include('layouts.boxbottom')
@include('organismo.js')
@endsection