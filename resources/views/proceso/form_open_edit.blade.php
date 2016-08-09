<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($proceso,['method' => 'PATCH','route'=>['proceso.update',$proceso->id_proceso]]) !!}
{{ Form::hidden('proceso_registra', $proceso->proceso_registra) }}
{{ Form::hidden('proceso_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
