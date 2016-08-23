{!! Form::open(['url' => 'hallazgo', 'name' => 'hallazgoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
