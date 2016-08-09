@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('auditor.form_open_create')
@include('auditor.form')

@include('layouts.boxbottom')
@endsection