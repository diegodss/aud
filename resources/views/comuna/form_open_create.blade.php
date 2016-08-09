{!! Form::open(['url' => 'comuna', 'name' => 'comunaForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
