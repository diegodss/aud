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
        $this->nomenclatura = array(
            "PMG" => "PMG"
            , "NO PMG" => "NO PMG"
            , "Contraloría General de la República" => "Contraloría General de la República"
        );
        $this->estado = array(
            "Reprogramado" => "Reprogramado"
            , "Finalizado" => "Finalizado"
            , "Vencido" => "Vencido"
            , "Asume el Riesgo" => "Asume el Riesgo"
            , "Vigente" => "Vigente"
            , "Suscripción" => "Suscripción"
        );
        $this->condicion = array(
            "Reprogramado" => "Reprogramado"
            , "En Proceso" => "En Proceso"
            , "Cumplida Parcial" => "Cumplida Parcial"
            , "No Cumplida" => "No Cumplida"
        );

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

        $excel = chr(255) . chr(254) /* BOM */ . mb_convert_encoding($excel, 'UTF-16LE', 'UTF-8');

        //$excel .= "\xEF\xBB\xBF"; // UTF-8 BOM
        //return chr(255) . chr(254) . mb_convert_encoding($excel, 'UTF-16LE', 'UTF-8');
        return $excel;
    }

    public function index(Request $request) {
        $this->setViewVariables();
        DB::enableQueryLog();
        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $returnData['nomenclatura'] = $this->nomenclatura;
        $returnData['estado'] = $this->estado;
        $returnData['condicion'] = $this->condicion;
        $returnData['division'] = CentroResponsabilidad::division()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['subsecretaria'] = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();


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
                $form->plazo_comprometido_inicio = $_GET["plazo_comprometido_inicio"];
                $form->plazo_comprometido_fin = $_GET["plazo_comprometido_fin"];
            }
        }
        $returnData['form'] = $this->form;


        //Log::error(json_encode($urlParams));

        $planillaSeguimiento = PlanillaSeguimiento::busqueda($busqueda);
        $this->planillaSeguimiento = $planillaSeguimiento;

        Session::put('planillaSeguimiento', $planillaSeguimiento);

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
        $planillaSeguimientoColumnSize = $this->getColumnSize();
        $returnData['planillaSeguimientoColumnSize'] = $planillaSeguimientoColumnSize;

        //Log::error(DB::getQueryLog());



        $camposTabla = PlanillaSeguimiento::getTableColumns();

        $returnData['camposTabla'] = $camposTabla;

        if (isset($_GET["columna"])) {
            $columna = $_GET["columna"];
        } else {
            foreach ($camposTabla as $row) {
                $columna[] = $row->column_name;
            }
        }

        $this->columna = $columna;
        Session::put('columna', $columna);

        $planillaSeguimientoTableSize = 0;
        foreach ($columna as $rowColumna) {
            $planillaSeguimientoTableSize += $planillaSeguimientoColumnSize[$rowColumna];
        }

        $returnData['planillaSeguimientoTableSize'] = $planillaSeguimientoTableSize + 100;

        $returnData['columna'] = $columna;



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

    public function getColumnSize() {

        $arrayColumnSize["id"] = "100";
        $arrayColumnSize["nomenclatura"] = "100";
        $arrayColumnSize["ano"] = "40";
        $arrayColumnSize["subsecretaria"] = "100";
        $arrayColumnSize["division"] = "100";
        $arrayColumnSize["area_auditada"] = "100";
        $arrayColumnSize["numero_informe"] = "100";
        $arrayColumnSize["fecha"] = "100";
        $arrayColumnSize["proceso"] = "100";
        $arrayColumnSize["auditor"] = "100";
        $arrayColumnSize["hallazgo"] = "100";
        $arrayColumnSize["recomendacion"] = "100";
        $arrayColumnSize["responsable"] = "100";
        $arrayColumnSize["criticidad"] = "100";
        $arrayColumnSize["compromiso"] = "100";
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
