<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Unidad;
use App\Departamento;
use App\ServicioClinico;

class UnidadController extends Controller {

    public function __construct() {

        $this->controller = "unidad";
        $this->title = "Unidad";
        $this->subtitle = "Gestion de unidads";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Unidad::with('departamento'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_unidad', 'ID', true)->style("width:80px");
        $grid->add('nombre_unidad', 'Unidad', true);
        $grid->add('departamento.nombre_departamento', 'Departamento', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_unidad', 'asc');
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

        return View::make('unidad.index', $returnData);
    }

    public function create() {

        $unidad = new Unidad;
        $returnData['unidad'] = $unidad;

        $departamento = Departamento::active()->lists('nombre_departamento', 'id_departamento')->all();
        $returnData['departamento'] = $departamento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nueva Unidad";

        return View::make('unidad.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_departamento' => 'required',
            'nombre_unidad' => 'required'
        ]);

        $unidad = $request->all();
        $unidad["fl_status"] = $request->exists('fl_status') ? true : false;
        $unidad_new = Unidad::create($unidad);

        $mensage_success = trans('message.saved.success');

        if ($unidad["modal"] == "sim") {
            return $unidad_new; //redirect()->route('unidad.index')
        } else {/*
          return redirect()->route('unidad.index')
          ->with('success', $mensage_success); */
            return $this->edit($unidad_new->id_unidad, true);
        }
        //
    }

    public function show($id) {

        $unidad = Unidad::find($id);
        $returnData['unidad'] = $unidad;

        $departamento = Departamento::active()->lists('nombre_departamento', 'id_departamento')->all();
        $returnData['departamento'] = $departamento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Unidad";
        return View::make('unidad.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $unidad = Unidad::find($id);
        $returnData['unidad'] = $unidad;

        $departamento = Departamento::active()->lists('nombre_departamento', 'id_departamento')->all();
        $returnData['departamento'] = $departamento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Unidad";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('unidad.edit', $returnData);
        } else {
            return View::make('unidad.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_departamento' => 'required',
            'nombre_unidad' => 'required'
        ]);

        $unidadUpdate = $request->all();
        $unidadUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $unidad = Unidad::find($id);
        $unidad->update($unidadUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $unidad = Unidad::find($id);

        $returnData['unidad'] = $unidad;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Unidad";
        return View::make('unidad.delete', $returnData);
    }

    public function destroy($id) {
        Unidad::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_unidad' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_unidad/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_unidad' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    function ajaxUnidad(Request $request) {

        $id_departamento = $request->input('id_departamento');
        $unidad = Unidad::where('id_departamento', '=', $id_departamento)->get();
        return $unidad;
    }

}
