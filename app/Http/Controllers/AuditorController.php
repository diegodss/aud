<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Auditor;

use App\Ministerio;

class AuditorController extends Controller {

    public function __construct() {

        $this->controller = "auditor";
        $this->title = "Auditores";
        $this->subtitle = "Gestion de auditores";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\Auditor); // (Auditor::with('nombre_auditor'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_auditor', 'ID', true)->style("width:80px");
        $grid->add('nombre_auditor', 'Auditor', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_auditor', 'asc');
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

        return View::make('auditor.index', $returnData);
    }

    public function create() {

        $auditor = new Auditor;
        $returnData['auditor'] = $auditor;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Auditor";

        return View::make('auditor.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_auditor' => 'required',
			'rut_completo' => 'required'
        ]);

        $auditor = $request->all();
        $auditor["fl_status"] = $request->exists('fl_status') ? true : false;
        $auditor_new = Auditor::create($auditor);

        $mensage_success = trans('message.saved.success');

        if ($auditor["modal"] == "sim") {
            Log::info($auditor);
            return $auditor_new; //redirect()->route('auditor.index')
        } else {/*
          return redirect()->route('auditor.index')
          ->with('success', $mensage_success); */
            return $this->edit($auditor_new->id_auditor, true);
        }
        //
    }

    public function show($id) {

        $auditor = Auditor::find($id);
        $returnData['auditor'] = $auditor;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Auditor";
        return View::make('auditor.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $auditor = Auditor::find($id);
        $returnData['auditor'] = $auditor;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Auditor";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('auditor.edit', $returnData);
        } else {
            return View::make('auditor.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_auditor' => 'required',
			'rut_completo' => 'required'
        ]);

        $auditorUpdate = $request->all();
        $auditorUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $auditor = Auditor::find($id);
        $auditor->update($auditorUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('auditor.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $auditor = Auditor::find($id);

        $returnData['auditor'] = $auditor;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Auditor";
        return View::make('auditor.delete', $returnData);
    }

    public function destroy($id) {
        Auditor::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_auditor' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_auditor/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_auditor' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
