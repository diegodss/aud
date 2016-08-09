@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('centro_responsabilidad.form_open_edit')
@include('centro_responsabilidad.form')

@include('layouts.boxbottom')
@endsection