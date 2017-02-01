@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('email_compromiso_atrasado', 'Correo para notificación de envio de compromisos atrasados (cópia):') !!}
            {!! Form::text('email_compromiso_atrasado',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('template_compromiso_atrasado', 'Mensaje del correo:') !!}
            {!! Form::textarea('template_compromiso_atrasado',null,['class'=>'form-control', 'id'=>'template_compromiso_atrasado' ]) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('asunto_compromiso_atrasado', 'Asunto del correo:') !!}
            {!! Form::text('asunto_compromiso_atrasado',null,['class'=>'form-control' ]) !!}
        </div>
        <h5>Dias para envio de alerta para compromisos atrasados</h5>
        <div class="form-group required">
            {!! Form::label('dias_alerta_compromiso_atrasado_1', 'Alerta 1:',['class'=>'form-100']) !!}
            {!! Form::text('dias_alerta_compromiso_atrasado_1',null,['class'=>'form-control form-100']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('dias_alerta_compromiso_atrasado_2', 'Alerta 2:',['class'=>'form-100']) !!}
            {!! Form::text('dias_alerta_compromiso_atrasado_2',null,['class'=>'form-control form-100']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('dias_alerta_compromiso_atrasado_3', 'Alerta 3:',['class'=>'form-100']) !!}
            {!! Form::text('dias_alerta_compromiso_atrasado_3',null,['class'=>'form-control form-100']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            <a href="#" class="btn btn-success btn-ejecutar-envio">Ejecutar envío</a>
        </div>
        <div class="form-group">            
            <div id="mensaje"></div>
        </div>
        <div class='alert alert-warning'>
            <h4><i class='icon fa fa-warning'></i> Atención.</h4>
            <p>Al hacer click los auditores y auditados van recibir alerta en sus correos.</p>
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
@include('config.js')

