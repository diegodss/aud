@extends('layouts.app_modal')
@yield('main-content')
@section('main-content')

@include('layouts.boxtop')

{!! $compromiso_vencido !!}

<div class = "form-group text-right"><button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
</div>

@include('layouts.boxbottom')
@endsection