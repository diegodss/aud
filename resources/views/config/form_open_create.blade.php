{!! Form::open(['url' => 'config', 'name' => 'configForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
