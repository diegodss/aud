@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
{!! Form::hidden('fl_status',true ) !!}
<div class="row">
    <div class="col-xs-6">
        <div class="form-group" >
            {!! Form::label('id_proceso_auditado', 'Proceso:') !!}
            {!! Form::hidden('id_proceso_auditado',$hallazgo->id_proceso_auditado ) !!}
            {!! Form::text('nombre_proceso_auditado',$nombre_proceso_auditado, ['class'=>'form-control', 'disabled'=>'disabled'] ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('nombre_hallazgo', 'Descripción Hallazgo:') !!}
            {!! Form::text('nombre_hallazgo',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('recomendacion', 'Recomendación:') !!}
            {!! Form::text('recomendacion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('criticidad', 'Criticidad:') !!}
            {!! Form::select('criticidad',[null=>'Seleccione'] +$criticidad, $hallazgo->criticidad, array('id'=> 'id_proceso_auditado' , 'class'=>'form-control') ) !!}
        </div>
    </div>
    <div class="col-xs-6">
    </div>
</div>

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a><?php
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

