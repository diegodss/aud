@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('nombre_hallazgo', 'Descripción Hallazgo:') !!}
            {!! Form::text('nombre_hallazgo',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('recomendacion', 'Recomendación:') !!}
            {!! Form::text('recomendacion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group required" >
            {!! Form::label('id_proceso_auditado', 'ProcesoAuditado:') !!}
            {!! Form::select('id_proceso_auditado',[null=>'Seleccione'] +$proceso_auditado, $hallazgo->id_proceso_auditado, array('id'=> 'id_proceso_auditado' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required" >
            {!! Form::label('criticidad', 'criticidad:') !!}
            {!! Form::select('criticidad',[null=>'Seleccione'] +$criticidad, $hallazgo->criticidad, array('id'=> 'id_proceso_auditado' , 'class'=>'form-control') ) !!}
        </div>

        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', $hallazgo->fl_status,  ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
        </div>
    </div>
    <div class="col-xs-6">
    </div>
</div>

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ url('hallazgo')}}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('hallazgo.js')

