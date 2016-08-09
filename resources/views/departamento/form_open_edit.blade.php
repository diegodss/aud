<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($departamento,['method' => 'PATCH','route'=>['departamento.update',$departamento->id_departamento]]) !!}
{{ Form::hidden('departamento_registra', $departamento->departamento_registra) }}
{{ Form::hidden('departamento_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
