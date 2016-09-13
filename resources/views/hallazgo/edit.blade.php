@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('hallazgo.form_open_edit')
@include('hallazgo.form')

@include('layouts.boxbottom')

@if ($proceso_auditado->fl_status === true)
@include('hallazgo.compromiso')
@endif

@endsection