<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Log;
use \stdClass;

class InformeDetallado extends Model {

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
	estado in (SELECT estado FROM collection_estado )
	and condicion in (SELECT condicion FROM collection_condicion)
	and (nomenclatura = 'PMG' OR nomenclatura = 'NO PMG')
	and (subsecretaria = '" . $sub . "') -- DINAMICO
	and (ano = '" . $ano . "')	-- DINAMICO
        );");
        return true;
    }

    public static function por_estado($todos = false) {

        $vista = "vw_planilla_seguimiento_report";
        if ($todos) {
            $vista = "vw_planilla_seguimiento";
        }

        $cuadro1 = DB::select("select
        estado.estado
        -- ---------------------------- PMG ------------------------------
        , (select count(*) from " . $vista . " where estado = estado.estado and nomenclatura = 'PMG') as tot_pmg
        , 	 ROUND(
                100.0 *
                (select count(*) from " . $vista . " where estado = estado.estado and nomenclatura = 'PMG') /
                sum((select count(*) from " . $vista . " where estado = estado.estado)) over ()
                ) as perc_PMG

        -- ---------------------------- NO PMG ------------------------------
        , (select count(*) from " . $vista . " where estado = estado.estado and nomenclatura = 'NO PMG') as tot_no_pmg

        , 	 ROUND(
                100.0 *
                (select count(*) from " . $vista . " where estado = estado.estado and nomenclatura = 'NO PMG') /
                sum((select count(*) from " . $vista . " where estado = estado.estado)) over ()
                ) as perc_NO_PMG

        -- ---------------------------- TOTAL ------------------------------
        , (select count(*) from " . $vista . " where estado = estado.estado ) as total
        , 	 ROUND(
                100.0 *
                (select count(*) from " . $vista . " where estado = estado.estado ) /
                (select count(*) from " . $vista . " )
                ) as perc

        from
        collection_estado estado;");
        return $cuadro1;
    }

    public static function por_condicion($nomenclatura, $todos = false) {

        $vista = "vw_planilla_seguimiento_report";
        if ($todos) {
            $vista = "vw_planilla_seguimiento";
        }

        $nomenclatura_db = str_replace("_", " ", $nomenclatura);
        $cuadro2 = DB::select("select
        condicion.condicion
        , (select count(*) from " . $vista . " where condicion = condicion.condicion and nomenclatura = '" . $nomenclatura_db . "') as tot_" . $nomenclatura . "
        , CASE (select count(*) from " . $vista . " where condicion = condicion.condicion and nomenclatura = '" . $nomenclatura_db . "')
        WHEN 0 THEN 0 ELSE
         	 ROUND(
                100.0 *
                (select count(*) from " . $vista . " where condicion = condicion.condicion and nomenclatura = '" . $nomenclatura_db . "') /
                (select count(*) from " . $vista . " where  nomenclatura = '" . $nomenclatura_db . "')
                )
        END as perc_" . $nomenclatura . "

        from
        collection_condicion condicion ");

        return $cuadro2;
    }

    public static function rango_por_condicion($condicion, $nomenclatura) {

        $nomenclatura_db = str_replace("_", " ", $nomenclatura);
        $cuadro3 = DB::select("select
        condicion.condicion
        , (select count(*) from vw_planilla_seguimiento where porcentaje_avance::float >= 1 and porcentaje_avance::float <= 50 and nomenclatura = '" . $nomenclatura_db . "') as de_1_a_50
        , (select count(*) from vw_planilla_seguimiento where porcentaje_avance::float > 50 and porcentaje_avance::float <= 75 and nomenclatura = '" . $nomenclatura_db . "') as de_51_a_75
        , (select count(*) from vw_planilla_seguimiento where porcentaje_avance::float > 75 and porcentaje_avance::float <= 99 and nomenclatura = '" . $nomenclatura_db . "') as de_76_a_a99
        from
        collection_condicion condicion
        where condicion.condicion = '" . $condicion . "' ");
        return $cuadro3;
    }

    public static function detalle_proceso($condicion, $nomenclatura, $subsecretaria) {

        $nomenclatura_db = str_replace("_", " ", $nomenclatura);
        $cuadro7_13 = DB::select("select
            numero_informe
            , fecha
            , proceso
            , area_auditada
            , count(*) as total_compromiso
            from vw_planilla_seguimiento
            where condicion = '" . $condicion . "'
            and (subsecretaria = '" . $subsecretaria . "')
            and (nomenclatura = '" . $nomenclatura_db . "')
            group by
            numero_informe
            , fecha
            , proceso
            , area_auditada");
        return $cuadro7_13;
    }

    public static function detalle_area_auditada($subsecretaria, $division) {

        $queryDivision = "";
        if ($division != "") {
            $queryDivision = " AND division IN ('" . $division . "') ";
        }
        $query = "SELECT
        division
        , area_auditada
        , COUNT(CASE WHEN ps.condicion = 'Cumplida' THEN 1 ELSE NULL END ) AS \"Cumplida\"
        , COUNT(CASE WHEN ps.condicion = 'Cumplida Parcial' THEN 1 ELSE NULL END ) AS \"Cumplida Parcial\"
        , COUNT(CASE WHEN ps.condicion = 'No Cumplida' THEN 1 ELSE NULL END ) AS \"No Cumplida\"
        , COUNT(CASE WHEN ps.condicion = 'En Proceso' THEN 1 ELSE NULL END ) AS \"En Proceso\"
        , COUNT(CASE WHEN ps.condicion = 'Asume el Riesgo' THEN 1 ELSE NULL END ) AS \"Asume el Riesgo\"
        FROM VW_PLANILLA_SEGUIMIENTO ps
        WHERE subsecretaria = '" . $subsecretaria . "' " . $queryDivision . "
        GROUP BY
        division
        , area_auditada";

        $cuadro7_13 = DB::select($query);
        return $cuadro7_13;
    }

    public static function por_condicion_otros() {



        $query = DB::select("select
            condicion as label
            , count(*) as value
        from vw_planilla_seguimiento
        where condicion not in (select condicion from collection_condicion) or condicion is null
        group by condicion");
        return $query;
    }

    public static function por_estado_otros() {

        $query = DB::select("select estado as label, count(*)  as value
        from vw_planilla_seguimiento
        where estado not in (select estado from collection_estado) or estado is null
        group by estado");
        return $query;
    }

}
