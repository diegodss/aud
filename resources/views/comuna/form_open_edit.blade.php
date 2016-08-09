<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($comuna,['method' => 'PATCH','route'=>['comuna.update',$comuna->id_comuna]]) !!}
{{ Form::hidden('comuna_registra', $comuna->comuna_registra) }}
{{ Form::hidden('comuna_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
