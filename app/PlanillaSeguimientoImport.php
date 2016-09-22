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

    public function scopeGetProcesoAuditado($query) {
        return $query->select('proceso', 'fecha_informe', 'ano', 'n_informe', 'division', 'area_auditada')
                        ->groupBy('proceso', 'fecha_informe', 'ano', 'n_informe', 'division', 'area_auditada');
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
        if (count($value) > 0) {
            foreach ($value as $keyBusqueda => $valueBusqueda) {

//                print_r($keyBusqueda . ' = ' . $valueBusqueda);
                //               print_r(' <br>');
                if ($i == 0) {
                    $query->where($keyBusqueda, $valueBusqueda);
                } else {
                    $query->Where($keyBusqueda, $valueBusqueda);
                }

                //            print_r(' <br>');
                //          print_r(' <br>');
                $i++;
            }
        }

        return $query->get();
        if ($campoReporte != null) {
            return $query->groupBy($campoReporte)->get();
        } else {
            return $query->orderBy('numero_informe', 'fecha');
        }
        //  Log::error($value);
    }

    public function scopeActive($query) {
        return $query->groupBy('fecha_informe', 'ano', 'nomenclatura', 'n_informe');
    }

}
