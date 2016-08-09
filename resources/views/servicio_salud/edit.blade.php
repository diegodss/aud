@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('servicio_salud.form_open_edit')
@include('servicio_salud.form')

@include('layouts.boxbottom')
@endsection