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

    /*
      public function seguimiento() {
      return $this->hasMany('App\seguimiento', 'id_compromiso', 'id_compromiso');
      }
     */

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

}
