<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\PlanillaSeguimiento;

class PlanillaSeguimientoController extends Controller {

    public function __construct() {

        $this->controller = "planilla_seguimiento";
        $this->title = "Planilla de Seguimiento";
        $this->subtitle = "Reporteria";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\PlanillaSeguimiento); // (Region::with('nombre_region'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_proceso_auditado', 'ID')->style("width:80px");
        $grid->add('nomenclatura', 'nomenclatura')->style("width:80px");
        $grid->add('ano', 'ano')->style("width:80px");
        $grid->add('area_auditada', 'area_auditada')->style("width:80px");



        $grid->orderBy('id_proceso_auditado', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('planilla_seguimiento.index', $returnData);
    }

    public function create() {

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nueva Region";

        return View::make('region.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_region' => 'required'
        ]);

        $region = $request->all();
        $region["fl_status"] = $request->exists('fl_status') ? true : false;
        $region_new = Region::create($region);

        $mensage_success = trans('message.saved.success');

        if ($region["modal"] == "sim") {
            Log::info($region);
            return $region_new; //redirect()->route('region.index')
        } else {/*
          return redirect()->route('region.index')
          ->with('success', $mensage_success); */
            return $this->edit($region_new->id_region, true);
        }
        //
    }

    public function show($id) {

        $region = Region::find($id);

        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Region";
        return View::make('region.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $region = Region::find($id);

        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Region";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('region.edit', $returnData);
        } else {
            return View::make('region.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_region' => 'required'
        ]);

        $regionUpdate = $request->all();
        $regionUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $region = Region::find($id);
        $region->update($regionUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('region.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $region = Region::find($id);

        $returnData['region'] = $region;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Region";
        return View::make('region.delete', $returnData);
    }

    public function destroy($id) {
        Region::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_region' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_region/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_region' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
