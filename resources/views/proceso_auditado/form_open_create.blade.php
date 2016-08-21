{!! Form::open(['url' => 'proceso_auditado', 'name' => 'proceso_auditadoForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
