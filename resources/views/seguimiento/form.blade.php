@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
{!! Form::hidden('fl_status',true ) !!}
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('id_compromiso', 'Compromiso:') !!}
            {!! Form::select('id_compromiso_view',[null=>'Seleccione'] +$compromiso, $seguimiento->id_compromiso, array('id'=> 'id_compromiso' , 'class'=>'form-control', 'disabled'=>'disabled') ) !!}
            {!! Form::hidden('id_compromiso',$seguimiento->id_compromiso ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('diferencia_tiempo', 'Diferencia de tiempo:') !!}
            {!! Form::text('diferencia_tiempo',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('estado', 'Estado:') !!}
            {!! Form::select('estado',[null=>'Seleccione']+$estado, $seguimiento->estado, array('id'=> 'estado' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('condicion', 'Condicion:') !!}
            {!! Form::select('condicion',[null=>'Seleccione']+$condicion, $seguimiento->condicion, array('id'=> 'condicion' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required" >
            {!! Form::label('porcentaje_avance', 'Porcentaje de Avance') !!}
            {!! Form::text('porcentaje_avance',null,['class'=>'form-control width-100']) !!} %
        </div>
        <div class="form-group">
            {!! Form::label('razon_no_cumplimiento ', 'Razón de no cumplimiento:') !!}
            {!! Form::textarea('razon_no_cumplimiento ', $seguimiento->razon_no_cumplimiento,['class'=>'form-control', 'id'=>'razon_no_cumplimiento']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <h3>Medio de Verificación</h3>
        <div class="form-group">
            {!! Form::label('Documentos Adjuntos') !!}
            {!! Form::file('documento_adjunto[]', ['multiple' => 'multiple']) !!}
        </div>
        {!! $medio_verificacion !!}
    </div>
</div>

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ url('seguimiento')}}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('seguimiento.js')
