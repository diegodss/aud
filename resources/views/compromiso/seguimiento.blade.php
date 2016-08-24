<?php $titleBox = "Historico de Seguimiento"; ?>
@include('layouts.boxtop')
<div class="row">

    <div class="col-xs-12 linespace-bottom">
        @can('userAction', 'seguimiento-create')
        <a href="{{url('/seguimiento/create/' . $compromiso->id_compromiso)}}" class="btn btn-success" >Nuevo Seguimiento</a>
        @endcan
    </div>
    <div class="col-xs-12">
        {!! $seguimiento !!}
    </div>
</div>

@include('layouts.boxbottom')