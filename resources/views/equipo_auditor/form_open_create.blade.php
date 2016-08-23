{!! Form::open(['url' => 'equipo_auditor', 'name' => 'equipo_auditorForm']) !!}
{{ Form::hidden('usuario_registra', Auth::user()->id) }}
{{ Form::hidden('action', 'create') }}
