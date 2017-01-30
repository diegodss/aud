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
        // , "nomenclatura"
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
        , "cuantidad_hallazgo"
    ];

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nombre_proceso', 'ilike', '%' . $value . '%')
                        ->orWhere('responsable_proceso', 'ilike', '%' . $value . '%')
        ;
    }

    public function scopeTest($query) {
        return $query->where('fl_status', true);
    }

    public static function getByCorrelativoInterno($id_proceso_auditado) {
        $db = ProcesoAuditado::select('proceso_auditado.id_proceso_auditado', 'nomenclatura')
                ->join('hallazgo as h', 'h.id_proceso_auditado', '=', 'proceso_auditado.id_proceso_auditado')
                ->join('compromiso as c', 'c.id_hallazgo', '=', 'h.id_hallazgo')
                ->where('c.id_compromiso', $id_proceso_auditado) // id_compromiso es el nuevo correlativo_interno
                ->first();
        return $db;
    }

    public static function getAreaAuditada($id_proceso_auditado) {
        $db = DB::table('area_proceso_auditado')
                ->select('descripcion')
                ->where('id_proceso_auditado', $id_proceso_auditado)
                ->orderBy('id_area_proceso_auditado', 'DESC')
                ->first();
        return $db->descripcion;
    }

    public static function getDivision($id_proceso_auditado) {
        $db = DB::table('area_proceso_auditado')
                ->select('descripcion')
                ->where('id_proceso_auditado', $id_proceso_auditado)
                ->where('tabla', 'division')
                ->orderBy('id_area_proceso_auditado', 'DESC')
                ->first();
        return $db->descripcion;
    }

    public function auditor() {
        return $this->belongsToMany('App\Auditor', 'rel_proceso_auditor', 'id_proceso_auditado', 'id_auditor')->withPivot('jefatura_equipo');
        //return $this->belongsToMany('App\Auditor', 'rel_auditor_equipo');
    }

    /*
      public function test($query) {
      return $query->where('fl_status', 1);
      }
     */

    public static function getAuditorById($id_proceso_auditado) {
        $db = DB::table('proceso_auditado AS pa')
                ->join('rel_proceso_auditor AS rpa', 'rpa.id_proceso_auditado', '=', 'pa.id_proceso_auditado')
                ->join('auditor AS a', 'a.id_auditor', '=', 'rpa.id_auditor')
                ->select('a.id_auditor', 'a.nombre_auditor', 'rpa.jefatura_equipo')
                ->where('pa.id_proceso_auditado', $id_proceso_auditado);
        return $db;
    }

    public static function area_auditada() {

        $db = DB::table('vw_proceso_auditado');

        /*
          $db = DB::table('proceso_auditado AS pa')
          ->join('area_proceso_auditado AS apa', function($join) {
          $join->on('apa.id_proceso_auditado', '=', 'pa.id_proceso_auditado');
          $join->on('apa.tabla', '=', DB::raw("'division'"));
          }); */
        //->where('pa.id_proceso_auditado', $id_proceso_auditado);
        return $db;
    }

}
