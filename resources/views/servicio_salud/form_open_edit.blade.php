<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($servicio_salud,['method' => 'PATCH','route'=>['servicio_salud.update',$servicio_salud->id_servicio_salud]]) !!}
{{ Form::hidden('servicio_salud_registra', $servicio_salud->servicio_salud_registra) }}
{{ Form::hidden('servicio_salud_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
