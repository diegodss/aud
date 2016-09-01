<?php $titleBox = "Compromisos"; ?>
@include('layouts.boxtop')
<div class="row">

    <div class="col-xs-12 linespace-bottom">
        @can('userAction', 'compromiso-create')
        <a href="{{url('/compromiso/create/' . $hallazgo->id_hallazgo)}}" class="btn btn-success" >Nuevo Compromiso</a>
        @endcan
    </div>
    <div class="col-xs-12">
        {!! $compromiso !!}
    </div>
</div>

@include('layouts.boxbottom')