@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('servicio_salud.form_open_create')
@include('servicio_salud.form')

@include('layouts.boxbottom')
@endsection