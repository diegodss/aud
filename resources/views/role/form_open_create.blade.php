{!! Form::open(['url' => 'role', 'name' => 'roleForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
