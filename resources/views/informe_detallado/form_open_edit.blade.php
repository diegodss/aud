<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($region,['method' => 'PATCH','route'=>['region.update',$region->id_region]]) !!}
{{ Form::hidden('region_registra', $region->region_registra) }}
{{ Form::hidden('region_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
