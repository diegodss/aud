@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('id_subsecretaria', 'Subsecretaria:') !!}
            {!! Form::select('id_subsecretaria',[null=>'Seleccione'] +$subsecretaria, $centro_responsabilidad->id_subsecretaria, array('id'=> 'id_subsecretaria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('tipo', 'Tipo:') !!}
            {!! Form::select('tipo',[null=>'Seleccione'] +$tipo, $centro_responsabilidad->tipo, array('id'=> 'tipo' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('nombre_centro_responsabilidad', 'Nombre Centro Responsabilidad:') !!}
            {!! Form::text('nombre_centro_responsabilidad',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('nombre_jefatura', 'Nombre Jefatura:') !!}
            {!! Form::text('nombre_jefatura',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fono_jefatura', 'Telefono Jefatura:') !!}
            {!! Form::text('fono_jefatura',null,['class'=>'form-control', 'id'=>'fono_jefatura']) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('descripcion', 'Descripcion:') !!}
            {!! Form::textarea('descripcion',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', $centro_responsabilidad->fl_status , ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
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
        ?><a href="{{ url('centro_responsabilidad')}}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('centro_responsabilidad.js')

