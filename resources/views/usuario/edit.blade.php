@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('usuario.form_open_edit')
@include('usuario.form')

@include('layouts.boxbottom')
@endsection