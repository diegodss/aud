@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

@include('equipo_auditor.form_open_create')
@include('equipo_auditor.form')

@include('layouts.boxbottom')
@endsection