<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($unidad,['method' => 'PATCH','route'=>['unidad.update',$unidad->id_unidad]]) !!}
{{ Form::hidden('unidad_registra', $unidad->unidad_registra) }}
{{ Form::hidden('unidad_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
