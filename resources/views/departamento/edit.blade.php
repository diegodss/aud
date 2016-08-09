@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('departamento.form_open_edit')
@include('departamento.form')

@include('layouts.boxbottom')
@endsection