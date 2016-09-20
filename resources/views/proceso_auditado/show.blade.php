@extends('layouts.app')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

<?php
$show_view = true;
$readonly = "css class";
$action = "show";
?>
@include('proceso_auditado.form_open_edit')
@include('proceso_auditado.form')

@include('layouts.boxbottom')
@include('proceso_auditado.hallazgo')
@endsection