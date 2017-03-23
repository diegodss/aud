@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('usuario.form_open_create')
@include('usuario.form')

@include('layouts.boxbottom')
@endsection