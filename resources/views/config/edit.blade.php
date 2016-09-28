@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('config.form_open_edit')
@include('config.form')

@include('layouts.boxbottom')
@endsection