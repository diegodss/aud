@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('proceso_auditado.form_open_edit')
@include('proceso_auditado.form')

@include('layouts.boxbottom')

@include('proceso_auditado.hallazgo')

@endsection