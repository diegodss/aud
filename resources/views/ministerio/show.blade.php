@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

<?php
$show_view = true;
$readonly = "css class";
$action = "show";
?>
@include('ministerio.form_open_edit')
@include('ministerio.form')

@include('layouts.boxbottom')
@endsection