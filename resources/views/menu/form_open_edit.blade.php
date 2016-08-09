<?php $action = isset($action) ? $action : "edit"; ?>
{!! Form::model($menu,['method' => 'PATCH','route'=>['menu.update',$menu->id_menu]]) !!}
{{ Form::hidden('menu_registra', $menu->menu_registra) }}
{{ Form::hidden('menu_modifica', Auth::user()->id) }}
{{ Form::hidden('action', $action) }}
