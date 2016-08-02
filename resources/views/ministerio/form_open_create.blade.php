{!! Form::open(['url' => 'ministerio', 'name' => 'ministerioForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
