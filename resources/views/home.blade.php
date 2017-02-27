@extends('layouts.app')
@yield('main-content')
@section('main-content')


@include('layouts.boxtop')

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $compromiso_vencido_verde }}</h3>

                <small>Compromisos vencidos</small>
                <p>Hasta 30 días</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" data-toggle="modal" data-tipo="verde" data-target="#myModal" class="alerta-semaforo small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $compromiso_vencido_amarilla }}</h3>
                <small>Compromisos vencidos</small>
                <p>De 30 a 60 días</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" data-toggle="modal" data-tipo="amarillo" data-target="#myModal" class="alerta-semaforo small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $compromiso_vencido_rojo }}</h3>
                <small>Compromisos vencidos</small>
                <p>Más de 90 días</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" data-toggle="modal" data-tipo="rojo" data-target="#myModal" class="alerta-semaforo small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
@include('layouts.boxbottom')

@include('layouts.boxtop')
<div class="row">
    <div class="col-md-12">
        <p class="text-center">
            <strong>PMG y NO PMG por Estado</strong>
        </p>

        <div class="chart">
            <canvas id="por_estado_gabinete_chart" style="height: 180px;"></canvas>
        </div>
    </div>
</div>
<br />

<div class="row">

    <div class="col-md-12">
        <h2> Gabinete </h2>
        {!! $datagrid_por_estado_gabinete !!}
    </div>
</div>
<div class="row">

    <!-- /.col -->
    <div class="col-md-6">
        <p class="text-center">
            <strong>PMG</strong>
        </p>
        <?php
        $i = 0;
        foreach ($porCondicionGabinetePmg as $item) :
            ?>
            <div class="progress-group">
                <span class="progress-text">{{ $item->condicion }}</span>
                <span class="progress-number"><b>{{ $item->tot_pmg }}</b>/ {{ $total_condicion_gabinete_pmg }} </span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-{{ $condicion_css[$i] }}" style="width: {{ $item->perc_pmg }}%"></div>
                </div>
            </div>
            <?php
            $i++;
        endforeach
        ?>
    </div>
    <!-- /.col -->
    <div class="col-md-6">
        <p class="text-center">
            <strong>NO PMG</strong>
        </p>
        <?php
        $i = 0;
        foreach ($porCondicionGabineteNoPmg as $item) :
            ?>
            <div class="progress-group">
                <span class="progress-text">{{ $item->condicion }}</span>
                <span class="progress-number"><b>{{ $item->tot_no_pmg }}</b>/ {{ $total_condicion_gabinete_no_pmg }} </span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-{{ $condicion_css[$i] }}" style="width: {{ $item->perc_no_pmg }}%"></div>
                </div>
            </div>
            <?php
            $i++;
        endforeach
        ?>
    </div>
    <!-- /.col -->
</div>
@include('layouts.boxbottom')

@include('layouts.boxtop')
<div class="row">
    <div class="col-md-12">
        <h2> Salud Pública </h2>
        <p class="text-center">
            <strong>PMG y NO PMG por Estado</strong>
        </p>

        <div class="chart">
            <canvas id="por_estado_ssp_chart" style="height: 180px;"></canvas>
        </div>
    </div>
</div>
<br />

<div class="row">

    <div class="col-md-12">
        {!! $datagrid_por_estado_ssp !!}
    </div>
</div>
<div class="row">

    <!-- /.col -->
    <div class="col-md-6">
        <p class="text-center">
            <strong>PMG</strong>
        </p>
        <?php
        $i = 0;
        foreach ($porCondicionSspPmg as $item) :
            ?>
            <div class="progress-group">
                <span class="progress-text">{{ $item->condicion }}</span>
                <span class="progress-number"><b>{{ $item->tot_pmg }}</b>/ {{ $total_condicion_ssp_pmg }} </span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-{{ $condicion_css[$i] }}" style="width: {{ $item->perc_pmg }}%"></div>
                </div>
            </div>
            <?php
            $i++;
        endforeach
        ?>
    </div>
    <!-- /.col -->
    <div class="col-md-6">
        <p class="text-center">
            <strong>NO PMG</strong>
        </p>
        <?php
        $i = 0;
        foreach ($porCondicionSspNoPmg as $item) :
            ?>
            <div class="progress-group">
                <span class="progress-text">{{ $item->condicion }}</span>
                <span class="progress-number"><b>{{ $item->tot_no_pmg }}</b>/ {{ $total_condicion_ssp_no_pmg }} </span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-{{ $condicion_css[$i] }}" style="width: {{ $item->perc_no_pmg }}%"></div>
                </div>
            </div>
            <?php
            $i++;
        endforeach
        ?>
    </div>
    <!-- /.col -->
