<div class="box">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6" class="box">
                <div class="form-group" >
                    {!! Form::label('id_proceso_auditado', 'Proceso:') !!}
                    {!! Form::hidden('id_proceso_auditado',$proceso_auditado->id_proceso_auditado ) !!}
                    {!! Form::text('nombre_proceso_auditado',$proceso_auditado->nombre_proceso_auditado, ['class'=>'form-control', 'disabled'=>'disabled'] ) !!}
                    <a href="{{ route('proceso_auditado.show', $proceso_auditado->id_proceso_auditado)  }}" class="btn-quick-add">
                        ver proceso
                    </a>
                </div>
            </div>
            <div class="col-xs-6" class="box">
                <div class="form-group required">
                    {!! Form::label('numero_informe', 'Numero de Informe:' , ['class'=>'']) !!}
                    {!! Form::text('numero_informe', $proceso_auditado->numero_informe_unidad . " Nº" . $proceso_auditado->numero_informe,['id'=>'numero_informe', 'class'=>'form-control', 'disabled'=>'disabled']) !!}
                </div>
            </div>
        </div>
    </div>
</div>