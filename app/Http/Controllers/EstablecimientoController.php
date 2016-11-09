<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Establecimiento;
use App\ServicioSalud;

class EstablecimientoController extends Controller {

    public function __construct() {

        $this->controller = "establecimiento";
        $this->title = "Establecimientos";
        $this->subtitle = "Gestion de establecimientos";

        $this->tipo_establecimiento = array("1" => "Privado", "2" => "Publico");

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Establecimiento::with('servicio_salud'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_establecimiento', 'ID', true)->style("width:80px");
        $grid->add('nombre_establecimiento', 'Establecimiento', true);
        $grid->add('servicio_salud.nombre_servicio', 'Servicio de Salud', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_establecimiento', 'asc');
        $grid->paginate($itemsPage);
        $grid->row(function ($row) {
            if ($row->cell('fl_status')->value == "No") {
                $row->style("color:#cccccc");
            }
        });

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('establecimiento.index', $returnData);
    }

    public function create() {

        $establecimiento = new Establecimiento;
        $returnData['establecimiento'] = $establecimiento;

        $servicio_salud = ServicioSalud::active()->lists('nombre_servicio', 'id_servicio_salud')->all();
        $returnData['servicio_salud'] = $servicio_salud;

        $returnData['tipo_establecimiento'] = $this->tipo_establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Establecimiento";

        return View::make('establecimiento.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_servicio_salud' => 'required',
            'nombre_establecimiento' => 'required',
            'tipo_establecimiento' => 'required'
        ]);

        $establecimiento = $request->all();
        $establecimiento["fl_status"] = $request->exists('fl_status') ? true : false;
        $establecimiento_new = Establecimiento::create($establecimiento);

        $mensage_success = trans('message.saved.success');

        if ($establecimiento["modal"] == "sim") {
            Log::info($establecimiento);
            return $establecimiento_new; //redirect()->route('establecimiento.index')
        } else {/*
          return redirect()->route('establecimiento.index')
          ->with('success', $mensage_success); */
            return $this->edit($establecimiento_new->id_establecimiento, true);
        }
        //
    }

    public function show($id) {

        $establecimiento = Establecimiento::find($id);
        $returnData['establecimiento'] = $establecimiento;

        $servicio_salud = ServicioSalud::active()->lists('nombre_servicio', 'id_servicio_salud')->all();
        $returnData['servicio_salud'] = $servicio_salud;

        $returnData['tipo_establecimiento'] = $this->tipo_establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Establecimiento";
        return View::make('establecimiento.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $establecimiento = Establecimiento::find($id);
        $returnData['establecimiento'] = $establecimiento;

        $servicio_salud = ServicioSalud::active()->lists('nombre_servicio', 'id_servicio_salud')->all();
        $returnData['servicio_salud'] = $servicio_salud;

        $returnData['tipo_establecimiento'] = $this->tipo_establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Establecimiento";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('establecimiento.edit', $returnData);
        } else {
            return View::make('establecimiento.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_servicio_salud' => 'required',
            'nombre_establecimiento' => 'required',
            'tipo_establecimiento' => 'required'
        ]);

        $establecimientoUpdate = $request->all();
        $establecimientoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $establecimiento = Establecimiento::find($id);
        $establecimiento->update($establecimientoUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('establecimiento.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $establecimiento = Establecimiento::find($id);

        $returnData['establecimiento'] = $establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Establecimiento";
        return View::make('establecimiento.delete', $returnData);
    }

    public function destroy($id) {
        Establecimiento::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_establecimiento' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_establecimiento/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_establecimiento' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    function ajaxEstablecimiento(Request $request) {

        $id_servicio_salud = $request->input('id_servicio_salud');
        //Log::error($id_servicio_salud);
        $establecimiento = Establecimiento::where('id_servicio_salud', '=', $id_servicio_salud)->get();
        return $establecimiento;
    }

}
