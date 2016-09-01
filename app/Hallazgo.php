<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Hallazgo extends Model {

    //
    protected $table = "hallazgo";
    protected $primaryKey = "id_hallazgo";
    protected $fillable = [
        "nombre_hallazgo"
        , "id_proceso_auditado"
        , "recomendacion"
        , "criticidad"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_hallazgo', 'ilike', '%' . $value . '%')
                        ->orWhere('recomendacion', 'ilike', '%' . $value . '%')
        ;
    }

    public function proceso_auditado() {
        return $this->belongsTo('App\ProcesoAuditado', 'id_proceso_auditado');
    }

    public static function getByIdProcesoAuditado($id_proceso_auditado) {
        $db = DB::table('hallazgo')
                ->where('id_proceso_auditado', $id_proceso_auditado);
        return $db;
    }

    public function scopeProcesoAuditado($query) {
        return $query->join('proceso_auditado AS pa', 'pa.id_proceso_auditado', '=', 'hallazgo.id_proceso_auditado');
    }

}
