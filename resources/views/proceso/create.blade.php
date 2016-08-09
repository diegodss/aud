@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('proceso.form_open_create')
@include('proceso.form')

@include('layouts.boxbottom')
@endsection