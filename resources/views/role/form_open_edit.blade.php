<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($role,['method' => 'PATCH','route'=>['role.update',$role->id_role], 'name' => 'roleForm']) !!}
{{ Form::hidden('region_registra', $role->region_registra) }}
{{ Form::hidden('region_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
