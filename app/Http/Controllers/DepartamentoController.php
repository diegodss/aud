<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Departamento;
use App\CentroResponsabilidad;
use App\Establecimiento;

class DepartamentoController extends Controller {

    public function __construct() {

        $this->controller = "departamento";
        $this->title = "Departamentos";
        $this->subtitle = "Gestion de departamentos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\Departamento); // (Departamento::with('nombre_departamento'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_departamento', 'ID', true)->style("width:80px");
        $grid->add('nombre_departamento', 'Departamento', true);
        $grid->add('centro_responsabilidad.nombre_centro_responsabilidad', 'Centro Responsabilidad', true);
        $grid->add('establecimiento.nombre_establecimiento', 'Establecimiento', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_departamento', 'asc');
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

        return View::make('departamento.index', $returnData);
    }

    public function create() {

        $departamento = new Departamento;
        $returnData['departamento'] = $departamento;

        $centro_responsabilidad = CentroResponsabilidad::active()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['centro_responsabilidad'] = $centro_responsabilidad;

        $establecimiento = Establecimiento::active()->lists('nombre_establecimiento', 'id_establecimiento')->all();
        $returnData['establecimiento'] = $establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Departamento";

        return View::make('departamento.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_centro_responsabilidad' => 'required',
            'nombre_departamento' => 'required',
            'id_establecimiento' => 'required'
        ]);

        $departamento = $request->all();
        $departamento["fl_status"] = $request->exists('fl_status') ? true : false;
        $departamento_new = Departamento::create($departamento);

        $mensage_success = trans('message.saved.success');

        if ($departamento["modal"] == "sim") {
            Log::info($departamento);
            return $departamento_new; //redirect()->route('departamento.index')
        } else {/*
          return redirect()->route('departamento.index')
          ->with('success', $mensage_success); */
            return $this->edit($departamento_new->id_departamento, true);
        }
        //
    }

    public function show($id) {

        $departamento = Departamento::find($id);
        $returnData['departamento'] = $departamento;

        $centro_responsabilidad = CentroResponsabilidad::active()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['centro_responsabilidad'] = $centro_responsabilidad;

        $establecimiento = Establecimiento::active()->lists('nombre_establecimiento', 'id_establecimiento')->all();
        $returnData['establecimiento'] = $establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Departamento";
        return View::make('departamento.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $departamento = Departamento::find($id);
        $returnData['departamento'] = $departamento;

        $centro_responsabilidad = CentroResponsabilidad::active()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['centro_responsabilidad'] = $centro_responsabilidad;

        $establecimiento = Establecimiento::active()->lists('nombre_establecimiento', 'id_establecimiento')->all();
        $returnData['establecimiento'] = $establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Departamento";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('departamento.edit', $returnData);
        } else {
            return View::make('departamento.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_centro_responsabilidad' => 'required',
            'nombre_departamento' => 'required',
            'id_establecimiento' => 'required'
        ]);

        $departamentoUpdate = $request->all();
        $departamentoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $departamento = Departamento::find($id);
        $departamento->update($departamentoUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $departamento = Departamento::find($id);

        $returnData['departamento'] = $departamento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Departamento";
        return View::make('departamento.delete', $returnData);
    }

    public function destroy($id) {
        Departamento::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_departamento' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_departamento/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_departamento' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
