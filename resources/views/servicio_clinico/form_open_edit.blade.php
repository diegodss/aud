<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($servicio_clinico,['method' => 'PATCH','route'=>['servicio_clinico.update',$servicio_clinico->id_servicio_clinico]]) !!}
{{ Form::hidden('servicio_clinico_registra', $servicio_clinico->servicio_clinico_registra) }}
{{ Form::hidden('servicio_clinico_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