</div>
@include('layouts.boxbottom')


@include('layouts.boxtop')
<div class="row">
    <div class="col-md-12">
        <h2> Redes Asistenciales </h2>
        <p class="text-center">
            <strong>PMG y NO PMG por Estado</strong>
        </p>

        <div class="chart">
            <canvas id="por_estado_ra_chart" style="height: 180px;"></canvas>
        </div>
    </div>
</div>
<br />

<div class="row">
    <div class="col-md-12">
        {!! $datagrid_por_estado_ra !!}
    </div>
</div>
<div class="row">

    <!-- /.col -->
    <div class="col-md-6">
        <p class="text-center">
            <strong>PMG</strong>
        </p>
        <?php
        $i = 0;
        foreach ($porCondicionRaPmg as $item) :
            ?>
            <div class="progress-group">
                <span class="progress-text">{{ $item->condicion }}</span>
                <span class="progress-number"><b>{{ $item->tot_pmg }}</b>/ {{ $total_condicion_ra_pmg }} </span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-{{ $condicion_css[$i] }}" style="width: {{ $item->perc_pmg }}%"></div>
                </div>
            </div>
            <?php
            $i++;
        endforeach
        ?>
    </div>
    <!-- /.col -->
    <div class="col-md-6">
        <p class="text-center">
            <strong>NO PMG</strong>
        </p>
        <?php
        $i = 0;
        foreach ($porCondicionRaNoPmg as $item) :
            ?>
            <div class="progress-group">
                <span class="progress-text">{{ $item->condicion }}</span>
                <span class="progress-number"><b>{{ $item->tot_no_pmg }}</b>/ {{ $total_condicion_ra_no_pmg }} </span>

                <div class="progress sm">
                    <div class="progress-bar progress-bar-{{ $condicion_css[$i] }}" style="width: {{ $item->perc_no_pmg }}%"></div>
                </div>
            </div>
            <?php
            $i++;
        endforeach
        ?>
    </div>
    <!-- /.col -->
</div>
@include('layouts.boxbottom')

@include('layouts.boxtop')
<div class="row">
    <div class="col-md-6 col-xs-6 col-sm-12">
        <H4>Otras Condiciones</H4>
        <canvas id="pieChart" height="100" style="margin:20px;"></canvas>
    </div>

    <div class="col-md-6 col-xs-6 col-sm-12">
        <H4>Otros Estados</H4>
        <canvas id="pieChart2" height="100" style="margin:20px;"></canvas>
    </div>

</div>
<?php //$titleBox = 'Area auditada y cantidad de compromisos por condicion'; ?>
<div class="row">
    <div class="col-md-6 col-xs-6 col-sm-12">

        <div class="box-footer no-padding">
            <ul class="nav nav-pills nav-stacked">
                @foreach ($porCondicionOtros as $item )
                <li><a href="#" style="color: {{ $item->color }}">{!! $item->label !!}
                        <span class="pull-right">{!! $item->value !!}</span></a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-6 col-xs-6 col-sm-12">

        <div class="box-footer no-padding">
            <ul class="nav nav-pills nav-stacked">
                @foreach ($porEstadoOtros as $item )
                <li><a href="#" style="color: {{ $item->color }}">{!! $item->label !!}
                        <span class="pull-right "> {!! $item->value !!}</span></a></li>
                @endforeach
            </ul>
        </div>

    </div>
</div>



@include('layouts.boxbottom')

<?php $modal = "no"; ?>
@include('layouts.partials.modal.header')
<div id="compromiso_vencido">Cargando...</div>
@include('layouts.partials.modal.footer')


@include('home.js')


@endsection
