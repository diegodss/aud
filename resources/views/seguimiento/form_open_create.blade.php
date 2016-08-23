{!! Form::open(['url' => 'seguimiento', 'name' => 'seguimientoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
