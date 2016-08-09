@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('subsecretaria.form_open_create')
@include('subsecretaria.form')

@include('layouts.boxbottom')
@endsection