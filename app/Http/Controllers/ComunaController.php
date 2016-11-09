<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Comuna;
use App\Region;

class ComunaController extends Controller {

    public function __construct() {

        $this->controller = "comuna";
        $this->title = "Comunas";
        $this->subtitle = "Gestion de comunas";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Comuna::with('region'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_comuna', 'ID', true)->style("width:80px");
        $grid->add('nombre_comuna', 'Comuna', true);
        $grid->add('cod_comuna_deis', 'Codigo DEIS', true);
        $grid->add('region.nombre_region', 'Region', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_comuna', 'asc');
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

        return View::make('comuna.index', $returnData);
    }

    public function create() {

        $comuna = new Comuna;
        $returnData['comuna'] = $comuna;

        $region = Region::active()->lists('nombre_region', 'id_region')->all();
        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nueva Comuna";

        return View::make('comuna.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_comuna' => 'required',
            'cod_comuna_deis' => 'required',
            'id_region' => 'required',
        ]);

        $comuna = $request->all();
        $comuna["fl_status"] = $request->exists('fl_status') ? true : false;
        $comuna_new = Comuna::create($comuna);

        $mensage_success = trans('message.saved.success');

        if ($comuna["modal"] == "sim") {
            Log::info($comuna);
            return $comuna_new;
        } else {
            return $this->edit($comuna_new->id_comuna, true);
        }
    }

    public function show($id) {

        $comuna = Comuna::find($id);
        $returnData['comuna'] = $comuna;

        $region = Region::active()->lists('nombre_region', 'id_region')->all();
        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Comuna";
        return View::make('comuna.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $comuna = Comuna::find($id);
        $returnData['comuna'] = $comuna;

        $region = Region::active()->lists('nombre_region', 'id_region')->all();
        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Comuna";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('comuna.edit', $returnData);
        } else {
            return View::make('comuna.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_comuna' => 'required',
            'cod_comuna_deis' => 'required',
            'id_region' => 'required',
        ]);

        $comunaUpdate = $request->all();
        $comunaUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $comuna = Comuna::find($id);
        $comuna->update($comunaUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $comuna = Comuna::find($id);

        $returnData['comuna'] = $comuna;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Comuna";
        return View::make('comuna.delete', $returnData);
    }

    public function destroy($id) {
        Comuna::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_comuna' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_comuna/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_comuna' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
