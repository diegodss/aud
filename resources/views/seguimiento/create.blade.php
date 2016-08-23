@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('seguimiento.form_open_create')
@include('seguimiento.form')

@include('layouts.boxbottom')
@endsection