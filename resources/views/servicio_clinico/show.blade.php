@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

<?php
$show_view = true;
$readonly = "css class";
$action = "show";
?>
@include('servicio_clinico.form_open_edit')
@include('servicio_clinico.form')

@include('layouts.boxbottom')
@endsection