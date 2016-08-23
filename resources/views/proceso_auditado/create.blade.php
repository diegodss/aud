@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')

<form action="{{ url('proceso_auditado/guardar/filtro') }}" method="post" >
    <input type="hidden" value="{{Session::token()}}" name="_token">
    <!-- !! Form::open(['url' => 'proceso_auditado_guardar/filtro']) !! -->

    {{ Form::hidden('usuario_registra', Auth::user()->id) }}
    {!! Form::hidden('area_proceso_auditado',$area_proceso_auditado,['class'=>'form-control', 'id'=>'area_proceso_auditado' ]) !!}
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                {!! Form::label('unidad_auditada', 'Unidad Auditada:') !!}
                {!! Form::text('unidad_auditada',$unidad_auditada,['disabled' => 'disabled', 'class'=>'form-control']) !!}
            </div>
            <div class="form-group required">
                {!! Form::label('objetivo_auditoria', 'Objetivo Auditoria:') !!}
                {!! Form::select('objetivo_auditoria',[null=>'Seleccione']+$objetivo_auditoria, 'default', array('id'=> 'objetivo_auditoria' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group required">
                {!! Form::label('actividad_auditoria', 'Actividad Auditoria:') !!}
                {!! Form::select('actividad_auditoria',[null=>'Seleccione']+$actividad_auditoria, 'default', array('id'=> 'actividad_auditoria' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group required">
                {!! Form::label('tipo_auditoria', 'Tipo de Auditoria:') !!}
                {!! Form::select('tipo_auditoria',[null=>'Seleccione']+$tipo_auditoria, 'default', array('id'=> 'tipo_auditoria' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group">
                {!! Form::label('codigo_caigg', 'Codigo CAIGG:') !!}
                {!! Form::text('codigo_caigg',null,['class'=>'form-control']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('id_proceso', 'Proceso Transversal:') !!}
                {!! Form::select('id_proceso',[null=>'Seleccione']+$proceso, 'default', array('id'=> 'id_proceso' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group required">
                {!! Form::label('nomenclatura', 'Nomenclatura:') !!}
                {!! Form::select('nomenclatura',[null=>'Seleccione']+$nomenclatura, 'default', array('id'=> 'nomenclatura' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group">
                {!! Form::label('tipo_informe', 'Tipo de Informe:') !!}
                {!! Form::select('tipo_informe',[null=>'Seleccione']+$tipo_informe, 'default', array('id'=> 'tipo_informe' , 'class'=>'form-control') ) !!}
            </div>

        </div>
        <div class="col-xs-6">
            <div class="form-group required">
                {!! Form::label('numero_informe', 'Numero de Informe:' , ['class'=>'form-100']) !!}
                {!! Form::text('numero_informe',null,['id'=>'numero_informe', 'class'=>'form-control  form-100']) !!}
                {!! Form::select('numero_informe_unidad',[null=>'Seleccione']+$numero_informe_unidad, 'default', array('id'=> 'numero_informe_unidad' , 'class'=>'form-control form-100') ) !!}
            </div>
            <div class="form-group required">
                {!! Form::label('ano', 'Año:') !!}
                {!! Form::select('ano',[null=>'Seleccione']+$ano, 'default', array('id'=> 'ano' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group required">
                {!! Form::label('fecha', 'Fecha:') !!}
                {!! Form::text('fecha',null,['class'=>'form-control', 'id'=>'fecha']) !!}
            </div>
            <div class="form-group required" >
                {!! Form::label('nombre_proceso_auditado', 'Proceso:') !!}
                {!! Form::textarea('nombre_proceso_auditado',null,['class'=>'form-control two-lines']) !!}
            </div>


            <div class="form-group">
                <h3>Equipo de Auditores</h3>
                {!! Form::select('id_equipo_auditor',[null=>'Seleccione'] + $equipo_auditor, 'default', array('id'=> 'id_equipo_auditor' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group">
                <div id="grid_equipo_auditor"></div>
            </div>

        </div>
    </div>
    <div class="form-group row-action text-right">
        <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
        {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    </div>
    {!! Form::close() !!}


    @include('layouts.boxbottom')
    @include('proceso_auditado.js')

    @endsection