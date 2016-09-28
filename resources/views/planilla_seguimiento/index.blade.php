@extends('layouts.app')
@yield('main-content')
@section('main-content')

<?php $titleBox = "Seleccione los campos a mostrar en el informe:"; ?>
@include('layouts.boxtop')
@include('alerts.success')




{!! Form::open(['url' => 'planilla_seguimiento', 'method' => 'get', 'name' => 'planilla_seguimientoForm', 'id' => 'planilla_seguimientoForm']) !!}
{!! Form::hidden('_method','GET') !!}
<div class="row">
    <div class="col-xs-12">
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
            {!! Form::select('subsecretaria',[null=>'Seleccione']+$subsecretaria, $form->subsecretaria, array('id'=> 'subsecretaria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('division', 'División:') !!}
            {!! Form::select('division',[null=>'Seleccione']+$division, $form->division, array('id'=> 'division' , 'class'=>'form-control') ) !!}
        </div>
    </div>
    <div class="col-xs-4">
        <div class="form-group">
            {!! Form::label('estado', 'Estado:') !!}
            {!! Form::select('estado',[null=>'Seleccione']+$estado, $form->estado, array('id'=> 'estado' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('condicion', 'Condicion:') !!}
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
            {!! Form::text('plazo_comprometido_inicio',$form->plazo_comprometido_inicio,['class'=>'form-control width-100', 'id'=>'plazo_comprometido_inicio' ]) !!}
            {!! Form::text('plazo_comprometido_fin',$form->plazo_comprometido_fin,['class'=>'form-control width-100', 'id'=>'plazo_comprometido_fin' ]) !!}
            {!! Form::submit('Continuar', ['class' => 'btn btn-success']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-right">
        <!-- {!! Form::button('Reset', ['class' => 'btn ']) !!} -->
    </div>
</div>
{!! Form::close() !!}
@include('layouts.boxbottom')
<?php $titleBox = "Resultado Informe:"; ?>
@include('layouts.boxtop')


<div class="row planilla_seguimiento" id="planilla_seguimiento">
    <div class="col-xs-12">

        <table class="table-bordered table-striped dataTable" width="{{ $planillaSeguimientoTableSize }}px">
            <thead class="stick" id="sticky">
                <tr>
                    @foreach ($columna as $rowColumna)
                    <TH id='panel{{ $rowColumna }}' width="{{ $planillaSeguimientoColumnSize[$rowColumna] }}px">
                        <?php
                        if ($rowColumna == "ano") {
                            echo "año";
                        } else {
                            echo str_replace("_", " ", $rowColumna);
                        }
                        ?> </TH>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($planillaSeguimiento as $linea)
                <tr>
                    @foreach ($columna as $rowColumna)
                    <td width="{{ $planillaSeguimientoColumnSize[$rowColumna] }}px">{{ str_limit($linea[$rowColumna], 50) }} </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            <a href="#"  id="print" class="print btn btn-app"><i class="fa fa-print"></i> Imprimir</a>
            <a href="{{ URL::to('/') }}/planilla_seguimiento/excel" id="excel1" class="excel  btn btn-app"><i class="fa fa-file-excel-o"></i> Exportar Excel</a>
        </div>
    </div>
    {{ $planillaSeguimiento->appends(Request::query() )->links() }}
    @include('layouts.boxbottom')
    <div class="row">
        <div class="col-xs-6">
            <?php $titleBox = "Grafico por Condición"; ?>
            @include('layouts.boxtop')
            <div id="chart_div"></div>
            @include('layouts.boxbottom')
        </div>
        <div class="col-xs-6">
            <?php $titleBox = "Grafico por Estado"; ?>
            @include('layouts.boxtop')
            <div id="top_x_div" style="width: 400px; height: 300px;"></div>
            @include('layouts.boxbottom')
        </div>
    </div>
    @include('planilla_seguimiento.grafico')
    @include('planilla_seguimiento.js')
    @endsection