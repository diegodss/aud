@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('id_hallazgo', 'Hallazgo:') !!}
            {!! Form::select('id_hallazgo_view',[null=>'Seleccione'] +$hallazgo, $compromiso->id_hallazgo, array('id'=> 'id_hallazgo' , 'class'=>'form-control', 'disabled'=>'disabled') ) !!}
            {!! Form::hidden('id_hallazgo',$compromiso->id_hallazgo ) !!}
        </div>
        <div class="form-group required" >
            {!! Form::label('nombre_compromiso', 'Compromiso:') !!}
            {!! Form::textarea('nombre_compromiso',null,['class'=>'form-control']) !!}
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
            {!! Form::label('email_responsable ', 'E-mail Responsable:') !!}
            {!! Form::text('email_responsable ',null,['class'=>'form-control', 'id'=>'email_responsable']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('fl_status', 'Activo:') !!}
            {!! Form::checkbox('fl_status', '1', $compromiso->fl_status , ['class'=>'form-control_none', 'id'=>'fl_status', 'data-size'=>'mini']) !!}
        </div>
    </div>
    <div class="col-xs-6">
    </div>
</div>

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ url('compromiso')}}" class="btn btn-primary">Volver</a><?php
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

