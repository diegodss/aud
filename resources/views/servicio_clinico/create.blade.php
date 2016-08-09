@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('servicio_clinico.form_open_create')
@include('servicio_clinico.form')

@include('layouts.boxbottom')
@endsection