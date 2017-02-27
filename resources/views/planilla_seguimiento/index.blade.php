@extends('layouts.app_modal')
@yield('main-content')
@section('main-content')
<div class="row bar_planilla_seguimiento">
    <div class="col-xs-4">
        <h1>Planilla de Seguimiento</h1>
    </div>
    <div class="col-xs-8 text-right">
        <a href="{{ url('/') }}"  id="btn_back" class="print btn btn-app"><i class="fa fa-arrow-circle-left"></i> Volver</a>
        <a href="#graficos"  id="btn_chart" class="print btn btn-app"><i class="fa fa-bar-chart"></i> Graficos</a>
        <a href="#"  id="btn_filter" data-toggle="modal" data-target="#modal_filtro_planilla_seguimiento" class="print btn btn-app"><i class="fa fa-filter"></i> Filtrar</a>
        <a href="#"  id="print" class="print btn btn-app"><i class="fa fa-print"></i> Imprimir</a>
        <a href="{{ URL::to('/') }}/planilla_seguimiento/excel" id="excel1" class="excel  btn btn-app"><i class="fa fa-file-excel-o"></i> Exportar Excel</a>
        <a href="{{ URL::to('/') }}/planilla_seguimiento/medio_verificacion" id="btn_medio_verificacion" class="btn_medio_verificacion  btn btn-app"><i class="fa fa-download"></i>Medio de verificación</a>
    </div>
</div>

@include('alerts.success')
@include('planilla_seguimiento.filtro')

<?php if (count($busqueda) > 0) : ?>
    <div class="row filtro_planilla_seguimiento">
        <div class="col-xs-12">
            Filtros:
            @foreach ($busqueda as $filtro => $value )
            <span  class='btn btn-info btn-xs'><b>{{ $filtro }}</b>: {{ $value }}</span> &nbsp;
            @endforeach
            <a href='{{ url('planilla_seguimiento') }}'  class='btn btn-default btn-xs'>Limpiar</a>
        </div></div>
<?php endif; ?>

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
                    <td width="{{ $planillaSeguimientoColumnSize[$rowColumna] }}px">
                        <a href="<?php echo url('compromiso/' . $linea["id"] . '/edit'); ?>">{{ str_limit($linea[$rowColumna], 50) }} </a></td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<div class="row">
    <div class="col-xs-6 pull-left">
        <ul class="pagination">
            <li> <a name=fo>
                    <?php
                    // diego

                    $itemsPage = 40;


                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }

                    $i_start = (($page * (int) $itemsPage) - $itemsPage) + 1;
                    $i_end = $page * (int) $itemsPage;
                    if ($i_end > $planillaSeguimiento->total())
                        $i_end = $planillaSeguimiento->total();

                    echo "Mostrando <b>" . $i_start . "</b> a <b>" . $i_end . "</b> de <b>" . $planillaSeguimiento->total() . "</b> entradas ";
                    ?>
                </a>
            </li>
        </ul>
    </div>
    <div class="col-xs-6 pull-right"> {{ $planillaSeguimiento->appends(Request::query() )->links() }}</div>
</div>
<a name="graficos"></a>
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