@extends('layouts.app')
@yield('main-content')
@section('main-content')
<style type="text/css">

    .informe_detallado_anio{ width:120px; float:left; margin-right:8px;}
</style>
<?php $titleBox = "Seleccione la subsecretaria para mostrar el informe detallado:"; ?>
@include('layouts.boxtop')
@include('alerts.success')

{!! Form::open(['url' => 'informe_detallado', 'name' => 'informe_detallado_form', 'id' => 'informe_detallado_form']) !!}
{!! Form::hidden('subsecretaria', "Salud Pública",['class'=>'form-control', 'id'=>'subsecretaria' ]) !!}
{!! Form::hidden('_method','POST') !!}

<div class="row">
    <div class="col-xs-12">
        <a href="#Salud Pública" class="btn btn_subsecretaria {{ $css_ssp}} ">Salud Pública</a> &nbsp;
        <a href="#Redes Asistenciales" class="btn btn_subsecretaria {{ $css_ra}}" >Redes Asistenciales</a> &nbsp;
        <div class="informe_detallado_anio"> {!! Form::select('anio',[null=>'Seleccione']+$anio, $request_anio, array('id'=> 'anio' , 'class'=>'form-control') ) !!}</div>
    </div>
</div>
{!! Form::close() !!}
@include('layouts.boxbottom')
<?php $titleBox = "Por Estado"; ?>
@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-12">
        <!-- h5></h5 --> <!-- cuadro1 -->
        {!! $datagrid_por_estado !!}
    </div>
</div>
@include('layouts.boxbottom')
<?php $titleBox = "Por Condicion"; ?>
@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-6">
        <!-- cuadro2 -->
        {!! $datagrid_por_condicion_pmg !!}
    </div>
    <div class="col-xs-6">
        <!-- cuadro5 -->
        {!! $datagrid_por_condicion_no_pmg !!}
    </div>
</div>
@include('layouts.boxbottom')

<?php $titleBox = 'Por Condicion, cuando condición es "Cumplido Parcial"'; ?>
@include('layouts.boxtop')
<div class="row">

    <div class="col-xs-6">
        <!-- cuadro3 -->
        {!! $datagrid_rango_por_condicion_pmg !!}
    </div>

    <div class="col-xs-6">
        <!-- cuadro6 -->
        {!! $datagrid_rango_por_condicion_no_pmg !!}
    </div>


</div>
@include('layouts.boxbottom')

<?php $titleBox = 'No PMG, cuando condición es "No Cumplido"'; ?>
@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-12">
        <!-- cuadro7 -->
        {!! $datagrid_detalle_proceso !!}
    </div>
</div>
@include('layouts.boxbottom')

<?php $titleBox = 'Area auditada y cuantidad de compromisos por condicion'; ?>
@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-12">

        <!-- cuadro8 -->
        {!! $datagrid_detalle_area_auditada !!}

        <!-- h5>lo mismo, por otra division</h5><!-- cuadro9 -->
        <!-- div id="tabla_cuadro9"></div -->
    </div>
</div>
@include('layouts.boxbottom')
<!--
include('layouts.boxtop')
<div class="row">
    <div class="col-xs-12">
        <h5>Area Auditada por Condicion</h5><!-- cuadro8 ->
<div id="tabla_cuadro8"></div>
</div>
</div>
include('layouts.boxbottom') -->


<div class="row">
    <div class="col-xs-12 text-right">
        <a href="{{ URL::to('/') }}/informe_detallado/excel/export/{{ $subsecretaria }}" id="excel1" class="excel btn btn-app"><i class="fa fa-file-excel-o"></i> Exportar Excel</a>
    </div>
</div>


<!-- include('informe_detallado.grafico') Cambiamos grafico de google por dataGrid de laravel -->
@include('informe_detallado.js')
@endsection