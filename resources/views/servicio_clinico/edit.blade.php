@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('servicio_clinico.form_open_edit')
@include('servicio_clinico.form')

@include('layouts.boxbottom')
@endsection