@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.errors')
{!! Form::model($role,['method' => 'PATCH','route'=>['role.update',$role->id_role]]) !!}
{{ Form::hidden('role_registra', $role->role_registra) }}
{{ Form::hidden('role_modifica', Auth::user()->id) }}
<div class="form-group">
    {!! Form::label('role', 'Role:') !!}
    {!! Form::text('role',null,['class'=>'form-control']) !!}
</div>
<div class="form-group">
    <table class="table table-bordered">
        <tr>
            <td><b>Modulo</b></td>
            <td align="center"><b>visualizar</b></td>
            <td align="center"><b>Crear</b></td>
            <td align="center"><b>editar</b></td>
            <td align="center"><b>eliminar</b></td>
        </tr>
        @foreach ($roleMenuPermiso as $menuItem)
        @if ($menuItem->id_menu_parent == 0 )
        <?php $bgColor = "#EEEEEE"; ?>
        @else
        <?php $bgColor = ""; ?>
        @endif
        <tr bgcolor="<?php echo $bgColor; ?>">
            <td> {!! Form::hidden('id_menu[]'.$menuItem->id_menu,$menuItem->id_menu,['class'=>'form-control']) !!}
                {{ $menuItem->nombre_menu }}</td>
            <td align="center">{!! Form::checkbox('visualizar' .$menuItem->id_menu, '1', $menuItem->visualizar, ['class'=>'form-control_none', 'id'=>'visualizar'.$menuItem->id_menu]) !!}</td>
            <td align="center">{!! Form::checkbox('agregar' .$menuItem->id_menu, '1', $menuItem->agregar, ['class'=>'form-control_none', 'id'=>'agregar'.$menuItem->id_menu]) !!}</td>
            <td align="center">{!! Form::checkbox('editar'  .$menuItem->id_menu, '1', $menuItem->editar, ['class'=>'form-control_none', 'id'=>'editar'.$menuItem->id_menu]) !!}</td>
            <td align="center">{!! Form::checkbox('eliminar'.$menuItem->id_menu, '1', $menuItem->eliminar, ['class'=>'form-control_none', 'id'=>'eliminar'.$menuItem->id_menu]) !!}</td>
        </tr>
        @endforeach
    </table>
</div>
<div class="form-group">
    {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
    <a href="{{ url('role')}}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}
@include('layouts.boxbottom')
@include('role.js')
@endsection