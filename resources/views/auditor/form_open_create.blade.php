{!! Form::open(['url' => 'auditor', 'name' => 'auditorForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
