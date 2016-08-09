<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\CentroResponsabilidad;
use App\Subsecretaria;

class GabineteController extends Controller {

    public function __construct() {

        $this->controller = "gabinete";
        $this->title = "Gabinetes";
        $this->subtitle = "Gestion de gabinetes";

        //$this->middleware('auth');
        //$this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $gabinete = CentroResponsabilidad::gabinete();

        $filter = \DataFilter::source($gabinete);
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_centro_responsabilidad', 'ID', true)->style("width:80px");
        $grid->add('nombre_centro_responsabilidad', 'CentroResponsabilidad', true);
        $grid->add('nombre_jefatura', 'Ministro', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_centro_responsabilidad', 'asc');
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

        return View::make('centro_responsabilidad.index', $returnData);
    }

    public function create() {

        $centro_responsabilidad = new CentroResponsabilidad;
        $returnData['centro_responsabilidad'] = $centro_responsabilidad;

        $subsecretaria = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();
        $returnData['subsecretaria'] = $subsecretaria;

        $returnData['lbl_nombre_centro_responsabilidad'] = "Gabienete";

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Gabinete";

        return View::make('gabinete.create', $returnData);
    }

    public function store(Request

    $request) {
        $this->validate($request, [
            'id_subsecretaria' => 'required',
            'nombre_centro_responsabilidad' => 'required',
            'nombre_jefatura' => 'required'
        ]);

        $centro_responsabilidad = $request->all();
        $centro_responsabilidad["fl_status"] = $request->exists('fl_status') ? true : false;
        $centro_responsabilidad_new = CentroResponsabilidad::create($centro_responsabilidad);

        $mensage_success = trans('message.saved.success');

        if ($centro_responsabilidad["modal"] == "sim") {
            Log::info($centro_responsabilidad);
            return $centro_responsabilidad_new; //redirect()->route('gabinete.index')
        } else {/*
          return redirect()->route('gabinete.index')
          ->with('success', $mensage_success); */
            return $this->edit($centro_responsabilidad_new->id_centro_responsabilidad, true);
        }
        //f
    }

    public function show($id) {

        $centro_responsabilidad = CentroResponsabilidad::find($id);
        $returnData['centro_responsabilidad'] = $centro_responsabilidad;

        $subsecretaria = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();
        $returnData['subsecretaria'] = $subsecretaria;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar CentroResponsabilidad";
        return View::make('gabinete.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $centro_responsabilidad = CentroResponsabilidad::find($id);
        $returnData['centro_responsabilidad'] = $centro_responsabilidad;

        $subsecretaria = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();
        $returnData['subsecretaria'] = $subsecretaria;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar CentroResponsabilidad";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('gabinete.edit', $returnData);
        } else {
            return View::make('gabinete.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_centro_responsabilidad' => 'required',
            'nombre_jefatura' => 'required',
        ]);

        $centro_responsabilidadUpdate = $request->all();
        $centro_responsabilidadUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $centro_responsabilidad = CentroResponsabilidad::find($id);
        $centro_responsabilidad->update($centro_responsabilidadUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('gabinete.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $centro_responsabilidad = CentroResponsabilidad::find($id);

        $returnData['centro_responsabilidad'] = $centro_responsabilidad;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar CentroResponsabilidad";
        return View::make('gabinete.delete', $returnData);
    }

    public function destroy($id) {
        CentroResponsabilidad::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href = '" . $this->controller . "/$row->id_centro_responsabilidad' class = 'btn btn-info btn-xs'><i class = 'fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href = '" . $this->controller . "/$row->id_centro_responsabilidad/edit' class = 'btn btn-primary btn-xs'><i class = 'fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href = '" . $this->controller . "/delete/$row->id_centro_responsabilidad' class = 'btn btn-danger btn-xs'> <i class = 'fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn











        ;
    }

}
