@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('departamento.form_open_create')
@include('departamento.form')

@include('layouts.boxbottom')
@endsection