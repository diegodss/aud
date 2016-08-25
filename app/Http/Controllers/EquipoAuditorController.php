<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\EquipoAuditor;
use App\Auditor;
use App\RelAuditorEquipo;

class EquipoAuditorController extends Controller {

    public function __construct() {

        $this->controller = "equipo_auditor";
        $this->title = "Equipo de Auditores";
        $this->subtitle = "Gestion de equipo de auditores";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function storeAuditor($id_equipo_auditor, $id_auditor) {

        $auditores = EquipoAuditor::getAuditorById($id_equipo_auditor)->get();

        $relAuditorEquipo = new RelAuditorEquipo();
        $relAuditorEquipo->id_equipo_auditor = $id_equipo_auditor;
        $relAuditorEquipo->id_auditor = $id_auditor;
        if (count($auditores) == 0) {
            $relAuditorEquipo->jefatura_equipo = true;
        }
        $relAuditorEquipo->save();
    }

    public function gridAjaxAuditorEquipo($id) {

        $auditores = EquipoAuditor::getAuditorById($id);

        if (count($auditores->get()) == 0) {
            $grid = "<div class='alert alert-warning'>
                    <h4><i class='icon fa fa-warning'></i> Atención</h4>
                    El primero auditor debe ser el lider del equipo.
                    </div>";
        } else {
            $grid = \DataGrid::source($auditores);
            $grid->add('id_auditor', 'ID')->style("width:40px");
            $grid->add('nombre_auditor', 'Auditor');
            $grid->add('jefatura_equipo', 'Lider')->cell(function( $value, $row ) {
                return $row->jefatura_equipo ? "<small class='label bg-green'>lider</small>" : "";
            });
        }
        return $grid;
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\EquipoAuditor); // (EquipoAuditor::with('nombre_equipo_auditor'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_equipo_auditor', 'ID', true)->style("width:80px");
        $grid->add('nombre_equipo_auditor', 'Equipo de Auditor', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_equipo_auditor', 'asc');
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

        return View::make('equipo_auditor.index', $returnData);
    }

    public function create() {

        $equipo_auditor = new EquipoAuditor;
        $returnData['equipo_auditor'] = $equipo_auditor;

        $auditor = Auditor::active()->lists('nombre_auditor', 'id_auditor')->all();
        $returnData['auditor'] = $auditor;


        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Equipo de Auditores";

        return View::make('equipo_auditor.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_equipo_auditor' => 'required'
        ]);

        $equipo_auditor = $request->all();
        $equipo_auditor["fl_status"] = $request->exists('fl_status') ? true : false;
        $equipo_auditor_new = EquipoAuditor::create($equipo_auditor);

        $mensage_success = trans('message.saved.success');

        if ($equipo_auditor["modal"] == "sim") {
            Log::info($equipo_auditor);
            return $equipo_auditor_new; //redirect()->route('equipo_auditor.index')
        } else {/*
          return redirect()->route('equipo_auditor.index')
          ->with('success', $mensage_success); */
            return $this->edit($equipo_auditor_new->id_equipo_auditor, true);
        }
        //
    }

    public function show($id) {

        $equipo_auditor = EquipoAuditor::find($id);

        $returnData['equipo_auditor'] = $equipo_auditor;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar EquipoAuditor";
        return View::make('equipo_auditor.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $equipo_auditor = EquipoAuditor::find($id);
        $returnData['equipo_auditor'] = $equipo_auditor;

        $auditor = Auditor::active()->lists('nombre_auditor', 'id_auditor')->all();
        $returnData['auditor'] = $auditor;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar EquipoAuditor";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('equipo_auditor.edit', $returnData);
        } else {
            return View::make('equipo_auditor.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_equipo_auditor' => 'required'
        ]);

        $equipo_auditorUpdate = $request->all();
        $equipo_auditorUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $equipo_auditor = EquipoAuditor::find($id);
        $equipo_auditor->update($equipo_auditorUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('equipo_auditor.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $equipo_auditor = EquipoAuditor::find($id);

        $returnData['equipo_auditor'] = $equipo_auditor;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar EquipoAuditor";
        return View::make('equipo_auditor.delete', $returnData);
    }

    public function destroy($id) {
        EquipoAuditor::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_equipo_auditor' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_equipo_auditor/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_equipo_auditor' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
