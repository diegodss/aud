@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('equipo_auditor.form_open_edit')
@include('equipo_auditor.form')

@include('layouts.boxbottom')
@endsection