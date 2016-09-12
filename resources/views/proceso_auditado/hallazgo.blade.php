<?php $titleBox = "Hallazgos"; ?>
@include('layouts.boxtop')
<div class="row">

    <div class="col-xs-12 linespace-bottom">
        count {{$cuanditad_hallazgo_db}} -
        pro {{$proceso_auditado->cuantidad_hallazgo}}

        @if ($cuanditad_hallazgo_db < $proceso_auditado->cuantidad_hallazgo )
        <div class='alert alert-warning'>
            <h4><i class='icon fa fa-warning'></i> Atención</h4>
            Es necesario agregar {{ $proceso_auditado->cuantidad_hallazgo - $cuanditad_hallazgo_db }} para desbloquear este proceso auditado.
        </div>
        @can('userAction', 'hallazgo-create')
        <a href="{{url('/hallazgo/create/' . $proceso_auditado->id_proceso_auditado.'/multiple/'.$proceso_auditado->cuantidad_hallazgo)}}" class="btn btn-success" >Nuevo Hallazgo</a>
        @endcan
        @endif
    </div>
    <div class="col-xs-12">
        {!! $hallazgo !!}
    </div>
</div>

@include('layouts.boxbottom')