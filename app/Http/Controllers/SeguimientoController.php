<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Seguimiento;
use App\ControlCompromiso;
use App\Establecimiento;

class SeguimientoController extends Controller {

    public function __construct() {

        $this->controller = "seguimiento";
        $this->title = "Seguimientos";
        $this->subtitle = "Gestion de seguimientos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Seguimiento::with('control_compromiso'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_seguimiento', 'ID', true)->style("width:80px");
        $grid->add('diferencia_tiempo', 'Seguimiento', true);
        $grid->add('control_compromiso.nombre_control_compromiso', 'Centro Responsabilidad', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_seguimiento', 'asc');
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

        return View::make('seguimiento.index', $returnData);
    }

    public function create() {

        $seguimiento = new Seguimiento;
        $returnData['seguimiento'] = $seguimiento;

        $control_compromiso = ControlCompromiso::active()->lists('nombre_control_compromiso', 'id_control_compromiso')->all();
        $returnData['control_compromiso'] = $control_compromiso;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Seguimiento";

        return View::make('seguimiento.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_control_compromiso' => 'required',
            'diferencia_tiempo' => 'required'
        ]);

        $seguimiento = $request->all();
        $seguimiento["fl_status"] = $request->exists('fl_status') ? true : false;
        $seguimiento_new = Seguimiento::create($seguimiento);

        $mensage_success = trans('message.saved.success');

        if ($seguimiento["modal"] == "sim") {
            Log::info($seguimiento);
            return $seguimiento_new; //redirect()->route('seguimiento.index')
        } else {/*
          return redirect()->route('seguimiento.index')
          ->with('success', $mensage_success); */
            return $this->edit($seguimiento_new->id_seguimiento, true);
        }
        //
    }

    public function show($id) {

        $seguimiento = Seguimiento::find($id);
        $returnData['seguimiento'] = $seguimiento;

        $control_compromiso = ControlCompromiso::active()->lists('nombre_control_compromiso', 'id_control_compromiso')->all();
        $returnData['control_compromiso'] = $control_compromiso;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Seguimiento";
        return View::make('seguimiento.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $seguimiento = Seguimiento::find($id);
        $returnData['seguimiento'] = $seguimiento;

        $control_compromiso = ControlCompromiso::active()->lists('nombre_control_compromiso', 'id_control_compromiso')->all();
        $returnData['control_compromiso'] = $control_compromiso;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Seguimiento";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('seguimiento.edit', $returnData);
        } else {
            return View::make('seguimiento.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_control_compromiso' => 'required',
            'diferencia_tiempo' => 'required'
        ]);

        $seguimientoUpdate = $request->all();
        $seguimientoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $seguimiento = Seguimiento::find($id);
        $seguimiento->update($seguimientoUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $seguimiento = Seguimiento::find($id);

        $returnData['seguimiento'] = $seguimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Seguimiento";
        return View::make('seguimiento.delete', $returnData);
    }

    public function destroy($id) {
        Seguimiento::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_seguimiento' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_seguimiento/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_seguimiento' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    function ajaxSeguimiento(Request $request) {

        $id_control_compromiso = $request->input('id_control_compromiso');
        $seguimiento = Seguimiento::where('id_control_compromiso', '=', $id_control_compromiso)->get();
        return $seguimiento;
    }

}
