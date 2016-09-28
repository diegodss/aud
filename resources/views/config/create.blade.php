@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('config.form_open_create')
@include('config.form')

@include('layouts.boxbottom')
@endsection