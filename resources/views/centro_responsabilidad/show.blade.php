@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

<?php
$show_view = true;
$readonly = "css class";
$action = "show";
?>
@include('centro_responsabilidad.form_open_edit')
@include('centro_responsabilidad.form')

@include('layouts.boxbottom')
@endsection