@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('proceso.form_open_edit')
@include('proceso.form')

@include('layouts.boxbottom')
@endsection