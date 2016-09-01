@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')
<div class="row">
    <div class="col-xs-12">

        <div class="pull-right">
            {!! $filter->open !!}
            <div class="input-group custom-search-form">
                <div id="fg_numero_informe">{!! $filter->field('numero_informe') !!}</div>
            </div>
            <div class="input-group custom-search-form">
                <div id="fg_numero_informe_unidad">{!! $filter->field('numero_informe_unidad') !!}</div>
            </div>
            <div class="input-group custom-search-form">

                <div id="fg_ano">{!! $filter->field('ano') !!}</div>
            </div>
            <div class="input-group custom-search-form">

                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                    <a href="<?php echo e(url('/' . $controller)); ?>" class="btn btn-default">
                        <span class="glyphicon glyphicon-remove"></span>
                    </a>
                </span>
            </div>
            {!! $filter->close !!}
        </div>
        <div class="pull-right">
            @include('widget.index.items-page')
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">&nbsp; </div>
</div>
{!! $grid !!}

@include('proceso_auditado.js-index')
@include('layouts.boxbottom')
@endsection