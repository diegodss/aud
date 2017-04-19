@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')
<div class="row">
    <div class="col-xs-12">
        &nbsp;
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <h4>Caso sea una nueva recarga, será necesario resetear los datos de los informes anteriores</h4>
        <div class="form-group">
            <a href="#" class="btn btn-success btn-truncate_proceso_auditado">Borrar todos los informes</a>
            <div id="mensaje"></div>
        </div>
        <h4>Elija el archivo a cargar</h4>
        <div class="form-group required" >
            {!! Form::label('file_import', 'Archivo para cargar:') !!}
            {!! Form::select('file_import',[null=>'Seleccione'] +$file_import, null, array('id'=> 'file_import' , 'class'=>'form-control') ) !!}
        </div>
    </div>
    <div class="col-xs-12">
        <h4>Para realizar la carga masiva de datos, debese seguir los siguientes pasos:</h4>
        <p>1. Importar archivo seleccionado.</p>
        <p><a href="#" id="importar" class="btn btn-success">Continuar</a></p>
        <div id="div_import" class="iframe_planilla_seguimiento_import"></div>
		<hr />
        <p>2. Procesar datos importados</p>
        <p><a id="set_procesa" class="btn btn-success">Continuar</a></p>
        <div id="div_procesa" class="iframe_planilla_seguimiento_import"></div>
		<hr />
        <p>3. Configurar compromisos reprogramados</p>
        <p><a id="set_compromiso_padre" class="btn btn-success">Continuar</a></p>
        <div id="div_compromiso_padre" class="iframe_planilla_seguimiento_import"></div>
		<hr />
		<p>4. Finalizar Importación</p>
        <p><a id="finalizar_importacion" class="btn btn-success">Continuar</a></p>
        <div id="div_finalizar_importacion" class="iframe_planilla_seguimiento_import"></div>

    </div>
</div>
@include('layouts.boxbottom')
@include('planilla_seguimiento_import.js')
@endsection