<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class AreaProcesoAuditado extends Model {

    //
    protected $table = "area_proceso_auditado";
    protected $primaryKey = "id_area_proceso_auditado";
    protected $fillable = [
        "id_proceso_auditado"
        , "id_tabla"
        , "tabla"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_area_proceso_auditado', 'ilike', '%' . $value . '%')
                        ->orWhere('nombre_jefatura_area_proceso_auditado', 'ilike', '%' . $value . '%')
        ;
    }

    public function procesoAuditado() {
        return $this->belongsTo('App\ProcesoAuditado', 'id_proceso_auditado');
    }

}
