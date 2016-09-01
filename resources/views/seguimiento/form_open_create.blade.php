{!! Form::open(['url' => 'seguimiento', 'name' => 'seguimientoForm','files'=>true]) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
