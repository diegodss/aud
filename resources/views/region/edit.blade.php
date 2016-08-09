@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('region.form_open_edit')
@include('region.form')

@include('layouts.boxbottom')
@endsection