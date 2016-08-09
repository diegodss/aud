<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($centro_responsabilidad,['method' => 'PATCH','route'=>['centro_responsabilidad.update',$centro_responsabilidad->id_centro_responsabilidad]]) !!}
{{ Form::hidden('centro_responsabilidad_registra', $centro_responsabilidad->centro_responsabilidad_registra) }}
{{ Form::hidden('centro_responsabilidad_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
