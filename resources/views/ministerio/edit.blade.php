@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('ministerio.form_open_edit')
@include('ministerio.form')

@include('layouts.boxbottom')
@endsection