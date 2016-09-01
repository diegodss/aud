<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class PlanillaSeguimiento extends Model {

    //
    protected $table = "vw_planilla_seguimiento";
    protected $primaryKey = "id_proceso_auditado";

    public function scopeActive($query) {
        return $query->where('fl_status', 1);
    }

    public function scopeFreesearch($query, $value) {
        return $query->where('nomenclatura', 'ilike', '%' . $value . '%')
                        ->orWhere('ano', '=', $value)
                        ->orWhere('area_auditada', 'ilike', '%' . $value . '%');
    }

    public function scopeBusqueda($query, $value, $campoReporte = null) {
        if ($campoReporte != null) {
            $query->selectRaw($campoReporte . ', count(*) as total');
        }
        $i = 0;
        if (count($value) > 0) {
            foreach ($value as $keyBusqueda => $valueBusqueda) {
                if ($keyBusqueda == 'plazo_comprometido') {

                    $dt = explode("|", $valueBusqueda);
                    $plazo_comprometido_inicio = $dt[0];
                    $plazo_comprometido_fin = $dt[1];

                    $query->where('plazo_comprometido', '=', $plazo_comprometido_inicio);
                    //$query->where('plazo_comprometido', '=', $plazo_comprometido_fin);
                }

                if ($i == 0) {
                    $query->where($keyBusqueda, '=', $valueBusqueda);
                } else {
                    $query->orWhere($keyBusqueda, '=', $valueBusqueda);
                }
                $i++;
            }
        }
        if ($campoReporte != null) {
            return $query->groupBy($campoReporte)->get();
        } else {
            return $query->paginate(40);
        }
        //  Log::error($value);
    }

    public static function getTableColumns() {
        $db = DB::table('information_schema.columns')
                ->select('column_name')
                ->where('table_name', 'vw_planilla_seguimiento')
                ->get();
        return $db;
    }

}
