{!! Form::open(['url' => 'centro_responsabilidad', 'name' => 'centro_responsabilidadForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
