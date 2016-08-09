{!! Form::open(['url' => 'servicio_salud', 'name' => 'servicio_saludForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
