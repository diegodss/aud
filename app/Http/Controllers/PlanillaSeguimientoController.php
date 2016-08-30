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

class PlanillaSeguimientoController extends Controller {

    public function __construct() {

        $this->controller = "planilla_seguimiento";
        $this->title = "Planilla de Seguimiento";
        $this->subtitle = "Reporteria";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function excel() {
        $filename = "excel";
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$filename.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        return " archivo impreso \t coluna b";
    }

    public function index(Request $request) {
        DB::enableQueryLog();
        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $this->nomenclatura = array(
            "PMG" => "PMG"
            , "NO PMG" => "NO PMG"
            , "Contraloría General de la República" => "Contraloría General de la República"
        );
        $returnData['nomenclatura'] = $this->nomenclatura;

        $this->estado = array(
            "Reprogramado" => "Reprogramado"
            , "Finalizado" => "Finalizado"
            , "Vencido" => "Vencido"
            , "Asume el Riesgo" => "Asume el Riesgo"
            , "Vigente" => "Vigente"
            , "Suscripción" => "Suscripción"
        );
        $returnData['estado'] = $this->estado;

        $this->condicion = array(
            "Reprogramado" => "Reprogramado"
            , "En Proceso" => "En Proceso"
            , "Cumplida Parcial" => "Cumplida Parcial"
            , "No Cumplida" => "No Cumplida"
        );
        $returnData['condicion'] = $this->condicion;


        $division = CentroResponsabilidad::division()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['division'] = $division;

        $subsecretaria = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();
        $returnData['subsecretaria'] = $subsecretaria;

        // $busqueda = array('condicion' => 'En Proceso', 'estado' => 'Finalizado');
        $busqueda = array();
        $form = new \stdClass();
        $form->condicion = "";
        $form->estado = "";
        $form->nomenclatura = "";
        $form->division = "";
        $form->subsecretaria = "";
        $returnData['form'] = $form;

        $urlParams = "";
        if ($_GET) {

            if ($_GET["division"] != "") {
                $busqueda["division"] = $_GET["division"];
                $form->division = $_GET["division"];
                $urlParams .= "'division' => '" . $_GET["division"] . "'";
            }
            if ($_GET["subsecretaria"] != "") {
                $busqueda["subsecretaria"] = $_GET["subsecretaria"];
                $form->subsecretaria = $_GET["subsecretaria"];
                $urlParams .= "'subsecretaria' => '" . $_GET["subsecretaria"] . "'";
            }

            if ($_GET["condicion"] != "") {
                $busqueda["condicion"] = $_GET["condicion"];
                $form->condicion = $_GET["condicion"];
                $urlParams .= "'condicion' => '" . $_GET["condicion"] . "'";
            }
            if ($_GET["estado"] != "") {
                $busqueda["estado"] = $_GET["estado"];
                $form->estado = $_GET["estado"];
            }

            if ($_GET["nomenclatura"] != "") {
                $busqueda["nomenclatura"] = $_GET["nomenclatura"];
                $form->nomenclatura = $_GET["nomenclatura"];
            }

            if ($_GET["plazo_comprometido_inicio"] != "" && $_GET["plazo_comprometido_fin"] != "") {
                $busqueda["plazo_comprometido"] = $_GET["plazo_comprometido_inicio"] . "|" . $_GET["plazo_comprometido_fin"];
            }
        }
//        Log::error($_POST);
        $planillaSeguimiento = PlanillaSeguimiento::busqueda($busqueda);
        $graficoEstado = PlanillaSeguimiento::busqueda($busqueda, 'estado');

        $graficoEstadoArray = "[";
        $i = 1;
        $graficoEstadoArray .= "['Opening Move', 'Percentage'],";
        foreach ($graficoEstado as $row) {

            $comma = count($graficoEstado) == $i++ ? "" : ",";
            //Log::error(count($graficoEstado) . " == " . $i++ . " " . $comma);
            $graficoEstadoArray .= "['" . $row->estado . "', " . $row->total . "]" . $comma;
        }
        $graficoEstadoArray .= "]";

        $graficoCondicion = PlanillaSeguimiento::busqueda($busqueda, 'condicion');

        $graficoCondicionArray = "[";
        $i = 1;
        foreach ($graficoCondicion as $row) {

            $comma = count($graficoCondicion) == $i++ ? "" : ",";
            //Log::error(count($graficoCondicion) . " == " . $i++ . " " . $comma);
            $graficoCondicionArray .= "['" . $row->condicion . "', " . $row->total . "]" . $comma;
        }
        $graficoCondicionArray .= "]";

        $returnData["graficoCondicion"] = $graficoCondicionArray;
        $returnData["graficoEstado"] = $graficoEstadoArray;

        $returnData['planillaSeguimiento'] = $planillaSeguimiento;
        //Log::error(DB::getQueryLog());
        Log::error($graficoEstadoArray);
        /*
          Excel::create('Filename', function($excel) {

          $excel->sheet('Sheetname', function($sheet) {

          $sheet->fromArray(array(
          array('data1', 'data2'),
          array('data3', 'data4')
          ));
          });
          })->export('xls');

         */


        $camposTabla = PlanillaSeguimiento::getTableColumns();
        //Log::error($camposTabla);
        $returnData['camposTabla'] = $camposTabla;

        if (isset($_GET["columna"])) {
            $columna = $_GET["columna"];
        } else {
            foreach ($camposTabla as $row) {
                $columna[] = $row->column_name;
            }
        }

        $returnData['columna'] = $columna;

        $returnData['urlParams'] = $urlParams;

        $filter = \DataFilter::source(new \App\PlanillaSeguimiento); // (Region::with('nombre_region'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_proceso_auditado', 'ID')->style("width:80px");
        $grid->add('nomenclatura', 'nomenclatura')->style("width:80px");
        $grid->add('ano', 'ano')->style("width:80px");
        $grid->add('area_auditada', 'area_auditada')->style("width:80px");

        $grid->orderBy('id_proceso_auditado', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('planilla_seguimiento.index', $returnData);
    }

    public function create() {

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nueva Region";

        return View::make('region.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_region' => 'required'
        ]);

        $region = $request->all();
        $region["fl_status"] = $request->exists('fl_status') ? true : false;
        $region_new = Region::create($region);

        $mensage_success = trans('message.saved.success');

        if ($region["modal"] == "sim") {
            Log::info($region);
            return $region_new; //redirect()->route('region.index')
        } else {/*
          return redirect()->route('region.index')
          ->with('success', $mensage_success); */
            return $this->edit($region_new->id_region, true);
        }
        //
    }

    public function show($id) {

        /*        $region = Region::find($id);

          $returnData['region'] = $region;

          $returnData['title'] = $this->title;
          $returnData['subtitle'] = $this->subtitle;
          $returnData['titleBox'] = "Visualizar Region";
          return View::make('region.show', $returnData);
         * */
    }

    public function edit($id, $show_success_message = false) {

        $region = Region::find($id);

        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Region";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('region.edit', $returnData);
        } else {
            return View::make('region.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_region' => 'required'
        ]);

        $regionUpdate = $request->all();
        $regionUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $region = Region::find($id);
        $region->update($regionUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('region.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $region = Region::find($id);

        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Region";
        return View::make('region.delete', $returnData);
    }

    public function destroy($id) {
        Region::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_region' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_region/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_region' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
