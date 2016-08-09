@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('establecimiento.form_open_edit')
@include('establecimiento.form')

@include('layouts.boxbottom')
@endsection