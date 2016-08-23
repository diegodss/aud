@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('compromiso.form_open_create')
@include('compromiso.form')

@include('layouts.boxbottom')
@endsection