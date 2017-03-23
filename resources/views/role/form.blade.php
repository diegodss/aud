@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('role', 'Role:') !!}
            {!! Form::text('role',null,['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-6">
    </div>

    <div class="col-xs-12">
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
                    <td> {!! Form::hidden('id_menu[]',$menuItem->id_menu,['class'=>'form-control']) !!}
                        {{ $menuItem->nombre_menu }}</td>
                    <td align="center">{!! Form::checkbox('visualizar' .$menuItem->id_menu, '1', $menuItem->visualizar, ['class'=>'form-control_none', 'id'=>'visualizar'.$menuItem->id_menu]) !!}</td>
                    <td align="center">{!! Form::checkbox('agregar' .$menuItem->id_menu, '1', $menuItem->agregar, ['class'=>'form-control_none', 'id'=>'agregar'.$menuItem->id_menu]) !!}</td>
                    <td align="center">{!! Form::checkbox('editar'  .$menuItem->id_menu, '1', $menuItem->editar, ['class'=>'form-control_none', 'id'=>'editar'.$menuItem->id_menu]) !!}</td>
                    <td align="center">{!! Form::checkbox('eliminar'.$menuItem->id_menu, '1', $menuItem->eliminar, ['class'=>'form-control_none', 'id'=>'eliminar'.$menuItem->id_menu]) !!}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @if ($action == "edit")
    <div class="col-xs-12">
        <div class="form-group">
            {!! Form::checkbox('aplicar_role_usuario' , '1', null, ['class'=>'form-control_none', 'id'=>'aplicar_role_usuario']) !!}
            <b>Aplicar esta actualización para todos los usuarios asociados a este role.</b>

            <div id="box_tipo_acceso_customizado" class="custom-box">


            </div>
        </div>
    </div>
    @endif
</div>
<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('role.js')

