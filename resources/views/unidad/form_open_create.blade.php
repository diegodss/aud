{!! Form::open(['url' => 'unidad', 'name' => 'unidadForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
