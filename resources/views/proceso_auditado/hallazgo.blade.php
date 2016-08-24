<?php $titleBox = "Hallazgos"; ?>
@include('layouts.boxtop')
<div class="row">

    <div class="col-xs-12 linespace-bottom">
        @can('userAction', 'hallazgo-create')
        <a href="{{url('/hallazgo/create/' . $proceso_auditado->id_proceso_auditado)}}" class="btn btn-success" >Nuevo Hallazgo</a>
        @endcan
    </div>
    <div class="col-xs-12">
        {!! $hallazgo !!}
    </div>
</div>

@include('layouts.boxbottom')