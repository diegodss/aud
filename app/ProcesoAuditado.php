<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class ProcesoAuditado extends Model {

    //
    protected $table = "proceso_auditado";
    protected $primaryKey = "id_proceso_auditado";
    protected $fillable = [
        "id_proceso"
        , "fecha"
        , "nomenclatura"
        , "observaciones"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
        , "ano"
        , "numero_informe"
        , "numero_informe_unidad"
        , "objetivo_auditoria"
        , "actividad_auditoria"
        , "tipo_auditoria"
        , "codigo_caigg"
        , "tipo_informe"
        , "nombre_proceso_auditado"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_proceso', 'ilike', '%' . $value . '%')
                        ->orWhere('responsable_proceso', 'ilike', '%' . $value . '%')
        ;
    }

}
