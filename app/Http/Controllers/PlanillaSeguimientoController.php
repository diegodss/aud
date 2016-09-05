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
use Session;

class PlanillaSeguimientoController extends Controller {

    public function __construct() {

        $this->controller = "planilla_seguimiento";
        $this->title = "Planilla de Seguimiento";
        $this->subtitle = "Reporteria";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function setViewVariables() {

        $form = new \stdClass();
        $form->condicion = "";
        $form->estado = "";
        $form->nomenclatura = "";
        $form->division = "";
        $form->subsecretaria = "";
        $form->plazo_comprometido_inicio = "";
        $form->plazo_comprometido_fin = "";
        $this->form = $form;
    }

    public function index(Request $request) {
        $this->setViewVariables();
        DB::enableQueryLog();

        $returnData['nomenclatura'] = config('collection.nomenclatura');
        $returnData['estado'] = config('collection.estado');
        $returnData['condicion'] = config('collection.condicion');
        $returnData['division'] = CentroResponsabilidad::division()->lists('nombre_centro_responsabilidad', 'nombre_centro_responsabilidad')->all();
        $returnData['subsecretaria'] = Subsecretaria::active()->lists('nombre_subsecretaria', 'nombre_subsecretaria')->all();

        $busqueda = $this->setBusqueda();
        $returnData['form'] = $this->form;

        $planillaSeguimiento = PlanillaSeguimiento::busqueda($busqueda);
        Session::put('planillaSeguimiento', $planillaSeguimiento); // para imprimir excel
        $returnData['planillaSeguimiento'] = $planillaSeguimiento;

        $returnData["graficoCondicion"] = $this->getGraficoCondicion($busqueda);
        $returnData["graficoEstado"] = $this->getGraficoEstado($busqueda);

        $planillaSeguimientoColumnSize = $this->getColumnSize();
        $returnData['planillaSeguimientoColumnSize'] = $planillaSeguimientoColumnSize;

        /// Log::error(DB::getQueryLog());

        $camposTabla = PlanillaSeguimiento::getTableColumns();
        $returnData['camposTabla'] = $camposTabla;

        $columna = $this->setColumna($camposTabla);
        Session::put('columna', $columna);
        $returnData['columna'] = $columna;

        $returnData['planillaSeguimientoTableSize'] = $this->setTableSize($columna, $planillaSeguimientoColumnSize);

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('planilla_seguimiento.index', $returnData);
    }

    public function excel() {

        $fechaActual = date("d") . "-" . date("m") . "-" . date("Y");
        $filename = "planilla_seguimiento_" . $fechaActual;

        header("Content-Type: application/xls;");
        header("Content-Disposition: attachment; filename=$filename.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $excel = "";
        $excel .= "Planilla de Seguimiento " . $fechaActual;
        $excel .= "\n";
        $excel .= "\n";
        $columna = Session::get('columna');
        $planillaSeguimiento = Session::get('planillaSeguimiento');

        foreach ($columna as $rowColumna) {
            $excel .= $rowColumna . "\t";
        }
        $excel .= "\n";
        foreach ($planillaSeguimiento as $linea) {
            foreach ($columna as $rowColumna) {
                $excel .= $linea[$rowColumna] . "\t";
            }
            $excel .= "\n";
        }

        $excel = chr(255) . chr(254) . mb_convert_encoding($excel, 'UTF-16LE', 'UTF-8');
        return $excel;
    }

    public function setBusqueda() {
        $busqueda = array();
        if ($_GET) {

            if (isset($_GET["division"]) && $_GET["division"] != "") {
                $busqueda["division"] = $_GET["division"];
                $this->form->division = $_GET["division"];
            }
            if (isset($_GET["subsecretaria"]) && $_GET["subsecretaria"] != "") {
                $busqueda["subsecretaria"] = $_GET["subsecretaria"];
                $this->form->subsecretaria = $_GET["subsecretaria"];
            }

            if (isset($_GET["condicion"]) && $_GET["condicion"] != "") {
                $busqueda["condicion"] = $_GET["condicion"];
                $this->form->condicion = $_GET["condicion"];
            }
            if (isset($_GET["estado"]) && $_GET["estado"] != "") {
                $busqueda["estado"] = $_GET["estado"];
                $this->form->estado = $_GET["estado"];
            }

            if (isset($_GET["nomenclatura"]) && $_GET["nomenclatura"] != "") {
                $busqueda["nomenclatura"] = $_GET["nomenclatura"];
                $this->form->nomenclatura = $_GET["nomenclatura"];
            }

            if (isset($_GET["plazo_comprometido_inicio"]) && isset($_GET["plazo_comprometido_fin"]) && $_GET["plazo_comprometido_inicio"] != "" && $_GET["plazo_comprometido_fin"] != "") {
                $busqueda["plazo_comprometido"] = $_GET["plazo_comprometido_inicio"] . "|" . $_GET["plazo_comprometido_fin"];
                $this->form->plazo_comprometido_inicio = $_GET["plazo_comprometido_inicio"];
                $this->form->plazo_comprometido_fin = $_GET["plazo_comprometido_fin"];
            }
        }
        return $busqueda;
    }

    public function setTableSize($columna, $planillaSeguimientoColumnSize) {
        $planillaSeguimientoTableSize = 0;
        foreach ($columna as $rowColumna) {
            $planillaSeguimientoTableSize += $planillaSeguimientoColumnSize[$rowColumna];
        }
        return $planillaSeguimientoTableSize + 100;
    }

    public function setColumna($camposTabla) {
        if (isset($_GET["columna"])) {
            $columna = $_GET["columna"];
        } else {
            foreach ($camposTabla as $row) {
                $columna[] = $row->column_name;
            }
        }

        return $columna;
    }

    public function getGraficoEstado($busqueda) {

        $graficoEstado = PlanillaSeguimiento::busqueda($busqueda, 'estado');

        $graficoEstadoArray = "[";
        $i = 1;
        $graficoEstadoArray .= "['Opening Move', 'Percentage'],";
        foreach ($graficoEstado as $row) {

            $comma = count($graficoEstado) == $i++ ? "" : ",";
            $graficoEstadoArray .= "['" . $row->estado . "', " . $row->total . "]" . $comma;
        }
        $graficoEstadoArray .= "]";
        return $graficoEstadoArray;
    }

    public function getGraficoCondicion($busqueda) {

        $graficoCondicion = PlanillaSeguimiento::busqueda($busqueda, 'condicion');
        $graficoCondicionArray = "[";
        $i = 1;
        foreach ($graficoCondicion as $row) {

            $comma = count($graficoCondicion) == $i++ ? "" : ",";
            //Log::error(count($graficoCondicion) . " == " . $i++ . " " . $comma);
            $graficoCondicionArray .= "['" . $row->condicion . "', " . $row->total . "]" . $comma;
        }
        $graficoCondicionArray .= "]";
        return $graficoCondicionArray;
    }

    public function getColumnSize() {

        $arrayColumnSize["id"] = "100";
        $arrayColumnSize["nomenclatura"] = "100";
        $arrayColumnSize["ano"] = "40";
        $arrayColumnSize["subsecretaria"] = "100";
        $arrayColumnSize["division"] = "200";
        $arrayColumnSize["area_auditada"] = "200";
        $arrayColumnSize["numero_informe"] = "100";
        $arrayColumnSize["fecha"] = "130";
        $arrayColumnSize["proceso"] = "200";
        $arrayColumnSize["auditor"] = "200";
        $arrayColumnSize["hallazgo"] = "200";
        $arrayColumnSize["recomendacion"] = "200";
        $arrayColumnSize["responsable"] = "200";
        $arrayColumnSize["criticidad"] = "100";
        $arrayColumnSize["compromiso"] = "200";
        $arrayColumnSize["plazo_estimado"] = "100";
        $arrayColumnSize["plazo_comprometido"] = "100";
        $arrayColumnSize["diferencia_tiempo"] = "100";
        $arrayColumnSize["porcentaje_avance"] = "100";
        $arrayColumnSize["condicion"] = "100";
        $arrayColumnSize["estado"] = "100";
        $arrayColumnSize["descripcion"] = "100";
        $arrayColumnSize["observacion"] = "100";
        return $arrayColumnSize;
    }

}
