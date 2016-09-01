<?php echo $__env->make('alerts.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<?php echo Form::hidden('fl_status',true ); ?>

<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            <?php echo Form::label('id_compromiso', 'Compromiso:'); ?>

            <?php echo Form::textarea('nombre_compromiso', $compromiso->nombre_compromiso,['class'=>'form-control two-lines', 'disabled'=>'disabled']); ?>

            <?php echo Form::hidden('id_compromiso',$seguimiento->id_compromiso ); ?>

        </div>
        <div class="form-group required">
            <?php echo Form::label('diferencia_tiempo', 'Diferencia de tiempo:'); ?>

            <?php echo Form::text('diferencia_tiempo',$seguimiento->diferencia_tiempo,['class'=>'form-control' ]); ?>

        </div>
        <div class="form-group required">
            <?php echo Form::label('estado', 'Estado:'); ?>

            <?php echo Form::select('estado',[null=>'Seleccione']+$estado, $seguimiento->estado, array('id'=> 'estado' , 'class'=>'form-control') ); ?>

        </div>
        <div class="form-group required">
            <?php echo Form::label('condicion', 'Condicion:'); ?>

            <?php echo Form::select('condicion',[null=>'Seleccione']+$condicion, $seguimiento->condicion, array('id'=> 'condicion' , 'class'=>'form-control') ); ?>

        </div>
        <div class="form-group required" >
            <?php echo Form::label('porcentaje_avance', 'Porcentaje de Avance'); ?>

            <?php echo Form::text('porcentaje_avance',null,['class'=>'form-control width-100']); ?> %
        </div>
        <div class="form-group">
            <?php echo Form::label('razon_no_cumplimiento', 'Razón de no cumplimiento:'); ?>

            <?php echo Form::textarea('razon_no_cumplimiento', $seguimiento->razon_no_cumplimiento,['class'=>'form-control', 'id'=>'razon_no_cumplimiento']); ?>

        </div>
    </div>
    <div class="col-xs-6">
        <h3>Medio de Verificación</h3>
        <div class="form-group">
            <?php echo Form::label('Documentos Adjuntos'); ?>

            <?php echo Form::file('documento_adjunto[]', ['multiple' => 'multiple']); ?>

        </div>
        <?php echo $medio_verificacion; ?>

    </div>
</div>

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="<?php echo e(URL::previous()); ?>" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        <?php echo Form::submit('Guardar', ['class' => 'btn btn-success']); ?>

        <?php
    }
    ?>
</div>

<?php echo Form::close(); ?>

<?php echo $__env->make('seguimiento.js', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

