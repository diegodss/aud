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

        $porEstado = \App\InformeDetallado::por_estado(true);

        // CUADRO 01 por_estado
        $por_estado = $this->por_estado(true);
        $returnData["datagrid_por_estado"] = $por_estado["dataGrid"];


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

        /* array
          {
          value: 700,
          color: "#f56954",
          highlight: "#f56954",
          label: "Chrome"
          },
          {
          value: 500,
          color: "#00a65a",
          $obj->color = $color[$i];: "#00a65a",
          label: "IE"
          },
          {
          value: 400,
          color: "#f39c12",
          highlight: "#f39c12",
          label: "FireFox"
          } */

        $dataLabel = array();
        $dataPmg = array();
        $dataNoPmg = array();
        foreach ($porEstado as $data) {

            $dataLabel[] = $data->estado;
            $dataPmg[] = $data->tot_pmg;
            $dataNoPmg[] = $data->tot_no_pmg;
        }

        $porEstadoLabel = '["' . implode('","', $dataLabel) . '"]';
        $porEstadoDataPmg = '["' . implode('","', $dataPmg) . '"]';
        $porEstadoDataNoPmg = '["' . implode('","', $dataNoPmg) . '"]';

        $porEstadoDataPmg = str_replace('"', '', $porEstadoDataPmg);
        $porEstadoDataNoPmg = str_replace('"', '', $porEstadoDataNoPmg);

        $porEstadoDataPmg = str_replace(',', ', ', $porEstadoDataPmg);
        $porEstadoDataNoPmg = str_replace(',', ', ', $porEstadoDataNoPmg);

        $returnData["porEstadoLabel"] = $porEstadoLabel;
        $returnData["porEstadoDataPmg"] = $porEstadoDataPmg;
        $returnData["porEstadoDataNoPmg"] = $porEstadoDataNoPmg;


        $porCondicionPmg = \App\InformeDetallado::por_condicion("PMG", true);
        $porCondicionNoPmg = \App\InformeDetallado::por_condicion("NO_PMG", true);

        /*
          condicion
          total_condicion
          tot_pmg
          css
          perc_pmg


          stdClass::__set_state(array(
          'condicion' => 'En Proceso',
          'tot_no_pmg' => '11',
          'perc_no_pmg' => '24',
          )), */

        $totalPmg = 0;
        foreach ($porCondicionPmg as $item) {
            $totalPmg += $item->tot_pmg;
        }

        $totalNoPmg = 0;
        foreach ($porCondicionNoPmg as $item) {
            $totalNoPmg += $item->tot_no_pmg;
        }

        /* ALERTAS DE SEMAFORO */
        $compromiso_vencido_verde = Compromiso::compromiso_vencido("0", "30");
        $compromiso_vencido_amarilla = Compromiso::compromiso_vencido("31", "60");
        $compromiso_vencido_rojo = Compromiso::compromiso_vencido("61", "90");

        $returnData['compromiso_vencido_verde'] = $compromiso_vencido_verde->count();
        $returnData['compromiso_vencido_amarilla'] = $compromiso_vencido_amarilla->count();
        $returnData['compromiso_vencido_rojo'] = $compromiso_vencido_rojo->count();

        $returnData["condicion_css"] = array("aqua", "red", "green", "yellow", "yellow", "yellow", "yellow", "yellow");
        $returnData["total_condicion_pmg"] = $totalPmg;
        $returnData["total_condicion_no_pmg"] = $totalNoPmg;

        $returnData["porCondicionPmg"] = $porCondicionPmg;
        $returnData["porCondicionNoPmg"] = $porCondicionNoPmg;

        //Log::info($porCondicionPmg);
        //Log::info($porCondicionNoPmg);

        return View::make('home', $returnData);
    }

}
