<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\ServicioSalud;
use App\Subsecretaria;

class ServicioSaludController extends Controller {

    public function __construct() {

        $this->controller = "servicio_salud";
        $this->title = "Servicios de Salud";
        $this->subtitle = "Gestion de servicios de salud";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(ServicioSalud::with('subsecretaria'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_servicio_salud', 'ID', true)->style("width:80px");
        $grid->add('nombre_servicio', 'Servicio de Salud', true);
        $grid->add('subsecretaria.nombre_subsecretaria', 'Subsecretaria', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_servicio_salud', 'asc');
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

        return View::make('servicio_salud.index', $returnData);
    }

    public function create() {

        $servicio_salud = new ServicioSalud;
        $returnData['servicio_salud'] = $servicio_salud;

        $subsecretaria = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();
        $returnData['subsecretaria'] = $subsecretaria;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Servicio de Salud";

        return View::make('servicio_salud.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_subsecretaria' => 'required',
            'nombre_servicio' => 'required',
            'rut_completo' => 'required'
        ]);

        $servicio_salud = $request->all();
        $servicio_salud["fl_status"] = $request->exists('fl_status') ? true : false;
        $servicio_salud_new = ServicioSalud::create($servicio_salud);

        $mensage_success = trans('message.saved.success');

        if ($servicio_salud["modal"] == "sim") {
            return $servicio_salud_new;
        } else {
            return $this->edit($servicio_salud_new->id_servicio_salud, true);
        }
    }

    public function show($id) {

        $servicio_salud = ServicioSalud::find($id);
        $returnData['servicio_salud'] = $servicio_salud;

        $subsecretaria = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();
        $returnData['subsecretaria'] = $subsecretaria;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar ServicioSalud";
        return View::make('servicio_salud.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $servicio_salud = ServicioSalud::find($id);
        $returnData['servicio_salud'] = $servicio_salud;

        $subsecretaria = Subsecretaria::active()->lists('nombre_subsecretaria', 'id_subsecretaria')->all();
        $returnData['subsecretaria'] = $subsecretaria;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Servicio de Salud";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('servicio_salud.edit', $returnData);
        } else {
            return View::make('servicio_salud.edit', $returnData)->withSuccess($mensage_success);
        }
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_subsecretaria' => 'required',
            'nombre_servicio' => 'required',
            'rut_completo' => 'required'
        ]);

        $servicio_saludUpdate = $request->all();
        $servicio_saludUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $servicio_salud = ServicioSalud::find($id);
        $servicio_salud->update($servicio_saludUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $servicio_salud = ServicioSalud::find($id);

        $returnData['servicio_salud'] = $servicio_salud;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Servicio de Salud";
        return View::make('servicio_salud.delete', $returnData);
    }

    public function destroy($id) {
        ServicioSalud::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_servicio_salud' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_servicio_salud/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_servicio_salud' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
