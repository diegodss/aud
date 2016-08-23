<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($equipo_auditor,['method' => 'PATCH','route'=>['equipo_auditor.update',$equipo_auditor->id_equipo_auditor]]) !!}
{{ Form::hidden('equipo_auditor_registra', $equipo_auditor->equipo_auditor_registra) }}
{{ Form::hidden('equipo_auditor_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
