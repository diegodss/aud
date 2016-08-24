@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
{!! Form::hidden('fl_status',true) !!}
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('unidad_auditada', 'Unidad Auditada:') !!}
            {!! Form::text('unidad_auditada',$unidad_auditada,['disabled' => 'disabled', 'class'=>'form-control']) !!}
            {!! Form::text('area_proceso_auditado',$area_proceso_auditado,['class'=>'form-control', 'id'=>'area_proceso_auditado' ]) !!}

        </div>
        <div class="form-group required">
            {!! Form::label('objetivo_auditoria', 'Objetivo Auditoria:') !!}
            {!! Form::select('objetivo_auditoria',[null=>'Seleccione']+$objetivo_auditoria, $proceso_auditado->objetivo_auditoria, array('id'=> 'objetivo_auditoria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('actividad_auditoria', 'Actividad Auditoria:') !!}
            {!! Form::select('actividad_auditoria',[null=>'Seleccione']+$actividad_auditoria, $proceso_auditado->actividad_auditoria, array('id'=> 'actividad_auditoria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('tipo_auditoria', 'Tipo de Auditoria:') !!}
            {!! Form::select('tipo_auditoria',[null=>'Seleccione']+$tipo_auditoria, $proceso_auditado->tipo_auditoria, array('id'=> 'tipo_auditoria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('codigo_caigg', 'Codigo CAIGG:') !!}
            {!! Form::text('codigo_caigg',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('id_proceso', 'Proceso Transversal:') !!}
            {!! Form::select('id_proceso',[null=>'Seleccione']+$proceso, $proceso_auditado->id_proceso, array('id'=> 'id_proceso' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('nomenclatura', 'Nomenclatura:') !!}
            {!! Form::select('nomenclatura',[null=>'Seleccione']+$nomenclatura, $proceso_auditado->nomenclatura, array('id'=> 'nomenclatura' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('tipo_informe', 'Tipo de Informe:') !!}
            {!! Form::select('tipo_informe',[null=>'Seleccione']+$tipo_informe, $proceso_auditado->tipo_informe, array('id'=> 'tipo_informe' , 'class'=>'form-control') ) !!}
        </div>

    </div>
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('numero_informe', 'Numero de Informe:' , ['class'=>'form-100']) !!}
            {!! Form::text('numero_informe',null,['id'=>'numero_informe', 'class'=>'form-control  form-100']) !!}
            {!! Form::select('numero_informe_unidad',[null=>'Seleccione']+$numero_informe_unidad, $proceso_auditado->numero_informe_unidad, array('id'=> 'numero_informe_unidad' , 'class'=>'form-control form-100') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('ano', 'Año:') !!}
            {!! Form::select('ano',[null=>'Seleccione']+$ano, $proceso_auditado->ano, array('id'=> 'ano' , 'class'=>'form-control') ) !!}
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
            <div id="grid_equipo_auditor">{!! $grid_equipo_auditor !!}</div>
        </div>

    </div>
</div>


<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ url('proceso_auditado')}}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('proceso_auditado.js')
