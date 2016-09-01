<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Seguimiento extends Model {

    //
    protected $table = "seguimiento";
    protected $primaryKey = "id_seguimiento";
    protected $fillable = [
        "diferencia_tiempo"
        , "id_compromiso"
        , "porcentaje_avance"
        , "estado"
        , "condicion"
        , "razon_no_cumplimiento"
        , "fl_status"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('diferencia_tiempo', 'ilike', '%' . $value . '%')
                        ->orWhere('estado', 'ilike', '%' . $value . '%')
        ;
    }

    public function compromiso() {
        return $this->belongsTo('App\Compromiso', 'id_compromiso');
    }

    public static function getByIdCompromiso($id_compromiso) {
        $db = DB::table('seguimiento')
                ->where('id_compromiso', $id_compromiso);
        return $db;
    }

    public static function getActualByIdCompromiso($id_compromiso) {
        $db = DB::table('seguimiento')
                        ->where('id_compromiso', $id_compromiso)
                        ->orderBy('created_at', 'desc')->first();
        return $db;
    }

    public function scopeCompromisoHallazgoProcesoAuditado($query) {
        return $query->select('id_seguimiento', 'numero_informe', 'numero_informe_unidad', 'nombre_proceso_auditado', 'nombre_hallazgo', 'nombre_compromiso', 'seguimiento.estado', 'condicion', 'porcentaje_avance', 'plazo_comprometido')
                        ->join('compromiso AS c', 'seguimiento.id_compromiso', ' = ', 'c.id_compromiso')
                        ->join('hallazgo AS h', 'c.id_hallazgo', ' = ', 'h.id_hallazgo')
                        ->join('proceso_auditado AS pa', 'pa.id_proceso_auditado', ' = ', 'h.id_proceso_auditado');
    }

}
