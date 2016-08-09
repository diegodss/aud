@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('nombre_comuna', 'Nombre Comuna:') !!}
            {!! Form::text('nombre_comuna',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('cod_comuna_deis', 'Codigo DEIS:') !!}
            {!! Form::text('cod_comuna_deis',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group required" >
            {!! Form::label('id_region', 'Region:') !!}
            {!! Form::select('id_region',[null=>'Seleccione'] +$region, $comuna->id_region, array('id'=> 'id_region' , 'class'=>'form-control') ) !!}

        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', $comuna->fl_status,  ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
        </div>
    </div>
    <div class="col-xs-6">
    </div>
</div>

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ url('comuna')}}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('comuna.js')

