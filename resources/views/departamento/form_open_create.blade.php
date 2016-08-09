{!! Form::open(['url' => 'departamento', 'name' => 'departamentoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
