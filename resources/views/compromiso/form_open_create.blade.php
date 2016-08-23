{!! Form::open(['url' => 'compromiso', 'name' => 'compromisoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
