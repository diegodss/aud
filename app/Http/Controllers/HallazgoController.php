<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Hallazgo;
use App\ProcesoAuditado;

class HallazgoController extends Controller {

    public function __construct() {

        $this->controller = "hallazgo";
        $this->title = "Hallazgos";
        $this->subtitle = "Gestion de hallazgos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function setViewVariables() {
        $this->criticidad = array(
            "" => "Seleccione"
            , "Alta" => "Alta"
            , "Media" => "Media"
            , "Baja" => "Baja");

        $this->proceso_auditado = ProcesoAuditado::active()->lists('nombre_proceso_auditado', 'id_proceso_auditado')->all();
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Hallazgo::with('proceso_auditado'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_hallazgo', 'ID', true)->style("width:80px");
        $grid->add('proceso_auditado.nombre_proceso_auditado', 'Proceso', true);
        $grid->add('nombre_hallazgo', 'Hallazgo', true);
        $grid->add('recomendacion', 'Recomedacion', true);

        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_hallazgo', 'asc');
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

        return View::make('hallazgo.index', $returnData);
    }

    public function create($id_proceso_auditado) {

        $this->setViewVariables();
        $hallazgo = new Hallazgo;
        $hallazgo->id_proceso_auditado = $id_proceso_auditado;
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($id_proceso_auditado);
        $returnData['nombre_proceso_auditado'] = $proceso_auditado->nombre_proceso_auditado;

        $returnData['proceso_auditado'] = $this->proceso_auditado;
        $returnData['criticidad'] = $this->criticidad;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Hallazgo";

        return View::make('hallazgo.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_hallazgo' => 'required',
            'recomendacion' => 'required',
            'id_proceso_auditado' => 'required',
        ]);

        $hallazgo = $request->all();
        $hallazgo["fl_status"] = $request->exists('fl_status') ? true : false;
        $hallazgo_new = Hallazgo::create($hallazgo);

        $mensage_success = trans('message.saved.success');

        if ($hallazgo["modal"] == "sim") {
            return $hallazgo_new;
        } else {
            return $this->edit($hallazgo_new->id_hallazgo, true);
        }
    }

    public function show($id) {
        $this->setViewVariables();
        $hallazgo = Hallazgo::find($id);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['nombre_proceso_auditado'] = $proceso_auditado->nombre_proceso_auditado;

        $returnData['proceso_auditado'] = $this->proceso_auditado;
        $returnData['criticidad'] = $this->criticidad;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Hallazgo";
        return View::make('hallazgo.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {
        $this->setViewVariables();
        $hallazgo = Hallazgo::find($id);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['nombre_proceso_auditado'] = $proceso_auditado->nombre_proceso_auditado;

        $returnData['proceso_auditado'] = $this->proceso_auditado;
        $returnData['criticidad'] = $this->criticidad;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Hallazgo";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('hallazgo.edit', $returnData);
        } else {
            return View::make('hallazgo.edit', $returnData)->withSuccess($mensage_success);
        }
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_hallazgo' => 'required',
            'recomendacion' => 'required',
            'id_proceso_auditado' => 'required',
        ]);

        $hallazgoUpdate = $request->all();
        $hallazgoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $hallazgo = Hallazgo::find($id);
        $hallazgo->update($hallazgoUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $hallazgo = Hallazgo::find($id);

        $returnData['hallazgo'] = $hallazgo;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Hallazgo";
        return View::make('hallazgo.delete', $returnData);
    }

    public function destroy($id) {
        Hallazgo::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_hallazgo' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_hallazgo/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_hallazgo' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
