{!! Form::open(['url' => 'region', 'name' => 'regionForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
