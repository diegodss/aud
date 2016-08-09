{!! Form::open(['url' => 'proceso', 'name' => 'procesoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
