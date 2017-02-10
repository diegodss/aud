<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class Compromiso extends Model {

//
    protected $table = "compromiso";
    protected $primaryKey = "id_compromiso";
    protected $fillable = [
        "id_hallazgo"
        , "nombre_compromiso"
        , "plazo_comprometido"
        , "plazo_estimado"
        , "nomenclatura"
        , "responsable"
        , "fono_responsable"
        , "email_responsable"
        , "fl_status"
        , "id_compromiso_padre"
        , "usuario_registra"
        , "usuario_modifica"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeGetIdByCorrelativoInterno($query, $value) {
        return $query->where('correlativo_interno', $value);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_compromiso', 'ilike', '%' . $value . '%')
                        ->orWhere('responsable', 'ilike', '%' . $value . '%')
        ;
    }

    public function seguimiento() {
        return $this->hasMany('App\Seguimiento', 'id_compromiso', 'id_compromiso');
    }

    public function hallazgo() {
//return $this->belongsTo('App\seguimiento', 'id_compromiso', 'id_compromiso');
        return $this->hasOne('App\Hallazgo', 'id_hallazgo');
    }

    public static function getByIdHallazgo($id_hallazgo) {
        $db = DB::table('compromiso')
                ->where('id_hallazgo', $id_hallazgo);
        return $db;
    }

    public function scopeHallazgoProcesoAuditado($query) {
        return $query->join('hallazgo AS h', 'compromiso.id_hallazgo', '=', 'h.id_hallazgo')
                        ->join('proceso_auditado AS pa', 'pa.id_proceso_auditado', '=', 'h.id_proceso_auditado');
    }

    public static function aa() {

        $db = DB::table('compromiso AS c')
                ->join('hallazgo AS h', 'c.id_hallazgo', '=', 'h.id_hallazgo')
                ->join('proceso_auditado AS pa', 'pa.id_proceso_auditado', '=', 'h.id_proceso_auditado')
                ->get();
        ;
        return $db;
    }

    public static function getIdProcesoAuditado($id_compromiso) {
        $db = DB::table('proceso_auditado AS pa')
                ->select('pa.id_proceso_auditado')
                ->join('hallazgo AS h', 'pa.id_proceso_auditado', ' = ', 'h.id_proceso_auditado')
                ->join('compromiso AS c', 'c.id_hallazgo', ' = ', 'h.id_hallazgo')
                ->where('id_compromiso', $id_compromiso)
                ->first();
        return $db->id_proceso_auditado;
    }

    public static function compromiso_vencido($intervalo_inicio, $intervalo_fin) {

        if ((int) $intervalo_inicio == 0) {
            $fecha_inicio = " now()::date ";
        } else {
            $fecha_inicio = " (now()::date - interval '" . $intervalo_inicio . "' day)::date ";
        }

        $fecha_fin = " (now()::date - interval '" . $intervalo_fin . "' day)::date ";

        $query = PlanillaSeguimiento::select(
                        'id'
                        , 'numero_informe'
                        , 'hallazgo'
                        , 'compromiso'
                        , 'plazo_comprometido'
                        , 'condicion'
                        , 'porcentaje_avance'
                        , 'fecha'
                        , 'division'
                )
                ->where('estado', 'Vencido')
                ->whereRaw("( to_date(plazo_comprometido, 'DD/MM/YYYY'::text) BETWEEN " . $fecha_fin . " AND " . $fecha_inicio . " ) ")
        ;
        return $query;
    }

    public static function responsable($input) {

        $query = Compromiso::select(
                        'responsable AS value', 'fono_responsable', 'email_responsable'
                )->groupBy('responsable', 'fono_responsable', 'email_responsable')
                ->where('responsable', 'ilike', '%' . $input . '%');
        return $query;
    }

}
