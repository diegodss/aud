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
        , "cantidad_hallazgo"
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

    public static function getSubsecretaria($id_proceso_auditado) {
        $db = DB::table('area_proceso_auditado')
                ->select('descripcion')
                ->where('id_proceso_auditado', $id_proceso_auditado)
                ->where('tabla', 'subsecretaria')
                ->orderBy('id_area_proceso_auditado', 'DESC')
                ->first();
        return $db->descripcion;
    }

    public function auditor() {
        return $this->belongsToMany('App\Auditor', 'rel_proceso_auditor', 'id_proceso_auditado', 'id_auditor')->withPivot('jefatura_equipo');
        //return $this->belongsToMany('App\Auditor', 'rel_auditor_equipo');
    }

    public static function validaNumeroInforme($numero_informe, $numero_informe_unidad, $ano, $fecha) {

        /*
         *
         * No se puede existir un mismo informe con mismo numero de informe y año.
         * Pero si la fecha ingresada ya existe, tenemos que permitir el ingreso, ya que puede ser el mismo
         * informe, con otra division, o area auditada.
         * O sea, si hay por lo menos 1 informe con mismo numero y fecha, el sistema debe entender como el mismo informe
         * Si el sistema no encuentra la fecha ingresada, rechazara el ingreso de los datos.
         *
         *
         */


        $query = ProcesoAuditado::where('numero_informe', $numero_informe)
                ->where('numero_informe_unidad', $numero_informe_unidad)
                ->where('ano', $ano);
        //Log::info($query->count());

        $datos = $query->get();
        $fecha_existente = false;
        foreach ($datos as $dt) {
            if ($fecha == $dt->fecha) {
                $fecha_existente = true;
                break;
            }
        }

        return $fecha_existente; //$query->count();
    }

    public static function ProcesoAuditadoAuditor() {

        /*
          $query = ProcesoAuditado::select('proceso_auditado.*', 'a.nombre_auditor', 'a.id_auditor')
          ->join('rel_proceso_auditor as rpa', 'rpa.id_proceso_auditado', '=', 'proceso_auditado.id_proceso_auditado')
          ->join('auditor as a', 'a.id_auditor', '=', 'rpa.id_auditor')
          ->orderby('proceso_auditado.numero_informe', 'ASC')
          ->orderby('proceso_auditado.numero_informe_unidad', 'ASC')
          ->orderby('proceso_auditado.ano', 'ASC');


          $sql = "select *,
          (Select string_agg(nombre_auditor, ', ')  from auditor a
          inner join rel_proceso_auditor as rpa  on a.id_auditor = rpa.id_auditor
          where rpa.id_proceso_auditado = pa.id_proceso_auditado ) as nombre_auditor
          from proceso_auditado pa
          order by numero_informe ASC, numero_informe_unidad ASC, ano ASC";

          $query = DB::raw($sql);
         *  */


        $subquery = "(Select string_agg(nombre_auditor, ', \n')  from auditor a
        inner join rel_proceso_auditor as rpa  on a.id_auditor = rpa.id_auditor
        where rpa.id_proceso_auditado = proceso_auditado.id_proceso_auditado ) as nombre_auditor";

        $query = ProcesoAuditado::select('proceso_auditado.*', DB::raw($subquery))
                ->orderby('proceso_auditado.numero_informe', 'ASC')
                ->orderby('proceso_auditado.numero_informe_unidad', 'ASC')
                ->orderby('proceso_auditado.ano', 'ASC');



        return $query;
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
