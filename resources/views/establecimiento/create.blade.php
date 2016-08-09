@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('establecimiento.form_open_create')
@include('establecimiento.form')

@include('layouts.boxbottom')
@endsection