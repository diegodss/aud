@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('menu.form_open_create')
@include('menu.form')

@include('layouts.boxbottom')
@endsection