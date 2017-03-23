@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('id_auditor', 'Auditor:') !!}
            {!! Form::select('id_auditor',[0=>'Seleccione']+$auditor, $usuario->id_auditor, array('id'=> 'id_auditor' , 'class'=>'form-control') ) !!}
        </div>	
        <div class="form-group required">
            {!! Form::label('name', 'Nombre:') !!}
            {!! Form::text('name',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('email', 'Correo eletronico (login):') !!}
            {!! Form::text('email',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group " id="groupPassword">
            {!! Form::label('password', 'Password:', ['id'=>'lblPassword']) !!}
        </div>
        <div class="form-group" id="lblactive_directory_users">
            {!! Form::label('active_directory_user', 'Usuario Active Directory:') !!}
            {!! Form::text('active_directory_user',null,['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            {!! Form::label('Role', 'Role:') !!}
            {!! Form::select('id_role',$role, $usuario->id_role, array('id'=>'id_role', 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('tipo_acceso', 'Tipo de Acceso:') !!}
            {!! Form::text('tipo_acceso',null,['class'=>'form-control', 'id'=>'tipo_acceso', 'readonly'=>'readonly']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('active_directory', 'Validar en Active Directory:') !!}
            {!! Form::select('active_directory',$active_directory, $usuario->active_directory, array('id'=>'active_directory', 'class'=>'form-control', 'disabled'=>'disabled') ) !!}
            {{ Form::hidden('active_directory', 0) }}

        </div>

    </div>
</div>
<div class="form-group">
    <?php
    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
@include('layouts.boxbottom')
<h4>{{ trans('message.permiso_especifico_usuario') }} </h4>
@include('layouts.boxtop')
<div class="form-group">
    <table class="table table-bordered">
        <tr>
            <td><b>Modulo</b></td>
            <td align="center"><b>visualizar</b></td>
            <td align="center"><b>Crear</b></td>
            <td align="center"><b>editar</b></td>
            <td align="center"><b>eliminar</b></td>
        </tr>
        @foreach ($usuarioMenuPermiso as $menuItem)
        @if ($menuItem->id_menu_parent == 0 )
        <?php $bgColor = "#EEEEEE"; ?>
        @else
        <?php $bgColor = ""; ?>
        @endif
        <tr bgcolor="<?php echo $bgColor; ?>">
            <td> {!! Form::hidden('id_menu[]'.$menuItem->id_menu,$menuItem->id_menu,['class'=>'form-control']) !!}
                {{ $menuItem->nombre_menu }}</td>
            <td align="center">{!! Form::checkbox('visualizar' .$menuItem->id_menu, '1', $menuItem->visualizar, ['class'=>'form-control_none menu_item', 'id'=>'visualizar'.$menuItem->id_menu]) !!}</td>
            <td align="center">{!! Form::checkbox('agregar' .$menuItem->id_menu, '1', $menuItem->agregar, ['class'=>'form-control_none menu_item', 'id'=>'agregar'.$menuItem->id_menu]) !!}</td>
            <td align="center">{!! Form::checkbox('editar'  .$menuItem->id_menu, '1', $menuItem->editar, ['class'=>'form-control_none menu_item', 'id'=>'editar'.$menuItem->id_menu]) !!}</td>
            <td align="center">{!! Form::checkbox('eliminar'.$menuItem->id_menu, '1', $menuItem->eliminar, ['class'=>'form-control_none menu_item', 'id'=>'eliminar'.$menuItem->id_menu]) !!}</td>
        </tr>
        @endforeach
    </table>
</div>
<div class="form-group">
    <?php
    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
    <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
</div>
{!! Form::close() !!}
@include('usuario.js')