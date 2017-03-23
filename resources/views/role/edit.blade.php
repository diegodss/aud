@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('role.form_open_edit')
@include('role.form')

@include('layouts.boxbottom')
@endsection