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
use App\Compromiso;

class HallazgoController extends Controller {

    public function __construct() {

        $this->controller = "hallazgo";
        $this->title = "Hallazgos";
        $this->subtitle = "Gestion de hallazgos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Hallazgo::procesoAuditado());
        $filter->add('numero_informe', 'Nº Informe', 'text')->clause('where')->operator('=');
        $filter->add('numero_informe_unidad', 'Unidad', 'text')->clause('where')->operator('=');
        $filter->add('ano', 'Año', 'text')->clause('where')->operator('=');
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('numero_informe', 'nº', true)->style("width:80px")->cell(function( $value, $row ) {
            return $row->numero_informe . " " . $row->numero_informe_unidad;
        });
        $grid->add('nombre_proceso_auditado', 'Proceso', true);
        $grid->add('nombre_hallazgo', 'Hallazgo', true);
        $grid->add('recomendacion', 'Recomedacion', true);
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:180px; text-align:center");
        $grid->orderBy('id_hallazgo', 'asc');
        $grid->paginate($itemsPage);

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

        $hallazgo = new Hallazgo;
        $hallazgo->id_proceso_auditado = $id_proceso_auditado;
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($id_proceso_auditado);
        $returnData['proceso_auditado'] = $proceso_auditado;

        $returnData['criticidad'] = config('collection.criticidad');

        $cuantidad_hallazgo = $proceso_auditado->cuantidad_hallazgo;
        $cuantidad_hallazgo_db = Hallazgo::getCuantidadHallazgoDb($hallazgo->id_proceso_auditado);

        $returnData['cuantidad_hallazgo_db'] = $cuantidad_hallazgo_db;
        $returnData['cuanditad_hallazgo'] = $cuantidad_hallazgo;


        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Hallazgo";

        return View::make('hallazgo.create', $returnData);
    }

    public function createMultiple($id_proceso_auditado, $cuantidad_hallazgo) {

        $hallazgo = new Hallazgo;
        $hallazgo->id_proceso_auditado = $id_proceso_auditado;
        $returnData['hallazgo'] = $hallazgo;

        $returnData['cuantidad_hallazgo'] = $cuantidad_hallazgo;

        $proceso_auditado = ProcesoAuditado::find($id_proceso_auditado);
        $returnData['nombre_proceso_auditado'] = $proceso_auditado->nombre_proceso_auditado;

        $returnData['criticidad'] = config('collection.criticidad');

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $cuantidad_hallazgo = $proceso_auditado->cuantidad_hallazgo;
        $cuantidad_hallazgo_db = Hallazgo::getCuantidadHallazgoDb($hallazgo->id_proceso_auditado);


        $returnData['cuantidad_hallazgo_db'] = $cuantidad_hallazgo_db;
        $returnData['cuanditad_hallazgo'] = $cuantidad_hallazgo;


        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Hallazgo";

        return View::make('hallazgo.create_multiple', $returnData);
    }

    public function store(Request $request) {

        if ($request->cuantidad_hallazgo > 0) {


            $rules['id_proceso_auditado'] = ['required'];
            for ($i = 1; $i <= $request->cuantidad_hallazgo; $i++) {
                $rules['nombre_hallazgo_' . $i] = ['required'];
                $rules['recomendacion_' . $i] = ['required'];
                $rules['criticidad_' . $i] = ['required'];

                /*
                  'recomendacion_' . $i => 'required',
                  'id_proceso_auditado_' . $i => 'required',
                  ]; */
            }
            $this->validate($request, $rules);


            for ($i = 1; $i <= $request->cuantidad_hallazgo; $i++) {

                $hallazgo = new Hallazgo();
                $hallazgo->id_proceso_auditado = $request["id_proceso_auditado"];
                $hallazgo->nombre_hallazgo = $request["nombre_hallazgo_" . $i];
                $hallazgo->recomendacion = $request["recomendacion_" . $i];
                $hallazgo->criticidad = $request["criticidad_" . $i];
                $hallazgo->save();
            }
        } else {
            $this->validate($request, [
                'nombre_hallazgo' => 'required',
                'recomendacion' => 'required',
                'id_proceso_auditado' => 'required',
            ]);

            $hallazgo = $request->all();
            $hallazgo["fl_status"] = $request->exists('fl_status') ? true : false;
            $hallazgo_new = Hallazgo::create($hallazgo);
        }
        $this->verificaCuantidadHallazgo($request->id_proceso_auditado);

        return redirect()->route('proceso_auditado.edit', $request->id_proceso_auditado);
    }

