@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('region.form_open_create')
@include('region.form')

@include('layouts.boxbottom')
@endsection