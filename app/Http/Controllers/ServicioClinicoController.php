<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\ServicioClinico;
use App\CentroResponsabilidad;
use App\Establecimiento;

class ServicioClinicoController extends Controller {

    public function __construct() {

        $this->controller = "servicio_clinico";
        $this->title = "Servicios Clinicos";
        $this->subtitle = "Gestion de servicios clinicos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\ServicioClinico); // (ServicioClinico::with('nombre_servicio_clinico'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_servicio_clinico', 'ID', true)->style("width:80px");
        $grid->add('nombre_servicio_clinico', 'Servicios Clinicos', true);
        $grid->add('establecimiento.nombre_establecimiento', 'Establecimiento', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_servicio_clinico', 'asc');
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

        return View::make('servicio_clinico.index', $returnData);
    }

    public function create() {

        $servicio_clinico = new ServicioClinico;
        $returnData['servicio_clinico'] = $servicio_clinico;

        $establecimiento = Establecimiento::active()->lists('nombre_establecimiento', 'id_establecimiento')->all();
        $returnData['establecimiento'] = $establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Servicio Clinico";

        return View::make('servicio_clinico.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_servicio_clinico' => 'required',
            'id_establecimiento' => 'required'
        ]);

        $servicio_clinico = $request->all();
        $servicio_clinico["fl_status"] = $request->exists('fl_status') ? true : false;
        $servicio_clinico_new = ServicioClinico::create($servicio_clinico);

        $mensage_success = trans('message.saved.success');

        if ($servicio_clinico["modal"] == "sim") {
            Log::info($servicio_clinico);
            return $servicio_clinico_new;
        } else {
            return $this->edit($servicio_clinico_new->id_servicio_clinico, true);
        }
    }

    public function show($id) {

        $servicio_clinico = ServicioClinico::find($id);
        $returnData['servicio_clinico'] = $servicio_clinico;

        $establecimiento = Establecimiento::active()->lists('nombre_establecimiento', 'id_establecimiento')->all();
        $returnData['establecimiento'] = $establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Servicio Clinico";
        return View::make('servicio_clinico.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $servicio_clinico = ServicioClinico::find($id);
        $returnData['servicio_clinico'] = $servicio_clinico;

        $establecimiento = Establecimiento::active()->lists('nombre_establecimiento', 'id_establecimiento')->all();
        $returnData['establecimiento'] = $establecimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar ServicioClinico";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('servicio_clinico.edit', $returnData);
        } else {
            return View::make('servicio_clinico.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_servicio_clinico' => 'required',
            'id_establecimiento' => 'required'
        ]);

        $servicio_clinicoUpdate = $request->all();
        $servicio_clinicoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $servicio_clinico = ServicioClinico::find($id);
        $servicio_clinico->update($servicio_clinicoUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $servicio_clinico = ServicioClinico::find($id);
        $returnData['servicio_clinico'] = $servicio_clinico;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar ServicioClinico";
        return View::make('servicio_clinico.delete', $returnData);
    }

    public function destroy($id) {
        ServicioClinico::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_servicio_clinico' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_servicio_clinico/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_servicio_clinico' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