    public function show($id) {

        $hallazgo = Hallazgo::find($id);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['nombre_proceso_auditado'] = $proceso_auditado->nombre_proceso_auditado;

        $returnData['criticidad'] = config('collection.criticidad');

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Hallazgo";
        return View::make('hallazgo.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {


        $hallazgo = Hallazgo::find($id);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['nombre_proceso_auditado'] = $proceso_auditado->nombre_proceso_auditado;
        $returnData['proceso_auditado'] = $proceso_auditado;


        $returnData['compromiso'] = $this->compromiso($id);

        $returnData['criticidad'] = config('collection.criticidad');

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
            'criticidad' => 'required',
            'id_proceso_auditado' => 'required',
        ]);

        $hallazgoUpdate = $request->all();
        $hallazgoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $hallazgo = Hallazgo::find($id);
        $hallazgo->update($hallazgoUpdate);

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

    public function compromiso($id_hallazgo) {

        $compromiso = Compromiso::getByIdHallazgo($id_hallazgo);

        $grid = \DataGrid::source($compromiso);
        //$grid->add('id_compromiso', 'ID')->style("width:80px");
        $grid->add('nombre_compromiso', 'Compromiso');
        $grid->add('plazo_comprometido', 'Plazo Comprometido');
        $grid->add('plazo_estimado', 'Plazo Estimado');
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumnCompromiso($value, $row);
        })->style("width:90px; text-align:center");
        $grid->row(function ($row) {


            if ($row->data->id_compromiso_padre > 0) {
                $row->style("font-weight:bold");
            }
        });

        return $grid;
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', 'compromiso-create')) {
            $btnShow = "<a href='compromiso/create/$row->id_hallazgo' class='btn btn-info btn-xs'>nuevo compromiso</a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_hallazgo/edit' class='btn btn-primary btn-xs' alt='Editar Hallazgo'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_hallazgo' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    public function setActionColumnCompromiso($value, $row) {

        $controller = "compromiso";
        $actionColumn = "";
        $url = url('/') . "/";
        if (auth()->user()->can('userAction', $controller . '-index')) {
            //$btnShow = "<a href='" . $url . $controller . "/$row->id_compromiso' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            //$actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $controller . '-update')) {
            $btneditar = "<a href='" . $url . $controller . "/$row->id_compromiso/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if ($row->id_compromiso_padre > 0) {
            $actionColumn .= "&nbsp;&nbsp;<div class = \"field-tooltip\"><i class = 'fa fa-info-circle' data-toggle = \"tooltip\" data-html=\"true\" title=\"Este compromiso fue generado a partir de un compromiso reprogramado\"></i></div>";
        }

        return $actionColumn;
    }

    public function verificaCuantidadHallazgo($id_proceso_auditado) {
        $proceso_auditado = ProcesoAuditado::find($id_proceso_auditado);
        $cuantidad_hallazgo = $proceso_auditado->cuantidad_hallazgo;
        $cuantidad_hallazgo_db = Hallazgo::getCuantidadHallazgoDb($id_proceso_auditado);

        if ($cuantidad_hallazgo == $cuantidad_hallazgo_db) {
            $proceso_auditado->fl_status = true;
            $proceso_auditado->save();
            unset($proceso_auditado);
        }
    }

}
