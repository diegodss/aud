@extends('layouts.app')
@yield('main-content')
@section('main-content')
@include('layouts.boxtop')
@include('alerts.success')


<h3>Agregar nuevo proceso</h3>
<a href="http://localhost/auditoria/public/proceso_auditado/busqueda/filtro"> Filtro de seleccion de unidad</a>


<br>
<br>
<br>
<br>

@include('layouts.boxbottom')
@endsection