{!! Form::open(['url' => 'menu', 'name' => 'menuForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
