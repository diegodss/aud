@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
<div class="row">
    <div class="col-xs-6">
        <div class="form-group required">
            {!! Form::label('nombre_menu', 'Menu:') !!}
            {!! Form::text('nombre_menu',null,['class'=>'form-control' ]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('id_menu_parent', 'Parent:') !!}
            {!! Form::select('id_menu_parent',[0=>'Ninguno'] +$menu_parent, $menu->id_menu_parent, array('id'=> 'id_menu_parent' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('item_menu', 'Item de menu:') !!}
            {!! Form::select('item_menu',[null=>'Seleccione'] +$select_si_no, $menu->item_menu, array('id'=> 'item_menu' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group" >
            {!! Form::label('order', 'Orden:') !!}
            {!! Form::text('order',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('link', 'Link:') !!}
            {!! Form::text('link',null,['class'=>'form-control']) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('slug', 'Slug:') !!}
            {!! Form::text('slug',null,['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-6">

        <h4>Valores por defecto</h4>

        <table class="table table-bordered">
            <tr>
                <td width="100">
                    <div class="form-group required">
                        {!! Form::label('visualizar', 'Visualizar') !!}
                    </div>
                </td>
                <td>
                    {!! Form::radio('visualizar',0, $menu->visualizar, array('id'=> 'visualizar_si' , 'class'=>'') ) !!} {!! $select_si_no[0] !!}
                    {!! Form::radio('visualizar',1, $menu->visualizar, array('id'=> 'visualizar_no' , 'class'=>'') ) !!} {!! $select_si_no[1] !!}
                </td>
            </tr>
            <tr>
                <td><div class="form-group required">
                        {!! Form::label('agregar', 'agregar') !!}
                    </div>
                </td>
                <td>
                    {!! Form::radio('agregar',0, $menu->agregar, array('id'=> 'agregar_si' , 'class'=>'') ) !!} {!! $select_si_no[0] !!}
                    {!! Form::radio('agregar',1, $menu->agregar, array('id'=> 'agregar_no' , 'class'=>'') ) !!} {!! $select_si_no[1] !!}
                </td>
            </tr>
            <tr>
                <td><div class="form-group required">
                        {!! Form::label('editar', 'editar') !!}
                    </div>
                </td>
                <td>
                    {!! Form::radio('editar',0, $menu->editar, array('id'=> 'editar_si' , 'class'=>'') ) !!} {!! $select_si_no[0] !!}
                    {!! Form::radio('editar',1, $menu->editar, array('id'=> 'editar_no' , 'class'=>'') ) !!} {!! $select_si_no[1] !!}
                </td>
            </tr>
            <tr>
                <td><div class="form-group required">
                        {!! Form::label('eliminar', 'eliminar') !!}
                    </div>
                </td>
                <td>
                    {!! Form::radio('eliminar',0, $menu->eliminar, array('id'=> 'eliminar_si' , 'class'=>'') ) !!} {!! $select_si_no[0] !!}
                    {!! Form::radio('eliminar',1, $menu->eliminar, array('id'=> 'eliminar_no' , 'class'=>'') ) !!} {!! $select_si_no[1] !!}
                </td>
            </tr>
        </table>

        @if (isset($roles))
        <h4>Agregar a los siguientes roles:</h4>
        <table class="table table-bordered">
            @foreach ($roles as $id_role => $role )
            <tr>
                <td width="100">
                    {!! Form::checkbox('id_role[]' , $id_role, null, ['class'=>'form-control_none', 'id'=>'id_role'.$id_role]) !!}
                </td>
                <td>{!! $role !!}</td>
            </tr>
            @endforeach
        </table>
        @endif


    </div>
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
@include('menu.js')

