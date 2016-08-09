@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

<?php
$show_view = true;
$readonly = "css class";
$action = "show";
?>
@include('servicio_salud.form_open_edit')
@include('servicio_salud.form')

@include('layouts.boxbottom')
@endsection