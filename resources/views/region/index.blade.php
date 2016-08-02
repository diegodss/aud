@extends('layouts.app')

@section('content')
<h1>Regiones </h1>

{!!$btnActualizar!!}

@can('userAction', 'region-create')
	(adicionar)
@endcan	

@endsection
