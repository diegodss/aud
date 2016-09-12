@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

{!! Form::open(['url' => 'hallazgo', 'name' => 'hallazgoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
{{ Form::hidden('cuantidad_hallazgo', $cuantidad_hallazgo) }}

@include('alerts.errors')
<input type="hidden" name="modal" id="modal_input" value="<?php echo isset($modal) ? $modal : ""; ?>" />
{!! Form::hidden('fl_status',true ) !!}
<div class="row">
    <div class="col-xs-12">
        <div class="form-group" >
            {!! Form::label('id_proceso_auditado', 'Proceso:') !!}
            {!! Form::hidden('id_proceso_auditado',$hallazgo->id_proceso_auditado ) !!}
            {!! Form::text('nombre_proceso_auditado',$nombre_proceso_auditado, ['class'=>'form-control', 'disabled'=>'disabled'] ) !!}
            <a href="{{ route('proceso_auditado.edit', $hallazgo->id_proceso_auditado)  }}" class="btn-quick-add">
                ver proceso
            </a>
        </div>
    </div>
</div>
@for ($i=1;$i<=$cuantidad_hallazgo;$i++)
<div class="row hallazgo_multiple">
    <div class="col-xs-12"><h4>Hallazgo {{ $i }}</h4></div>
    <div class="col-xs-4">
        <div class="form-group required">
            {!! Form::label('nombre_hallazgo_'.$i, 'Descripción Hallazgo:') !!}
            {!! Form::textarea('nombre_hallazgo_'.$i, null,['class'=>'form-control two-lines', 'id'=>'nombre_hallazgo']) !!}
        </div>
    </div>
    <div class="col-xs-4">
        <div class="form-group required">
            {!! Form::label('recomendacion_'.$i, 'Recomendación:') !!}
            {!! Form::textarea('recomendacion_'.$i, null,['class'=>'form-control two-lines', 'id'=>'recomendacion']) !!}
        </div>
    </div>
    <div class="col-xs-4">
        <div class="form-group" >
            {!! Form::label('criticidad'.$i, 'Criticidad:') !!}
            {!! Form::select('criticidad_'.$i,[null=>'Seleccione'] +$criticidad, null, array('id'=> 'id_proceso_auditado' , 'class'=>'form-control') ) !!}
        </div>
    </div>
</div>
@endfor

<div class = "form-group text-right">
    <?php if ((isset($modal)) && ($modal == "sim")) {
        ?><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button><?php
    } else {
        ?><a href="{{ route('proceso_auditado.edit', $hallazgo->id_proceso_auditado)  }}" class="btn btn-primary">Volver</a><?php
    }

    if ((!isset($show_view)) or ( isset($show_view) && !$show_view)) {
        ?>
        {!!Form::submit('Guardar', ['class' => 'btn btn-success'])!!}
        <?php
    }
    ?>
</div>

{!!Form::close()!!}
@include('hallazgo.js')

@include('layouts.boxbottom')
@endsection