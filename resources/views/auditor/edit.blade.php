@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')
@include('alerts.success')

@include('auditor.form_open_edit')
@include('auditor.form')

@include('layouts.boxbottom')
@endsection