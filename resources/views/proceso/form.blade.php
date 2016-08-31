@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('nombre_proceso', 'Nombre Proceso:') !!}
            {!! Form::text('nombre_proceso',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('descripcion', 'Descripcion:') !!}
            {!! Form::textarea('descripcion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('responsable_proceso', 'Responsable:') !!}
            {!! Form::text('responsable_proceso',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fono_responsable_proceso', 'Telefono Responsable:') !!}
            {!! Form::text('fono_responsable_proceso',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('email_responsable_proceso', 'Email Responsable:') !!}
            {!! Form::text('email_responsable_proceso',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1','1', ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('nombre_contacto', 'Nombre Contacto:') !!}
            {!! Form::text('nombre_contacto',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fono_contacto', 'Telefono de contacto:') !!}
            {!! Form::text('fono_contacto',null,['class'=>'form-control']) !!}
        </div>
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
@include('proceso.js')

