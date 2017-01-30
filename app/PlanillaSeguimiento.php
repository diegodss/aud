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

    public function scopeBusqueda($query, $value, $campoReporte = null, $paginate = true) {
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

                    $query->whereRaw("to_date(\"plazo_comprometido\" , 'DD/MM/YYYY') >= to_date('" . $plazo_comprometido_inicio . "' , 'DD/MM/YYYY')  ");
                    $query->whereRaw("to_date(\"plazo_comprometido\" , 'DD/MM/YYYY') <= to_date('" . $plazo_comprometido_fin . "' , 'DD/MM/YYYY')  ");
                    //$query->where('plazo_comprometido', ' = ', );
                    //$query->where('plazo_comprometido', ' = ', $plazo_comprometido_fin);
                } else {

                    if ($i == 0) {
                        $query->where($keyBusqueda, $valueBusqueda);
                    } else {
                        $query->Where($keyBusqueda, $valueBusqueda);
                    }
                }
                $i++;
            }
        }

        if ($campoReporte != null) {
            return $query->groupBy($campoReporte)->get();
        } else {
            $query->orderBy('numero_informe', 'fecha');
            if ($paginate) {
                return $query->paginate(40);
            } else {
                return $query->get();
            }
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

    public static function createView($sub, $ano) {
        $cards = DB::select("
        CREATE OR REPLACE VIEW vw_planilla_seguimiento_report AS (

	SELECT * FROM vw_planilla_seguimiento
	WHERE
	estado in (SELECT estado FROM estado )
	and condicion in (SELECT condicion FROM condicion)
	and (nomenclatura = 'PMG' OR nomenclatura = 'NO PMG')
	and (subsecretaria = '" . $sub . "') -- DINAMICO
	and (ano = '" . $ano . "')	-- DINAMICO
        );");
        return true;
    }

    public static function cuadro1() {
        $cuadro1 = DB::select("select
        estado.estado
        -- ---------------------------- PMG ------------------------------
        , (select count(*) from vw_planilla_seguimiento_report where estado = estado.estado and nomenclatura = 'PMG') as tot_pmg
        , 	 ROUND(
                100.0 *
                (select count(*) from vw_planilla_seguimiento_report where estado = estado.estado and nomenclatura = 'PMG') /
                sum((select count(*) from vw_planilla_seguimiento_report where estado = estado.estado)) over ()
                ) as perc_PMG

        -- ---------------------------- NO PMG ------------------------------
        , (select count(*) from vw_planilla_seguimiento_report where estado = estado.estado and nomenclatura = 'NO PMG') as tot_no_pmg

        , 	 ROUND(
                100.0 *
                (select count(*) from vw_planilla_seguimiento_report where estado = estado.estado and nomenclatura = 'NO PMG') /
                sum((select count(*) from vw_planilla_seguimiento_report where estado = estado.estado)) over ()
                ) as perc_NO_PMG

        -- ---------------------------- TOTAL ------------------------------
        , (select count(*) from vw_planilla_seguimiento_report where estado = estado.estado ) as tot
        , 	 ROUND(
                100.0 *
                (select count(*) from vw_planilla_seguimiento_report where estado = estado.estado ) /
                (select count(*) from vw_planilla_seguimiento_report )
                ) as perc

        from
        estado   ;");
    }

    public static function cuadro2($nomenclatura) {

        $nomenclatura_db = str_replace("_", " ", $nomenclatura);
        $cuadro2 = DB::select("select
        condicion.condicion
        , (select count(*) from vw_planilla_seguimiento_report where condicion = condicion.condicion and nomenclatura = '" . $nomenclatura_db . "') as tot_" . $nomenclatura . "
        , 	 ROUND(
                100.0 *
                (select count(*) from vw_planilla_seguimiento_report where condicion = condicion.condicion and nomenclatura = '" . $nomenclatura_db . "') /
                (select count(*) from vw_planilla_seguimiento_report where  nomenclatura = '" . $nomenclatura_db . "')
                ) as perc_" . $nomenclatura . "

        from
        condicion ");
        return $cuadro2;
    }

    public static function cuadro3($estado, $nomenclatura) {

        $nomenclatura_db = str_replace("_", " ", $nomenclatura);
        $cuadro3 = DB::select("select
        estado.estado
        , (select count(*) from vw_planilla_seguimiento where porcentaje_avance::float >= 1 and porcentaje_avance::float <= 50 and nomenclatura = '" . $nomenclatura_db . "') as de_1_a_50
        , (select count(*) from vw_planilla_seguimiento where porcentaje_avance::float > 50 and porcentaje_avance::float <= 75 and nomenclatura = '" . $nomenclatura_db . "') as de_51_a_75
        , (select count(*) from vw_planilla_seguimiento where porcentaje_avance::float > 75 and porcentaje_avance::float <= 99 and nomenclatura = '" . $nomenclatura_db . "') as de_76_a_a99
        from
        estado
        where estado.estado = '" . $estado . "' ");
        return $cuadro3;
    }

}
