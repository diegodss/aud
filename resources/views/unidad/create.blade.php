@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('unidad.form_open_create')
@include('unidad.form')

@include('layouts.boxbottom')
@endsection