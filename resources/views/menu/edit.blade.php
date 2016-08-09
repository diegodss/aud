@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('menu.form_open_edit')
@include('menu.form')

@include('layouts.boxbottom')
@endsection