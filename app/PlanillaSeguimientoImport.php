<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class PlanillaSeguimientoImport extends Model {

//
    protected $table = "planilla_seguimiento_import";
    protected $primaryKey = "id_planilla_seguimiento_import";
    protected $fillable = [
        "correlativo_interno"
        , "nomenclatura"
        , "ano"
        , "subsecretaria"
        , "division"
        , "area_auditada"
        , "n_informe"
        , "fecha_informe"
        , "proceso"
        , "nombre_auditor"
        , "descripcion_del_hallazgo"
        , "descripcion_recomendacion"
        , "responsable"
        , "criticidad"
        , "descripcion_compromiso"
        , "plazo_estimado"
        , "plazo_que_compromete_auditado"
        , "diferencia"
        , "avance"
        , "condicion"
        , "estado"
        , "medios_de_verificacion"
        , "observacion"
    ];

    public function scopeGetProcesoAuditado($query) { // 'subsecretaria',
        return $query->select('proceso'
                                , 'fecha_informe'
                                , 'ano'
                                , 'subsecretaria'
                                , 'n_informe'
                                , 'division'
                                , 'area_auditada'
                                , 'nombre_auditor'
                                , 'objetivo_auditoria'
                                , 'actividad_auditoria'
                                , 'codigo_caigg'
                                , 'proceso_transversal'
                                , 'tipo_informe'
                        )
                        ->groupBy('proceso'
                                , 'fecha_informe'
                                , 'ano'
                                , 'subsecretaria'
                                , 'n_informe'
                                , 'division'
                                , 'area_auditada'
                                , 'nombre_auditor'
                                , 'objetivo_auditoria'
                                , 'actividad_auditoria'
                                , 'codigo_caigg'
                                , 'proceso_transversal'
                                , 'tipo_informe');
    }

// quitando reprogramado // 'nomenclatura',

    public function scopeReprogramado($query) {
        return $query->Where('estado', 'Reprogramado');
    }

    public function scopeGetByNumeroInforme($query, $value) {
        return $query->where('n_informe', 'ilike', $value);
    }

    public function scopeBusqueda($query, $value, $campoReporte = null) {
        if ($campoReporte != null) {
            $query->selectRaw($campoReporte . ', count(*) as total');
        }
        $i = 0;
        //$query->Where("grabado", 0);
        if (count($value) > 0) {
            foreach ($value as $keyBusqueda => $valueBusqueda) {

//                print_r($keyBusqueda . ' = ' . $valueBusqueda);
                //               print_r(' <br>');
                /*
                  if ($i == 0) {
                  $query->where($keyBusqueda, $valueBusqueda);
                  } else {
                  $query->Where($keyBusqueda, $valueBusqueda);
                  }
                 */
                $query->whereRaw("lower(" . trim($keyBusqueda) . ") = lower('" . trim($valueBusqueda) . "')");
                //            print_r(' <br>');
                //          print_r(' <br>');
                $i++;
            }
        }

        return $query->get();
        if ($campoReporte != null) {
            return $query->groupBy($campoReporte)->orderBy('correlativo_interno')->get();
        } else {
            return $query->orderBy('numero_informe', 'fecha');
        }
        //  Log::error($value);
    }

    public function scopeActive($query) {
        return $query->groupBy('fecha_informe', 'ano', 'nomenclatura', 'n_informe');
    }

    public static function truncateProcesoAuditado() {

        $commands = array(
            "truncate proceso_auditado cascade;",
            "ALTER SEQUENCE area_proceso_auditado_id_area_proceso_auditado_seq RESTART 1;",
            "ALTER SEQUENCE hallazgo_id_hallazgo_seq RESTART 1;",
            "ALTER SEQUENCE proceso_auditado_nomenclatura_id_proceso_auditado_nomenclat_seq RESTART 1;",
            "ALTER SEQUENCE compromiso_id_compromiso_seq RESTART 1;",
            "ALTER SEQUENCE medio_verificacion_id_medio_verificacion_seq RESTART 1;",
            "ALTER SEQUENCE seguimiento_id_seguimiento_seq RESTART 1;",
            "ALTER SEQUENCE compromiso_nomenclatura_id_compromiso_nomenclatura_seq RESTART 1;",
            "ALTER SEQUENCE public.proceso_auditado_id_proceso_auditado_seq RESTART 1;"
        );

        foreach ($commands as $query) {
            DB::select($query);
        }
        return "OK";
    }

    public static function finalizaImportacion() {
        $query = DB::select("update compromiso set plazo_comprometido = '' where plazo_comprometido = '--'");
		Log::info($query);
        return "OK";
    }

}
