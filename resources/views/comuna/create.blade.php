@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('comuna.form_open_create')
@include('comuna.form')

@include('layouts.boxbottom')
@endsection