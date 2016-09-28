@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('proceso_auditado.cabecera')

@include('layouts.boxtop')

<?php
$show_view = true;
$readonly = "css class";
$action = "show";
?>
@include('hallazgo.form_open_edit')
@include('hallazgo.form')

@include('layouts.boxbottom')
@endsection