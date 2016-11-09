<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ministerio;
use App\Organismo;

class OrganismoController extends Controller {

    public function __construct() {

        $this->controller = "organismo";
        $this->title = "Organismos";
        $this->subtitle = "Gestion de organismos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Organismo::with('ministerio')); //::with('ministerio')); // (Organismo::with('nombre_organismo'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_organismo', 'ID', true)->style("width:80px");
        $grid->add('nombre_organismo', 'Organismo', true);
        $grid->add('ministerio.nombre_ministerio', 'ministerio', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_organismo', 'asc');
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

        return View::make('organismo.index', $returnData);
    }

    public function create() {

        $ministerio = Ministerio::active()->lists('nombre_ministerio', 'id_ministerio');
        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('organismo.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_organismo' => 'required',
        ]);

        $organismo = $request->all();
        $organismo["fl_status"] = $request->exists('fl_status') ? true : false;
        $organismo_new = Organismo::create($organismo);

        $mensage_success = trans('message.saved.success');
        //return redirect($this->controller);
        return redirect()->route('organismo.index')
                        ->with('success', $mensage_success);
    }

    public function show($id) {

        $organismo = Organismo::find($id);

        $returnData['organismo'] = $organismo;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('organismo.show', $returnData);
    }

    public function edit($id) {

        $organismo = Organismo::find($id);
        $ministerio = Ministerio::active()->lists('nombre_ministerio', 'id_ministerio');

        $returnData['organismo'] = $organismo;
        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('organismo.edit', $returnData);
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_organismo' => 'required',
        ]);

        $organismoUpdate = $request->all();
        $organismoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;

        //Log::info($organismoUpdate);

        $organismo = Organismo::find($id);
        $organismo->update($organismoUpdate);

        $mensage_success = trans('message.saved.success');
        return redirect()->route('organismo.index')
                        ->with('success', $mensage_success);
    }

    public function delete($id) {

        $organismo = Organismo::find($id);

        $returnData['organismo'] = $organismo;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;

        return View::make('organismo.delete', $returnData);
    }

    public function destroy($id) {
        Organismo::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_organismo' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_organismo/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_organismo' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    function ajaxOrganismo(Request $request) {

        $id_ministerio = $request->input('id_ministerio');
        $organismos = Organismo::where('id_ministerio', '=', $id_ministerio)->get();
        return $organismos;
    }

}
