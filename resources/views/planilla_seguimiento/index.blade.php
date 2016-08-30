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
                {{ $iCampo->column_name }}
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
            {!! Form::select('subsecretaria',[null=>'Seleccione']+$division, $form->division, array('id'=> 'subsecretaria' , 'class'=>'form-control') ) !!}
        </div>
        <div class="form-group">
            {!! Form::label('division', 'División:') !!}
            {!! Form::select('division',[null=>'Seleccione']+$subsecretaria, $form->subsecretaria, array('id'=> 'division' , 'class'=>'form-control') ) !!}
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
            {!! Form::text('plazo_comprometido_inicio','17-08-2016',['class'=>'form-control width-100', 'id'=>'plazo_comprometido_inicio' ]) !!}
            {!! Form::text('plazo_comprometido_fin','18-08-2016',['class'=>'form-control width-100', 'id'=>'plazo_comprometido_fin' ]) !!}
            {!! Form::submit('Generar Informe', ['class' => 'btn btn-success']) !!}
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

<div class="row" class="planilla_seguimiento" id="planilla_seguimiento">
    <div class="col-xs-12">
        <table class="table table-bordered table-striped dataTable">
            <tr>
                @foreach ($columna as $rowColumna)
                <TH id='panel{{ $rowColumna }}' class="planilla_seguimiento_{{ $rowColumna }}"> {{ $rowColumna }} </TH>
                @endforeach
            </tr>
            @foreach ($planillaSeguimiento as $linea)
            <tr>
                @foreach ($columna as $rowColumna)
                <td > {{ $linea[$rowColumna] }} </td>
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>

</div>
<div class="row">
    <div class="col-xs-12 text-right">
        <a href="#"  id="print" class="print btn btn-app"><i class="fa fa-print"></i> Imprimir</a>
        <a href="{{ URL::to('/') }}/planilla_seguimiento/excel" id="excel1" class="excel  btn btn-app"><i class="fa fa-file-excel-o"></i> Exportar Excel</a>
    </div>
</div>
{{ $planillaSeguimiento->appends([$urlParams])->links() }}
<!-- )

-->

<table width="800" cellspacing="0" cellpadding="4" border=0>
    <tr>
        <td width="400"><div id="chart_div"></div></td>

        <td width="400"><div id="top_x_div" style="width: 400px; height: 300px;"></div>.</td>
    </tr>
</table>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages': ['corechart', 'bar']});
// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);
// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

// Create the data table.
var data = new google.visualization.DataTable();
data.addColumn('string', 'Topping');
data.addColumn('number', 'Slices');
data.addRows({!! $graficoCondicion !!});
// Set chart options
var options = {'title': 'Condición',
        'width': 400,
        'height': 300};
// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
chart.draw(data, options);
}
//      google.charts.load('current', {'packages':['bar']});
google.charts.setOnLoadCallback(drawStuff);
function drawStuff() {
var data = new google.visualization.arrayToDataTable({!! $graficoEstado !!});
var options = {
title: 'Estado',
        width: 400,
        legend: {position: 'none'},
        chart: {title: 'Estado'},
        bars: 'vertical', // Required for Material Bar Charts.
        axes: {
        x: {
        0: {side: 'bottom', label: 'Porcentaje'} // Top x-axis.
        }
        },
        bar: {groupWidth: "90%"}
};
var chart = new google.charts.Bar(document.getElementById('top_x_div'));
chart.draw(data, options);
}
;
</script>

@include('layouts.boxbottom')
@include('planilla_seguimiento.js')
@endsection