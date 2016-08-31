@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('nombre_equipo_auditor', 'Descrpción del equipo:') !!}
            {!! Form::text('nombre_equipo_auditor',null,['class'=>'form-control' ]) !!}
        </div>

        <?php if ($equipo_auditor->id_equipo_auditor != "") { ?>
            <h5>Auditores</h5>
            <div class="form-group">
                <div class="width-468">
                    {!! Form::select('id_auditor',[null=>'Seleccione'] + $auditor, 'default', array('id'=> 'id_auditor' , 'class'=>'form-control') ) !!}
                </div>
                {!! Form::button('Agregar', ['class' => 'btn btn-success', 'id'=>'btn-agregar-equipo-auditor']) !!}
            </div>
            <div class="form-group">
                <div id="grid_equipo_auditor"></div>
            </div>

            <div class="form-group">
                {!! Form::label('fl_status', 'Activo:') !!}
                {!! Form::checkbox('fl_status', '1', $equipo_auditor->fl_status, ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
            </div>
        <?php } // if (isset($equipo_auditor)) { ?>
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
@include('equipo_auditor.js')

