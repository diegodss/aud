<?php
$modal = "no";
$idModal = "modal_filtro_planilla_seguimiento";
?>
@include('layouts.partials.modal.header')
<!-- div id="vw_planilla_seguimiento">Cargando...</div -->
{!! Form::open(['url' => 'planilla_seguimiento', 'method' => 'get', 'name' => 'planilla_seguimientoForm', 'id' => 'planilla_seguimientoForm']) !!}
{!! Form::hidden('_method','GET') !!}
<div class="row">
    <div class="col-xs-12">
        <h1>Seleccione los campos a mostrar en el informe</h1>
        <table width="100%" border="0" cellpadding="2" cellspacing="1" >
            <?php
            $numeroColuna = 4;
            $columnaCount = 0;
            ?>
            @foreach ($camposTabla as $iCampo)
            @foreach ($columna as $rowColumna)

            <?php
            $checked = "";
            if ($iCampo->column_name == $rowColumna) {
                $checked = "checked";
                break;
            }
            ?>
            @endforeach
            <td width="160">
                {!! Form::checkbox('columna[]', $iCampo->column_name, $checked, ['class'=>'form-control_none', 'id'=>$iCampo->column_name]) !!}
                <?php
                if ($iCampo->column_name == "ano") {
                    echo "año";
                } else {
                    echo str_replace("_", " ", $iCampo->column_name);
                }
                ?>
            </td>
            <?php
            $columnaCount++;
            if ($columnaCount >= $numeroColuna) {
                $columnaCount = 0;
                echo "</tr><tr>";
            }
            ?>
            @endforeach
            <tr>
                <td colspan="{!! $numeroColuna !!}" >
                    <input type=checkbox value="Check All" id="checkAll">
                    <b>Seleccionar Todos </b><br>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-xs-12"><h4>Filtros</h4></div>
    <div class="col-xs-4">
        <div class="form-group">
            {!! Form::label('subsecretaria', 'Subsecretaria:') !!}
            {!! Form::select('subsecretaria',[null=>'Seleccione']+$subsecretaria, $form->subsecretaria, array('id'=> 'subsecretaria_search' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('division', 'División:') !!}
            {!! Form::select('division',[null=>'Seleccione']+$division, $form->division, array('id'=> 'division_search' , 'class'=>'form-control') ) !!}
        </div>
    </div>
    <div class="col-xs-4">
        <div class="form-group">
            {!! Form::label('estado', 'Estado:') !!}
            {!! Form::select('estado',[null=>'Seleccione']+$estado, $form->estado, array('id'=> 'estado' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('condicion', 'Condición:') !!}
            {!! Form::select('condicion',[null=>'Seleccione']+$condicion, $form->condicion, array('id'=> 'condicion' , 'class'=>'form-control') ) !!}
        </div>
    </div>
    <div class="col-xs-4">
        <div class="form-group required">
            {!! Form::label('nomenclatura', 'Nomenclatura:') !!}
            {!! Form::select('nomenclatura',[null=>'Seleccione']+$nomenclatura, $form->nomenclatura, array('id'=> 'nomenclatura' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group required">
            {!! Form::label('plazo_comprometido_inicio', 'Plazo Comprometido:', ['class'=>'width-100']) !!}
            {!! Form::text('plazo_comprometido_inicio',$form->plazo_comprometido_inicio,['class'=>'form-control width-100', 'id'=>'plazo_comprometido_inicio', 'placeholder'=>'Desde' ]) !!}
            {!! Form::text('plazo_comprometido_fin',$form->plazo_comprometido_fin,['class'=>'form-control width-100', 'id'=>'plazo_comprometido_fin', 'placeholder'=>'Hasta' ]) !!}
            {!! Form::submit('Continuar', ['class' => 'btn btn-success']) !!}
            <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> </button>

        </div>
    </div>
</div>
{!! Form::close() !!}
@include('layouts.partials.modal.footer')