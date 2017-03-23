<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($usuario,['method' => 'PATCH','route'=>['usuario.update',$usuario->id], 'name' => 'usuarioForm']) !!}
{{ Form::hidden('region_registra', $usuario->region_registra) }}
{{ Form::hidden('region_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
