@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('unidad.form_open_edit')
@include('unidad.form')

@include('layouts.boxbottom')
@endsection