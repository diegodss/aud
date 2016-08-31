@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('nombre_establecimiento', 'Nombre Establecimiento:') !!}
            {!! Form::text('nombre_establecimiento',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('codigo_establecimiento', 'Codigo Establecimiento:') !!}
            {!! Form::text('codigo_establecimiento',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('tipo_establecimiento', 'Tipo de establecimiento:') !!}
            {!! Form::select('tipo_establecimiento',[null=>'Seleccione'] +$tipo_establecimiento, $establecimiento->tipo_establecimiento, array('id'=> 'tipo_establecimiento' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('id_servicio_salud', 'Servicio de Salud:') !!}
            {!! Form::select('id_servicio_salud',[null=>'Seleccione'] +$servicio_salud, $establecimiento->id_servicio_salud, array('id'=> 'id_servicio_salud' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('nombre_director', 'Nombre Director:') !!}
            {!! Form::text('nombre_director',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fono_director', 'Telefono Director:') !!}
            {!! Form::text('fono_director',null,['class'=>'form-control', 'id'=>'fono_director']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('email_director', 'E-mail Director:') !!}
            {!! Form::text('email_director',null,['class'=>'form-control', 'id'=>'email_director']) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('descripcion', 'Descripcion:') !!}
            {!! Form::textarea('descripcion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', $establecimiento->fl_status , ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
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
@include('establecimiento.js')

