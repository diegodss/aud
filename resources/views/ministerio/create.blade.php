@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('ministerio.form_open_create')
@include('ministerio.form')

@include('layouts.boxbottom')
@endsection