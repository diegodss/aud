{!! Form::open(['url' => 'usuario', 'name' => 'usuarioForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
