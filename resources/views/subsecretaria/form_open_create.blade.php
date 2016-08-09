{!! Form::open(['url' => 'subsecretaria', 'name' => 'subsecretariaForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
