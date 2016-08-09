@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

<?php
$show_view = true;
$readonly = "css class";
$action = "show";
?>
@include('menu.form_open_edit')
@include('menu.form')

@include('layouts.boxbottom')
@endsection