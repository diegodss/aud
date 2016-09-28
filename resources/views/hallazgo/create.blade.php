@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('proceso_auditado.cabecera')

@include('layouts.boxtop')

@include('hallazgo.form_open_create')
@include('hallazgo.form')

@include('layouts.boxbottom')
@endsection