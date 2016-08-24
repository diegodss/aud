@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('compromiso.form_open_edit')
@include('compromiso.form')

@include('layouts.boxbottom')

@include('compromiso.seguimiento')
@include('compromiso.medioverificacion')

@endsection