<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($config,['method' => 'PATCH','route'=>['config.update',$config->id_config],'name' => 'configForm']) !!}
{{ Form::hidden('config_registra', $config->config_registra) }}
{{ Form::hidden('config_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
