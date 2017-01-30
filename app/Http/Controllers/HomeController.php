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

class HomeController extends Controller {

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
    public function index() {

        $porEstado = \App\InformeDetallado::por_estado();
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


        $porCondicionPmg = \App\InformeDetallado::por_condicion("PMG");
        $porCondicionNoPmg = \App\InformeDetallado::por_condicion("NO_PMG");

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

        Log::info($porCondicionPmg);
        Log::info($porCondicionNoPmg);

        return View::make('home', $returnData);
    }

}
