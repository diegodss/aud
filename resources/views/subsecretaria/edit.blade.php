@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('subsecretaria.form_open_edit')
@include('subsecretaria.form')

@include('layouts.boxbottom')
@endsection