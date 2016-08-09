<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($auditor,['method' => 'PATCH','route'=>['auditor.update',$auditor->id_auditor]]) !!}
{{ Form::hidden('auditor_registra', $auditor->auditor_registra) }}
{{ Form::hidden('auditor_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
