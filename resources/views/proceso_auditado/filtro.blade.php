@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')
{!! Form::open(['url' => 'proceso_auditado/confirmar', 'name' => 'proceso_auditado_filtroForm', 'id' => 'proceso_auditado_filtroForm']) !!}


<div id="mensaje"></div>
<div class="row">
    <div class="col-xs-12"> <!-- required for floating -->

        <div class="form-group">
            {!! Form::label('id_ministerio', 'Ministerio:') !!}
            {!! Form::select('id_ministerio',[null=>'Seleccione'] + $ministerio, 'default', array('id'=> 'id_ministerio' , 'class'=>'form-control') ) !!}
        </div>
    </div>


    <div class="col-xs-12"> <!-- required for floating -->
        <ul class="nav nav-tabs">
            <li class="dropdown active">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle "><b>Auditar un</b> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#tab_organismo" class="link_tab" data-toggle="tab">Organismos</a></li>
                    <li><a href="#tab_subsecretaria" class="link_tab" data-toggle="tab">Subsecretaria</a></li>
                    <li><a href="#tab_division" class="link_tab" data-toggle="tab">Division</a></li>
                    <li><a href="#tab_seremi" class="link_tab" data-toggle="tab">Seremi</a></li>
                    <li><a href="#tab_gabinete" class="link_tab" data-toggle="tab">Gabinete</a></li>
                    <li><a href="#tab_servicio_salud" class="link_tab" data-toggle="tab">Servicio de Salud</a></li>
                    <li><a href="#tab_establecimiento" class="link_tab" data-toggle="tab">Establecimiento</a></li>
                    <li><a href="#tab_departamento" class="link_tab" data-toggle="tab">Departamento</a></li>
                    <li><a href="#tab_unidad" class="link_tab" data-toggle="tab">Unidad</a></li>
                </ul>
            </li>
        </ul>

    </div>
    <div class="col-xs-12">
        <div class="proceso-auditado-busqueda-ayuda">
            <input type="hidden" name="tipo" id="tipo" />
            <div class="form-group div_subsecretaria_search" >
                {!! Form::label('subsecretaria_search', 'Subsecretaria:') !!}
                {!! Form::select('subsecretaria_search',[null=>'Seleccione'], 'default', array('id'=> 'subsecretaria_search' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group div_servicio_salud_search" >
                {!! Form::label('servicio_salud_search', 'Servicio de salud:') !!}
                {!! Form::select('servicio_salud_search',[null=>'Seleccione']+$servicio_salud, 'default', array('id'=> 'servicio_salud_search' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group div_tipo_centro_responsabilidad" >

                {!! Form::radio('tipo_centro_responsabilidad', 'Division', 0, ['class'=>'form-control_none tipo_centro_responsabilidad', 'id'=>'tipo_centro_responsabilidad_Division']) !!}
                Division

                {!! Form::radio('tipo_centro_responsabilidad', 'Seremi', 0, ['class'=>'form-control_none tipo_centro_responsabilidad', 'id'=>'tipo_centro_responsabilidad_Seremi']) !!}
                Seremi

                {!! Form::radio('tipo_centro_responsabilidad', 'Gabinete', 0, ['class'=>'form-control_none tipo_centro_responsabilidad', 'id'=>'tipo_centro_responsabilidad_Gabinete']) !!}
                Gabinete

                <!-- {!! Form::select('tipo_centro_responsabilidad',[null=>'Seleccione']+ $tipo_centro_responsabilidad , 'default', array('id'=> 'tipo_centro_responsabilidad' , 'class'=>'form-control') ) !!} -->
            </div>
            <div class="form-group div_centro_responsabilidad_search" >
                {!! Form::label('centro_responsabilidad_search', 'Centro de Responsabilidad:', ['id' => 'lbl_centro_responsabilidad_search' ]) !!}
                {!! Form::select('centro_responsabilidad_search',[null=>'Seleccione'] , 'default', array('id'=> 'centro_responsabilidad_search' , 'class'=>'form-control') ) !!}
            </div>
            <div class="form-group div_departamento_search" >
                {!! Form::label('departamento_search', 'departamento:') !!}
                {!! Form::select('departamento_search',[null=>'Seleccione'] , 'default', array('id'=> 'departamento_search' , 'class'=>'form-control') ) !!}
            </div>
        </div>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane" id="tab_organismo">
                <div class="form-group" >
                    {!! Form::label('id_organismo', 'Organismo:') !!}
                    {!! Form::select('id_organismo',[null=>'Seleccione'], 'default', array('id'=> 'id_organismo' , 'class'=>'form-control') ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_subsecretaria">
                <div class="form-group" >
                    {!! Form::label('id_subsecretaria', 'Subsecretaria:') !!}
                    {!! Form::select('id_subsecretaria',[null=>'Seleccione'], 'default', array('id'=> 'id_subsecretaria' , 'class'=>'form-control') ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_division">
                <div class="form-group" >
                    {!! Form::label('id_division', 'División:') !!}
                    {!! Form::select('id_division',[null=>'Seleccione'] + $division, 'default', array('id'=> 'id_division' , 'class'=>'form-control centro_responsabilidad') ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_seremi">
                <div class="form-group" >
                    {!! Form::label('id_seremi', 'Seremi:') !!}
                    {!! Form::select('id_seremi',[null=>'Seleccione']+$seremi, 'default', array('id'=> 'id_seremi' , 'class'=>'form-control centro_responsabilidad') ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_gabinete">
                <div class="form-group" >
                    {!! Form::label('id_gabinete', 'Gabinete:') !!}
                    {!! Form::select('id_gabinete',[null=>'Seleccione']+$gabinete, 'default', array('id'=> 'id_gabinete' , 'class'=>'form-control centro_responsabilidad' ) ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_servicio_salud">
                <div class="form-group" >
                    {!! Form::label('id_servicio_salud', 'Servicio de salud:') !!}
                    {!! Form::select('id_servicio_salud',[null=>'Seleccione']+$servicio_salud, 'default', array('id'=> 'id_servicio_salud' , 'class'=>'form-control') ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_establecimiento">
                <div class="form-group" >
                    {!! Form::label('id_establecimiento', 'Establecimiento:') !!}
                    {!! Form::select('id_establecimiento',[null=>'Seleccione']+$establecimiento, 'default', array('id'=> 'id_establecimiento' , 'class'=>'form-control') ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_departamento">
                <div class="form-group" >
                    {!! Form::label('id_departamento', 'Departamento:') !!}
                    {!! Form::select('id_departamento',[null=>'Seleccione']+$departamento, 'default', array('id'=> 'id_departamento' , 'class'=>'form-control') ) !!}
                </div>
            </div>
            <div class="tab-pane" id="tab_unidad">
                <div class="form-group" >
                    {!! Form::label('id_unidad', 'Unidad:') !!}
                    <div id="box_unidad_select2_msg" style="display:none" class="alert alert-danger">No hay unidades disponibles. </div>
                    <div id="box_unidad_select2">
                        {!! Form::select('id_unidad',[null=>'Seleccione']+$unidad, 'default', array('id'=> 'id_unidad' , 'class'=>'form-control') ) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
</div>
<br /><br />






<!--
 {!! Form::label('region_search', 'Region:') !!}
            {!! Form::select('region_search',[null=>'Seleccione'] + $region, 'default', array('id'=> 'region_search' , 'class'=>'form-control') ) !!}

            {!! Form::label('comuna_search', 'Comuna:') !!}
            {!! Form::select('comuna_search',[null=>'Seleccione'] , 'default', array('id'=> 'comuna_search' , 'class'=>'form-control') ) !!}

-->



<div class="form-group  text-right">
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
    {!! Form::submit('Continuar', ['class' => 'btn btn-success filtro_form_continuar']) !!}
</div>
{!! Form::close() !!}
@include('layouts.boxbottom')
@include('proceso_auditado.js')
@endsection