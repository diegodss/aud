@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')

{!! Form::open(['url' => 'proceso_auditado/create', 'name' => 'proceso_auditadoForm', 'id' => 'proceso_auditadoForm']) !!}
{!! Form::hidden('area_proceso_auditado',$area_proceso_auditado,['class'=>'form-control', 'id'=>'area_proceso_auditado' ]) !!}
{!! Form::hidden('_method','GET') !!}

<div class="row">
    <div class="col-xs-3">
    </div>
    <div class="col-xs-6">
        <p class="lead">Confirmar filtro de unidades</p>
        <div class="table-responsive">
            <table class="table">
                <tbody><tr>
                        <th style="width:50%">Has elegido auditar un: </th>
                        <td bgcolor="#f9f9f9">{{ $tipo }}</td>
                    </tr>
                    <tr>
                        <th>{{ $tipo }} Elegido:</th>
                        <td bgcolor="#f9f9f9">{{ $proceso_auditado_unidad }}</td>
                    </tr>
                </tbody></table>
        </div>
        <br /><br/>
        <div class="form-group text-center">
            <a href="{{ URL::previous() }}" class="btn btn-primary">Volver</a>
            {!! Form::submit('Iniciar proceso auditado', ['class' => 'btn btn-success']) !!}
        </div>
    </div>
    <div class="col-xs-3">
    </div>
</div>
{!! Form::close() !!}

@include('layouts.boxbottom')
@endsection