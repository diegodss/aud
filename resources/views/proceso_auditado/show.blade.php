@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            <?php echo Form::label('id_auditor', 'Auditor:'); ?>
            <?php echo Form::select('id_auditor', $auditor, $usuario->id_auditor, array('id' => 'id_auditor', 'class' => 'form-control', 'readonly' => 'readonly')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('name', 'Nombre:'); ?>
            <?php echo Form::text('name', null, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('email', 'Correo eletronico:'); ?>
            <?php echo Form::text('email', null, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            <?php echo Form::label('Role', 'Role:'); ?>
            <?php echo Form::select('id_role', $role, $usuario->id_role, array('id' => 'id_role', 'class' => 'form-control', 'readonly' => 'readonly')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('active_directory', 'Validar en Active Directory:'); ?>
            <?php echo Form::select('active_directory', $active_directory, $usuario->active_directory, array('id' => 'active_directory', 'class' => 'form-control', 'readonly' => 'readonly')); ?>
        </div>
        <div class="form-group" id="lblactive_directory_users">
            <?php echo Form::label('active_directory_user', 'Usuario Active Directory:'); ?>
            <?php echo Form::text('active_directory_user', null, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <a href="<?php echo e(url('usuario')); ?>" class="btn btn-primary">Volver</a>
</div>
<?php echo $__env->make('layouts.boxbottom', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<h4>Permisos especificos para este usuario </h4>
<?php echo $__env->make('layouts.boxtop', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<div class="form-group">
    <table class="table table-bordered">
        <tr>
            <td><b>Modulo</b></td>
            <td align="center"><b>visualizar</b></td>
            <td align="center"><b>Crear</b></td>
            <td align="center"><b>editar</b></td>
            <td align="center"><b>eliminar</b></td>
        </tr>
        <?php foreach ($usuarioMenuPermiso as $menuItem): ?>
            <?php if ($menuItem->id_menu_parent == 0): ?>
                <?php $bgColor = "#EEEEEE"; ?>
            <?php else: ?>
                <?php $bgColor = ""; ?>
            <?php endif; ?>
            <tr bgcolor="<?php echo $bgColor; ?>">
                <td> <?php echo Form::hidden('id_menu[]' . $menuItem->id_menu, $menuItem->id_menu, ['class' => 'form-control']); ?>

                    <?php echo e($menuItem->menu); ?></td>
                <td align="center"><?php echo Form::checkbox('visualizar' . $menuItem->id_menu, '1', $menuItem->visualizar, ['class' => 'form-control_none', 'readonly' => 'readonly']); ?></td>
                <td align="center"><?php echo Form::checkbox('agregar' . $menuItem->id_menu, '1', $menuItem->agregar, ['class' => 'form-control_none', 'readonly' => 'readonly']); ?></td>
                <td align="center"><?php echo Form::checkbox('editar' . $menuItem->id_menu, '1', $menuItem->editar, ['class' => 'form-control_none', 'readonly' => 'readonly']); ?></td>
                <td align="center"><?php echo Form::checkbox('eliminar' . $menuItem->id_menu, '1', $menuItem->eliminar, ['class' => 'form-control_none', 'readonly' => 'readonly']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="form-group">
    <a href="<?php echo e(url('usuario')); ?>" class="btn btn-primary">Volver</a>
</div>
@include('layouts.boxbottom')
@endsection
