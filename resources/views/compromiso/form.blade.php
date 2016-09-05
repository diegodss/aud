@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
{!! Form::hidden('fl_status',true ) !!}
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('id_hallazgo', 'Hallazgo:') !!}
            {!! Form::textarea('nombre_hallazgo', $hallazgo->nombre_hallazgo,['class'=>'form-control two-lines', 'disabled'=>'disabled']) !!}
            {!! Form::hidden('id_hallazgo',$compromiso->id_hallazgo ) !!}
            <a href="{{ route('hallazgo.edit', $compromiso->id_hallazgo)  }}" class="btn-quick-add">
                ver hallazgo
            </a>
        </div>
        <div class="form-group required" >
            {!! Form::label('nombre_compromiso', 'Compromiso:') !!}
            {!! Form::textarea('nombre_compromiso',null,['class'=>'form-control two-lines']) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('plazo_estimado', 'Plazo Estimado:') !!}
            {!! Form::text('plazo_estimado',null,['class'=>'form-control', 'id'=>'plazo_estimado' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('plazo_comprometido', 'Plazo Comprometido:') !!}
            {!! Form::text('plazo_comprometido',null,['class'=>'form-control', 'id'=>'plazo_comprometido' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('responsable', 'Responsable:') !!}
            {!! Form::text('responsable',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fono_responsable', 'Teléfono Responsable:') !!}
            {!! Form::text('fono_responsable',null,['class'=>'form-control', 'id'=>'fono_responsable']) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('email_responsable', 'E-mail Responsable:') !!}
            {!! Form::text('email_responsable',null,['class'=>'form-control', 'id'=>'email_responsable']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <?php if ($seguimiento_actual->id_seguimiento > 0) { ?>
            <h4>Seguimiento del Compromiso</h4>
            <div class="form-group">
                {!! Form::label('porcentaje_avance', 'Porcentaje de Avance') !!}
                {!! Form::text('porcentaje_avance',$seguimiento_actual->porcentaje_avance,['class'=>'form-control', 'disabled'=>'disabled']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('estado', 'Estado') !!}
                {!! Form::text('estado',$seguimiento_actual->estado,['class'=>'form-control', 'disabled'=>'disabled']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('condicion', 'Condicion') !!}
                {!! Form::text('condicion',$seguimiento_actual->condicion,['class'=>'form-control', 'disabled'=>'disabled']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('created_at', 'Creado en:') !!}
                {!! Form::text('created_at',$seguimiento_actual->created_at,['class'=>'form-control', 'disabled'=>'disabled']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('nombre_usuario_registra', 'Creado por:') !!}
                {!! Form::text('nombre_usuario_registra',$seguimiento_actual->nombre_usuario_registra,['class'=>'form-control', 'disabled'=>'disabled']) !!}
            </div>
        <?php } // if ($compromiso->id_compromiso > 0) { ?>
    </div>
</div>

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ route('hallazgo.edit', $compromiso->id_hallazgo)  }}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('compromiso.js')

