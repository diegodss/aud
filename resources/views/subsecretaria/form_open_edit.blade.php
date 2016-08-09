<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($subsecretaria,['method' => 'PATCH','route'=>['subsecretaria.update',$subsecretaria->id_subsecretaria]]) !!}
{{ Form::hidden('subsecretaria_registra', $subsecretaria->subsecretaria_registra) }}
{{ Form::hidden('subsecretaria_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
