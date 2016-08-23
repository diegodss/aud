@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('diferencia_tiempo', 'Nombre Seguimiento:') !!}
            {!! Form::text('diferencia_tiempo',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('id_control_compromiso', 'Centro de Responsabilidad:') !!}
            {!! Form::select('id_control_compromiso',[null=>'Seleccione'] +$control_compromiso, $seguimiento->id_control_compromiso, array('id'=> 'id_control_compromiso' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('estado', 'Nombre Jefatura:') !!}
            {!! Form::text('estado',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('condicion', 'Telefono Jefatura:') !!}
            {!! Form::text('condicion',null,['class'=>'form-control', 'id'=>'condicion']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('razon_no_cumplimiento ', 'E-mail Jefatura:') !!}
            {!! Form::text('razon_no_cumplimiento ',null,['class'=>'form-control', 'id'=>'razon_no_cumplimiento']) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('porcentaje_avance', 'Descripcion:') !!}
            {!! Form::textarea('porcentaje_avance',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', $seguimiento->fl_status , ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('nombre_contacto', 'Nombre Contacto:') !!}
            {!! Form::text('nombre_contacto',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fono_contacto', 'Telefono Contacto:') !!}
            {!! Form::text('fono_contacto',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('email_contacto', 'Email de contacto:') !!}
            {!! Form::text('email_contacto',null,['class'=>'form-control']) !!}
        </div>
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

