@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('comuna.form_open_edit')
@include('comuna.form')

@include('layouts.boxbottom')
@endsection