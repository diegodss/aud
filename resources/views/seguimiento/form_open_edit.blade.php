<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($seguimiento,['method' => 'PATCH','route'=>['seguimiento.update',$seguimiento->id_seguimiento]]) !!}
{{ Form::hidden('seguimiento_registra', $seguimiento->seguimiento_registra) }}
{{ Form::hidden('seguimiento_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
