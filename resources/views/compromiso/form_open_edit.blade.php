<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($compromiso,['method' => 'PATCH','route'=>['compromiso.update',$compromiso->id_compromiso]]) !!}
{{ Form::hidden('compromiso_registra', $compromiso->compromiso_registra) }}
{{ Form::hidden('compromiso_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
