<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Excel;
use File;
use App\Compromiso;
use App\InformeDetallado;

class HomeController extends InformeDetalladoController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        //    $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function setColorDoughnut($array) {
        $color = array("#f56954", "#00a65a", "#f39c12", "#00c0ef", "#3c8dbc", "#d2d6de");
        $highlight = array("#f56954", "#00a65a", "#f39c12", "#00c0ef", "#3c8dbc", "#d2d6de");

        $i = 0;
        foreach ($array as &$obj) {

            if (count($obj->label) == 0) {
                $obj->label = "Vacío";
            }
            Log::info($obj->label . " - " . count($obj->label) . " - " . is_null($obj->label));
            $obj->color = $color[$i];
            $obj->highlight = $highlight[$i];
            $i++;
        }
        return $array;
    }

    public function index(Request $request) {


        $returnData["condicion_css"] = array("aqua", "red", "green", "yellow", "yellow", "yellow", "yellow", "yellow");




        // ***** Gabinete *****


        /* == Estado == */
        $createview = InformeDetallado::createView("", "", "Gabinete");
        $por_estado = $this->por_estado(false);
        $returnData["datagrid_por_estado_gabinete"] = $por_estado["dataGrid"];

        $dataLabel = array();
        $dataPmg = array();
        $dataNoPmg = array();

        foreach ($por_estado["excelData"] as $data) {

            $dataLabel[] = $data["estado"];
            $dataPmg[] = $data["tot_pmg"];
            $dataNoPmg[] = $data["tot_no_pmg"];
        }

        $porEstadoLabel = '["' . implode('","', $dataLabel) . '"]';
        $porEstadoGabineteDataPmg = '["' . implode('","', $dataPmg) . '"]';
        $porEstadoGabineteDataNoPmg = '["' . implode('","', $dataNoPmg) . '"]';

        $returnData["porEstadoLabel"] = $porEstadoLabel;
        $returnData["porEstadoGabineteDataPmg"] = $porEstadoGabineteDataPmg;
        $returnData["porEstadoGabineteDataNoPmg"] = $porEstadoGabineteDataNoPmg;


        /* == Condicion == */
        $porCondicionGabinetePmg = \App\InformeDetallado::por_condicion("PMG", false);
        $porCondicionGabineteNoPmg = \App\InformeDetallado::por_condicion("NO_PMG", false);

        $totalPmg = 0;
        foreach ($porCondicionGabinetePmg as $item) {
            $totalPmg += $item->tot_pmg;
        }

        $totalNoPmg = 0;
        foreach ($porCondicionGabineteNoPmg as $item) {
            $totalNoPmg += $item->tot_no_pmg;
        }

        $returnData["total_condicion_gabinete_pmg"] = $totalPmg;
        $returnData["total_condicion_gabinete_no_pmg"] = $totalNoPmg;

        $returnData["porCondicionGabinetePmg"] = $porCondicionGabinetePmg;
        $returnData["porCondicionGabineteNoPmg"] = $porCondicionGabineteNoPmg;

        // ***** Salud Pública *****
        $createview = InformeDetallado::createView("Salud Pública", "", "!Gabinete");
        $por_estado = $this->por_estado(false);
        $returnData["datagrid_por_estado_ssp"] = $por_estado["dataGrid"];

        $dataLabel = array();
        $dataPmg = array();
        $dataNoPmg = array();

        foreach ($por_estado["excelData"] as $data) {

            $dataPmg[] = $data["tot_pmg"];
            $dataNoPmg[] = $data["tot_no_pmg"];
        }

        $porEstadoSspDataPmg = '["' . implode('","', $dataPmg) . '"]';
        $porEstadoSspDataNoPmg = '["' . implode('","', $dataNoPmg) . '"]';

        $returnData["porEstadoSspDataPmg"] = $porEstadoSspDataPmg;
        $returnData["porEstadoSspDataNoPmg"] = $porEstadoSspDataNoPmg;

        /* == Condicion == */
        $porCondicionSspPmg = \App\InformeDetallado::por_condicion("PMG", false);
        $porCondicionSspNoPmg = \App\InformeDetallado::por_condicion("NO_PMG", false);

        $totalPmg = 0;
        foreach ($porCondicionSspPmg as $item) {
            $totalPmg += $item->tot_pmg;
        }

        $totalNoPmg = 0;
        foreach ($porCondicionSspNoPmg as $item) {
            $totalNoPmg += $item->tot_no_pmg;
        }

        $returnData["total_condicion_ssp_pmg"] = $totalPmg;
        $returnData["total_condicion_ssp_no_pmg"] = $totalNoPmg;

        $returnData["porCondicionSspPmg"] = $porCondicionSspPmg;
        $returnData["porCondicionSspNoPmg"] = $porCondicionSspNoPmg;

        // ***** Redes Asistenciales *****
        $createview = InformeDetallado::createView("Redes Asistenciales", "", "!Gabinete");
        $por_estado = $this->por_estado(false);
        $returnData["datagrid_por_estado_ra"] = $por_estado["dataGrid"];

        $dataLabel = array();
        $dataPmg = array();
        $dataNoPmg = array();

        foreach ($por_estado["excelData"] as $data) {

            $dataPmg[] = $data["tot_pmg"];
            $dataNoPmg[] = $data["tot_no_pmg"];
        }

        $porEstadoRaDataPmg = '["' . implode('","', $dataPmg) . '"]';
        $porEstadoRaDataNoPmg = '["' . implode('","', $dataNoPmg) . '"]';

        $returnData["porEstadoRaDataPmg"] = $porEstadoRaDataPmg;
        $returnData["porEstadoRaDataNoPmg"] = $porEstadoRaDataNoPmg;

        /* == Condicion == */
        $porCondicionRaPmg = \App\InformeDetallado::por_condicion("PMG", false);
        $porCondicionRaNoPmg = \App\InformeDetallado::por_condicion("NO_PMG", false);

        $totalPmg = 0;
        foreach ($porCondicionRaPmg as $item) {
            $totalPmg += $item->tot_pmg;
        }

        $totalNoPmg = 0;
        foreach ($porCondicionRaNoPmg as $item) {
            $totalNoPmg += $item->tot_no_pmg;
        }

        $returnData["total_condicion_ra_pmg"] = $totalPmg;
        $returnData["total_condicion_ra_no_pmg"] = $totalNoPmg;

        $returnData["porCondicionRaPmg"] = $porCondicionRaPmg;
        $returnData["porCondicionRaNoPmg"] = $porCondicionRaNoPmg;


        /* OTROS */
        $porEstadoOtros = \App\InformeDetallado::por_estado_otros(true);
        $porCondicionOtros = \App\InformeDetallado::por_condicion_otros(true);

        $porEstadoOtros = $this->setColorDoughnut($porEstadoOtros);
        $porCondicionOtros = $this->setColorDoughnut($porCondicionOtros);

        $porEstadoOtros_doughnut = json_encode($porEstadoOtros);
        //Log::info();
        $returnData["porEstadoOtros"] = ($porEstadoOtros);
        $returnData["porEstadoOtros_doughnut"] = ($porEstadoOtros_doughnut);

        $porCondicionOtros_doughnut = json_encode($porCondicionOtros);
        //Log::info();
        $returnData["porCondicionOtros"] = ($porCondicionOtros);
        $returnData["porCondicionOtros_doughnut"] = ($porCondicionOtros_doughnut);


        /* ALERTAS DE SEMAFORO */
        $compromiso_vencido_verde = Compromiso::compromiso_vencido("0", "30");
        $compromiso_vencido_amarilla = Compromiso::compromiso_vencido("31", "60");
        $compromiso_vencido_rojo = Compromiso::compromiso_vencido("61", "90");

        $returnData['compromiso_vencido_verde'] = $compromiso_vencido_verde->count();
        $returnData['compromiso_vencido_amarilla'] = $compromiso_vencido_amarilla->count();
        $returnData['compromiso_vencido_rojo'] = $compromiso_vencido_rojo->count();

        return View::make('home', $returnData);
    }

}
