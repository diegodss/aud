{!! Form::open(['url' => 'servicio_clinico', 'name' => 'servicio_clinicoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
