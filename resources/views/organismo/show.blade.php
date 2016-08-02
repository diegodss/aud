@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-6">
        <div class="form-group">
            <?php echo Form::label('nombre_ministro', 'Auditor:'); ?>
            <?php echo Form::select('nombre_ministro', $auditor, $organismo->nombre_ministro, array('id' => 'nombre_ministro', 'class' => 'form-control', 'readonly' => 'readonly')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('name', 'Nombre:'); ?>
            <?php echo Form::text('name', null, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('nombre_organismo', 'Correo eletronico:'); ?>
            <?php echo Form::text('nombre_organismo', null, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="form-group">
            <?php echo Form::label('Role', 'Role:'); ?>
            <?php echo Form::select('nombre_contacto', $role, $organismo->nombre_contacto, array('id' => 'nombre_contacto', 'class' => 'form-control', 'readonly' => 'readonly')); ?>
        </div>
        <div class="form-group">
            <?php echo Form::label('fono_contacto', 'Validar en Active Directory:'); ?>
            <?php echo Form::select('fono_contacto', $fono_contacto, $organismo->fono_contacto, array('id' => 'fono_contacto', 'class' => 'form-control', 'readonly' => 'readonly')); ?>
        </div>
        <div class="form-group" id="lblemail_contactos">
            <?php echo Form::label('email_contacto', 'Usuario Active Directory:'); ?>
            <?php echo Form::text('email_contacto', null, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <a href="<?php echo e(url('organismo')); ?>" class="btn btn-primary">Volver</a>
</div>
<?php echo $__env->make('layouts.boxbottom', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<h4>Permisos especificos para este organismo </h4>
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
        <?php foreach ($organismoMenuPermiso as $menuItem): ?>
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
    <a href="<?php echo e(url('organismo')); ?>" class="btn btn-primary">Volver</a>
</div>
@include('layouts.boxbottom')
@endsection
