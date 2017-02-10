@extends('layouts.app_modal')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

{!! $compromiso_vencido !!}

<div class = "form-group text-right">
    <a href="{{ URL::to('/') }}/compromiso/vencidos/excel/export/{{ $tipo_alerta_semaforo }}" id="excel1" class="excel btn btn-app">
        <i class="fa fa-file-excel-o"></i> Exportar Excel</a>

    <a href="#" id="close" class="btn btn-app"  data-dismiss="modal">
        <i class="fa fa-close"></i>Cerrar</a>
</div>




@include('layouts.boxbottom')
@endsection