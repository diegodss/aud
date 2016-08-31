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
        <div class="form-group">
            {!! Form::label('slug', 'Slug:') !!}
            {!! Form::text('slug',null,['class'=>'form-control']) !!}
        </div>
    </div>
    <div class="col-xs-6">

        <h3>Valores por defecto</h3>
        <div class="form-group">
            {!! Form::label('visualizar', 'Visualizar:') !!}
            {!! Form::select('visualizar',[null=>'Seleccione'] +$select_si_no, $menu->visualizar, array('id'=> 'visualizar' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('agregar', 'agregar:') !!}
            {!! Form::select('agregar',[null=>'Seleccione'] +$select_si_no, $menu->agregar, array('id'=> 'agregar' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('editar', 'editar:') !!}
            {!! Form::select('editar',[null=>'Seleccione'] +$select_si_no, $menu->editar, array('id'=> 'editar' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('eliminar', 'eliminar:') !!}
            {!! Form::select('eliminar',[null=>'Seleccione'] +$select_si_no, $menu->eliminar, array('id'=> 'eliminar' , 'class'=>'form-control') ) !!}
        </div>


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

