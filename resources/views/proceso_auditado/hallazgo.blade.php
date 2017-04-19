<?php $titleBox = "Hallazgos"; ?>
@include('layouts.boxtop')
<div class="row">
    <div class="col-xs-12 linespace-bottom">
        @if ($cuanditad_hallazgo_db < $proceso_auditado->cantidad_hallazgo )
        <div class='alert alert-warning'>
            <h4><i class='icon fa fa-warning'></i> Atención</h4>
            Es necesario agregar {{ $proceso_auditado->cantidad_hallazgo - $cuanditad_hallazgo_db }} hallazgos para desbloquear este proceso auditado.
        </div>
        @can('userAction', 'hallazgo-create')
        <a href="{{url('/hallazgo/create/' . $proceso_auditado->id_proceso_auditado.'/multiple/'. ($proceso_auditado->cantidad_hallazgo - $cuanditad_hallazgo_db))}}" class="btn btn-success" >Nuevo Hallazgo</a>
        @endcan
        @endif
    </div>
    <div class="col-xs-12">
        <table class = "table custom-table proceso_auditado_hallazgo" >
            <tbody>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Hallazgo</th>
                    <th>Recomedacion</th>
                    <th>Criticidad</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                {!! $hallazgo_body !!}
            </tbody>
        </table>
    </div>
    <div class="col-xs-12">
        <table class="table-bordered table-striped dataTable recursiva" width="100%" style="display:none" >
            <tbody>
                @foreach ($hallazgo_parent as $linea)

                <?php
                if ($linea->id_compromiso_padre == 0) {
                    $arr_gap = "";
                } else {
                    $arr_gap .= "...";
                }
                ?>
                <tr>

                    <td>
                        {!! $arr_gap !!}  {!! $linea->estado !!}
                    </td>
                    <td>
                        {!! $linea->id_compromiso !!}
                    </td>
                    <td>
                        {!! $linea->id_compromiso_padre !!}
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@include('layouts.boxbottom')