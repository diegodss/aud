<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($ministerio,['method' => 'PATCH','route'=>['ministerio.update',$ministerio->id_ministerio]]) !!}
{{ Form::hidden('ministerio_registra', $ministerio->ministerio_registra) }}
{{ Form::hidden('ministerio_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
