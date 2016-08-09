@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('nombre_unidad', 'Nombre Unidad:') !!}
            {!! Form::text('nombre_unidad',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('id_departamento', 'Departamento:') !!}
            {!! Form::select('id_departamento',[null=>'Seleccione'] +$departamento, $unidad->id_departamento, array('id'=> 'id_departamento' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('id_servicio_clinico', 'Servicio Clinico:') !!}
            {!! Form::select('id_servicio_clinico',[null=>'Seleccione'] +$servicio_clinico, $unidad->id_servicio_clinico, array('id'=> 'id_servicio_clinico' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('nombre_jefatura_unidad', 'Nombre Jefatura:') !!}
            {!! Form::text('nombre_jefatura_unidad',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fono_jefatura', 'Telefono Jefatura:') !!}
            {!! Form::text('fono_jefatura',null,['class'=>'form-control', 'id'=>'fono_jefatura']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('email_jefatura', 'E-mail Jefatura:') !!}
            {!! Form::text('email_jefatura',null,['class'=>'form-control', 'id'=>'email_jefatura']) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('descripcion', 'Descripcion:') !!}
            {!! Form::textarea('descripcion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', $unidad->fl_status , ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
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
        ?><a href="{{ url('unidad')}}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('unidad.js')

