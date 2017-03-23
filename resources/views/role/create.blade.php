@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('role.form_open_create')
@include('role.form')

@include('layouts.boxbottom')
@endsection