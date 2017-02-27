@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')
<div class="row">
    <div class="col-xs-12">
        &nbsp;
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <a href="{{ url('planilla_seguimiento/excel/import') }}" target="_blank">Importar</a> <br>
        <a href="{{ url('planilla_seguimiento/excel/procesa') }}" target="_blank">Procesar</a> <br>
        <a href="{{ url('planilla_seguimiento/excel/compromiso_padre') }}" target="_blank">Set Reprogramado</a> <br>
    </div>
</div>
@include('layouts.boxbottom')
@endsection