@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('proceso_auditado.cabecera')

@include('layouts.boxtop')
@include('alerts.success')

@include('seguimiento.form_open_edit')
@include('seguimiento.form')

@include('layouts.boxbottom')
@endsection