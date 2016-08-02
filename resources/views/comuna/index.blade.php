@extends('layouts.app')

@section('content')
<h1>Comunass </h1>
{!!$btnActualizar!!}

@can('userAction', 'comuna-create')
	(adicionar)
@endcan	

@endsection
