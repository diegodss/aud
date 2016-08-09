<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Proceso;

class ProcesoController extends Controller {

    public function __construct() {

        $this->controller = "proceso";
        $this->title = "Procesos";
        $this->subtitle = "Gestion de procesos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\Proceso); // (Proceso::with('nombre_proceso'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_proceso', 'ID', true)->style("width:80px");
        $grid->add('nombre_proceso', 'Proceso', true);
        $grid->add('responsable_proceso', 'Responsable', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_proceso', 'asc');
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

        return View::make('proceso.index', $returnData);
    }

    public function create() {

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Proceso";

        return View::make('proceso.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_proceso' => 'required',
            'responsable_proceso' => 'required',
        ]);

        $proceso = $request->all();
        $proceso["fl_status"] = $request->exists('fl_status') ? true : false;
        $proceso_new = Proceso::create($proceso);

        $mensage_success = trans('message.saved.success');

        if ($proceso["modal"] == "sim") {
            Log::info($proceso);
            return $proceso_new; //redirect()->route('proceso.index')
        } else {/*
          return redirect()->route('proceso.index')
          ->with('success', $mensage_success); */
            return $this->edit($proceso_new->id_proceso, true);
        }
        //
    }

    public function show($id) {

        $proceso = Proceso::find($id);

        $returnData['proceso'] = $proceso;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Proceso";
        return View::make('proceso.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $proceso = Proceso::find($id);

        $returnData['proceso'] = $proceso;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Proceso";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('proceso.edit', $returnData);
        } else {
            return View::make('proceso.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_proceso' => 'required',
            'responsable_proceso' => 'required',
        ]);

        $procesoUpdate = $request->all();
        $procesoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $proceso = Proceso::find($id);
        $proceso->update($procesoUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('proceso.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $proceso = Proceso::find($id);

        $returnData['proceso'] = $proceso;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Proceso";
        return View::make('proceso.delete', $returnData);
    }

    public function destroy($id) {
        Proceso::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_proceso' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_proceso/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_proceso' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
