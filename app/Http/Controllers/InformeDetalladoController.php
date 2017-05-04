<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\PlanillaSeguimiento;
use App\CentroResponsabilidad;
use App\Subsecretaria;
use App\PlanillaSeguimientoImport;
use App\ProcesoAuditado;
use App\Auditor;
use App\AreaProcesoAuditado;
use App\RelProcesoAuditor;
use Session;
use Excel;
use File;
use App\Compromiso;
use App\InformeDetallado;

class InformeDetalladoController extends Controller {

    public function __construct() {

        $this->controller = "informe_detallado";
        $this->title = "Informe Detallado de Auditoria";
        $this->subtitle = "Reporteria";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function por_estado($todos_ssp_ra = false) {
        // CUADRO 01
        // Configura las columnas usadas para cada grafico, de acuerdo con cada query (cuadro)
        $columns = array("estado", "tot_pmg", "perc_pmg", "tot_no_pmg", "perc_no_pmg", "total", "perc");
        $columns_label = array("Estado", "PMG", "% PMG", "NO PMG", "% NO PMG", "Total", "%");
        $columns_postfix = array("", "", "%", "", "%", "", "%");

        $cuadro = InformeDetallado::por_estado($todos_ssp_ra); // Get Data SQL
        $cuadro = $this->setTotal($cuadro, $columns); // Set totals row

        $retorno = $this->setCuadro($cuadro, $columns, $columns_postfix, $columns_label); // Set dataChart, columnaGoogleChart, excelData
        return $retorno;
    }

    public function por_condicion($subsecretaria, $ano, $nomenclatura) {
        // CUADRO 02 y CUADRO 05 tabla_no_pmg_condicion
        // Configura las columnas usadas para cada grafico, de acuerdo con cada query (cuadro)
        if ($nomenclatura == "PMG") {
            $columns = array("condicion", "tot_pmg", "perc_pmg");
            $columns_label = array("Condición", "PMG", "% PMG");
        } else {
            $columns = array("condicion", "tot_no_pmg", "perc_no_pmg");
            $columns_label = array("Condición", "NO PMG", "% NO PMG");
        }
        $columns_postfix = array("", "", "%");

        $cuadro = InformeDetallado::por_condicion($nomenclatura);
        $cuadro = $this->setTotal($cuadro, $columns);

        $retorno = $this->setCuadro($cuadro, $columns, $columns_postfix, $columns_label);

        return $retorno;
    }

    public function rango_por_condicion($subsecretaria, $ano, $nomenclatura, $condicion = "Cumplida Parcial") {
        // CUADRO 03 tabla_pmg_rango_Cumplida_parcial
        // Configura las columnas usadas para cada grafico, de acuerdo con cada query (cuadro)
        $columns = array("condicion", "de_1_a_50", "de_51_a_75", "de_76_a_a99");
        $columns_label = array("Condición", "de 1% a 50%", "de 51% a 75%", "de 76% a 99%");
        $columns_postfix = array("", "", "", "");

        $cuadro = InformeDetallado::rango_por_condicion($condicion, $nomenclatura);
        //$cuadro = $this->setTotal($cuadro, $columns);

        $retorno = $this->setCuadro($cuadro, $columns, $columns_postfix, $columns_label);
        return $retorno;
    }

    public function detalle_proceso($subsecretaria, $ano, $nomenclatura, $condicion) {
        // "Salud Pública", $anio, "NO PMG", "No Cumplida"
        // CUADRO 07 (tabla_no_pmg_condicion_no_Cumplida) y 13
        // Configura las columnas usadas para cada grafico, de acuerdo con cada query (cuadro)
        $columns = array("numero_informe", "fecha", "proceso", "area_auditada", "total_compromiso");
        $columns_label = array("Nº Informe", "Fecha", "Proceso", "Area Auditada", "Total");
        $columns_postfix = array("", "", "", "", "");

        $cuadro = InformeDetallado::detalle_proceso($condicion, $nomenclatura, $subsecretaria);
        //$cuadro = $this->setTotal($cuadro, $columns);

        $retorno = $this->setCuadro($cuadro, $columns, $columns_postfix, $columns_label);
        return $retorno;
    }

    public function detalle_area_auditada($subsecretaria, $division) {

        if ($subsecretaria == "Salud Pública") {
            $division = "Gabinete Ministro";
        }

        // CUADRO 08 y 16
        $columns = array("division", "area_auditada", "Cumplida", "Cumplida Parcial", "No Cumplida", "Asume Riesgo");
        $columns_label = array("División", "Area Auditada", "Cumplida", "Cumplida Parcial", "No Cumplida", "Asume Riesgo");
        $columns_postfix = array("", "", "", "", "", "", "");

        $cuadro = InformeDetallado::detalle_area_auditada($subsecretaria, $division);
        $cuadro = $this->setTotal($cuadro, $columns);

        $retorno = $this->setCuadro($cuadro, $columns, $columns_postfix, $columns_label);
        return $retorno;
    }

    public function index(Request $request) {

        // Set secretaria y año para crear vista y mostrar los datos
        $subsecretaria = isset($request->subsecretaria) ? $request->subsecretaria : "Salud Pública";
        $anio = ""; // En 24/4/17 Katherine solicita para retirar box de año. : isset($request->anio) ? $request->anio : $anio;
        $createview = InformeDetallado::createView($subsecretaria, $anio, "");

        // Datos para mostrar en pantalla
        $returnData['css_ssp'] = $subsecretaria == "Salud Pública" ? " btn-success" : "btn-default";
        $returnData['css_ra'] = $subsecretaria == "Redes Asistenciales" ? " btn-success" : "btn-default";

        $returnData['anio'] = $this->getYearSelectValues();
        $returnData['request_anio'] = $anio;
        $returnData['subsecretaria'] = $subsecretaria;

        // CUADRO 01 por_estado
        $por_estado = $this->por_estado();
        // $returnData["columnaGoogleChart_ssp"] = $por_estado["columnaGoogleChart"]; : grafico desabilitado
        $returnData["datagrid_por_estado"] = $por_estado["dataGrid"];
        Session::put('excel_por_estado', $por_estado["excelData"]);

        // CUADRO 02 tabla_pmg_condicion
        $por_condicion_pmg = $this->por_condicion("Salud Pública", $anio, "PMG");
        $returnData["datagrid_por_condicion_pmg"] = $por_condicion_pmg["dataGrid"];
        Session::put('excel_por_condicion_pmg', $por_condicion_pmg["excelData"]);

        // CUADRO 03 tabla_pmg_rango_Cumplida_parcial
        $rango_por_condicion_pmg = $this->rango_por_condicion("Salud Pública", $anio, "PMG");
        $returnData["datagrid_rango_por_condicion_pmg"] = $rango_por_condicion_pmg["dataGrid"];
        Session::put('excel_rango_por_condicion_pmg', $rango_por_condicion_pmg["excelData"]);

        // CUADRO 05 tabla_no_pmg_condicion
        $por_condicion_no_pmg = $this->por_condicion("Salud Pública", $anio, "NO_PMG");
        $returnData["datagrid_por_condicion_no_pmg"] = $por_condicion_no_pmg["dataGrid"];
        Session::put('excel_por_condicion_no_pmg', $por_condicion_no_pmg["excelData"]);

        // CUADRO 06 tabla_no_pmg_rango_Cumplida_parcial
        $rango_por_condicion_no_pmg = $this->rango_por_condicion("Salud Pública", $anio, "NO_PMG");
        $returnData["datagrid_rango_por_condicion_no_pmg"] = $rango_por_condicion_no_pmg["dataGrid"];
        Session::put('excel_rango_por_condicion_no_pmg', $rango_por_condicion_no_pmg["excelData"]);

        // CUADRO 07 tabla_no_pmg_condicion_no_Cumplida
        $detalle_proceso = $this->detalle_proceso("Salud Pública", $anio, "NO_PMG", "No Cumplida");
        $returnData["datagrid_detalle_proceso"] = $detalle_proceso["dataGrid"];
        Session::put('excel_detalle_proceso', $detalle_proceso["excelData"]);

        // CUADRO 08 tabla_area_auditad
        $division = "";
        $detalle_area_auditada = $this->detalle_area_auditada($subsecretaria, $division);
        $returnData["datagrid_detalle_area_auditada"] = $detalle_area_auditada["dataGrid"];
        Session::put('excel_detalle_area_auditada', $detalle_area_auditada["excelData"]);

        // CUADRO 09 tabla_division
        /*
          $cuadro9 = $this->por_condicion("Salud Pública", $anio, "PMG");
          $returnData["columnaGoogleChart_cuadro9"] = $cuadro9["columnaGoogleChart"];
          $returnData["tabla_cuadro9"] = $cuadro9["dataChart"];
          Session::put('tabla_cuadro9', $cuadro9["excelData"]);
         */

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('informe_detallado.index', $returnData);
    }

    public function setTotal($data, $columns) {

        $total = new \stdClass();
        foreach ($columns as $col) {
            $total->{$col} = 0;
        }

        foreach ($data as $linea) {
            $a = 0;
            foreach ($columns as $col) {
                if ($a == 0) {
                    $total->{$col} = "Total";
                } else {
                    if (is_numeric($linea->{$col})) {
                        $total->{$col} = $total->{$col} + $linea->{$col};
                    } else {
                        $total->{$col} = "";
                    }
                }
                $a++;
            }
        }
        $data[] = $total;
        return $data;
    }

    public function setCuadro($cuadro, $columns, $columns_postfix, $columns_label = null) {

        // Configura datagrid para mostrar los datos en pantalla
        $dataGrid = $this->dataGridTable($cuadro, $columns, $columns_label, $columns_postfix);

        // Configura las columnas a mostrar en Google Chart (grafico o tabla)
        $columnaGoogleChart = $this->setColumnGoogleChart($columns);

        //Configura los datos para para exportar a excel
        $dataGoogleChart = $this->setDataGoogleChart($cuadro, $columns, $columns_postfix);

        $dataChartGoogleChart = $dataGoogleChart["dataChart"];
        $dataExcelGoogleChart = $dataGoogleChart["dataExcel"];

        $retorno = array(
            "dataGrid" => $dataGrid
            , "columnaGoogleChart" => $columnaGoogleChart
            , "excelData" => $dataExcelGoogleChart
        );
        return $retorno;
    }

    public function dataGridTable($datasource, $columns, $columns_label, $columns_postfix) {

        $grid = \DataGrid::source($datasource);
        for ($i = 0; $i < count($columns); $i++) {

            //if ($i == 0) {
            $grid->add($columns[$i], $columns_label[$i], false)->cell(function( $value, $row )use($columns, $columns_postfix, $i) {
                return $row->{$columns[$i]} . " " . $columns_postfix[$i];
            });
            //} else {
            //    $grid->add($columns[$i], $columns_label[$i], false)->style("text-align:center");
            //}
        }
        return $grid;
    }

    public function setColumnGoogleChart($column) {
        $columnGoogleChart = "";
        foreach ($column as $col) {
            $columnGoogleChart .= "data.addColumn('string', '" . $col . "');";
        }
        return $columnGoogleChart;
    }

    public function setDataGoogleChart($data, $column, $columns_postfix) {
        $x = 0;
        $dataChart = "[";
        $dataExcel = array();
        foreach ($data as $linea) {
            $comma = count($data) == ($x++) + 1 ? "" : ", ";
            $dataChart .= "[";
            $j = 0;
            foreach ($column as $col) {

                $sub_comma = count($column) == ($j++) + 1 ? "" : ", ";
                $dataExcel[$x][$col] = $linea->{$col} . " " . $columns_postfix[$j - 1];
                $dataChart .= "'" . $linea->{$col} . " " . $columns_postfix[$j - 1] . "'" . $sub_comma;
            }
            $dataChart .= "]" . $comma;
        }
        $dataChart .= "]";

        $retorno = array(
            "dataChart" => $dataChart
            , "dataExcel" => $dataExcel
        );
        return $retorno;
    }

    public function excel($subsecretaria) {
        //http://www.maatwebsite.nl/laravel-excel/docs/export
        $fechaActual = date("d") . "-" . date("m") . "-" . date("Y");
        $filename = "informe_detallado_" . $subsecretaria . "_" . $fechaActual;


        $excel_por_estado = Session::get('excel_por_estado');
        $excel_por_condicion_pmg = Session::get('excel_por_condicion_pmg');
        $excel_rango_por_condicion_pmg = Session::get('excel_rango_por_condicion_pmg');
        $excel_por_condicion_no_pmg = Session::get('excel_por_condicion_no_pmg');
        $excel_rango_por_condicion_no_pmg = Session::get('excel_rango_por_condicion_no_pmg');
        $excel_detalle_proceso = Session::get('excel_detalle_proceso');
        $excel_detalle_area_auditada = Session::get('excel_detalle_area_auditada');

        $array = array(
            'por_estado' => $excel_por_estado
            , 'por_condicion_pmg' => $excel_por_condicion_pmg
            , 'rango_por_condicion_pmg' => $excel_rango_por_condicion_pmg
            , 'por_condicion_no_pmg' => $excel_por_condicion_no_pmg
            , 'rango_por_condicion_no_pmg' => $excel_rango_por_condicion_no_pmg
            , 'detalle_proceso' => $excel_detalle_proceso
            , 'detalle_area_auditada' => $excel_detalle_area_auditada
        );

        $arrayTitle = array(
            'por_estado' => 'Por Estado'
            , 'por_condicion_pmg' => 'Por Condición PMG'
            , 'rango_por_condicion_pmg' => 'Por Condición, cuando condición es "Cumplida Parcial" - PMG'
            , 'por_condicion_no_pmg' => 'Por Condición NO PMG'
            , 'rango_por_condicion_no_pmg' => 'Por Condición, cuando condición es "Cumplida Parcial" - NO PMG'
            , 'detalle_proceso' => 'No PMG, cuando condición es "No Cumplida"'
            , 'detalle_area_auditada' => 'Area auditada y cantidad de compromisos por condicion'
        );

        Excel::create($filename, function($excel)use($array, $fechaActual, $arrayTitle) {

            foreach ($array as $key => $value) {

                $titlePage = $arrayTitle[$key];
                $excel->sheet($key, function($sheet) use($value, $titlePage, $key) {

                    //$sheet->fromArray($value);
                    $sheet->loadView('layouts.excel', array('nombre_hoja' => $key, 'titulo' => $titlePage, 'datos' => $value));
                });
            }
        })->export('xls');
    }

    public function getYearSelectValues() {
        $anoInicial = date("Y");
        $anoFinal = $anoInicial - 10;
        for ($i = $anoInicial; $i >= $anoFinal; $i--) {
            $ano[$i] = $i;
        }
        return $ano;
    }

}
