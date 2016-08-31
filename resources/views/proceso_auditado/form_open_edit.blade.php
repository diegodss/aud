<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($proceso_auditado,['method' => 'PATCH','route'=>['proceso_auditado.update',$proceso_auditado->id_proceso_auditado], 'name' => 'proceso_auditadoForm', 'id' => 'proceso_auditadoForm']) !!}
{{ Form::hidden('proceso_auditado_registra', $proceso_auditado->proceso_auditado_registra) }}
{{ Form::hidden('proceso_auditado_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
