@include('alerts.errors')
{!! Form::open(['url' => 'compromiso_import', 'name' => 'compromiso_importForm','files'=>true]) !!}
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
{!! Form::hidden('fl_status',true ) !!}
<div class="row">
    <div class="col-xs-6">
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('Listado de Archivos') !!}
            {!! Form::file('documento_adjunto[]', ['multiple' => 'multiple']) !!}
        </div>
        {!! $archivos_importados !!}
    </div>
</div>
<div class="form-group text-right">
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
@include('compromiso_import.js')