<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($hallazgo,['method' => 'PATCH','route'=>['hallazgo.update',$hallazgo->id_hallazgo]]) !!}
{{ Form::hidden('hallazgo_registra', $hallazgo->hallazgo_registra) }}
{{ Form::hidden('hallazgo_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
