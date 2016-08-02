<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Ministerio;

class MinisterioController extends Controller {

    public function __construct() {

        $this->controller = "ministerio";
        $this->title = "Ministerios";
        $this->subtitle = "Gestion de ministerios";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new \App\Ministerio); // (Ministerio::with('nombre_ministerio'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_ministerio', 'ID', true)->style("width:80px");
        $grid->add('nombre_ministerio', 'Ministerio', true);
        $grid->add('nombre_ministro', 'Ministro', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_ministerio', 'asc');
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

        return View::make('ministerio.index', $returnData);
    }

    public function create() {

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Ministerio";

        return View::make('ministerio.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'nombre_ministerio' => 'required',
            'nombre_ministro' => 'required',
        ]);

        $ministerio = $request->all();
        $ministerio["fl_status"] = $request->exists('fl_status') ? true : false;
        $ministerio_new = Ministerio::create($ministerio);

        $mensage_success = trans('message.saved.success');

        if ($ministerio["modal"] == "sim") {
            Log::info($ministerio);
            return $ministerio_new; //redirect()->route('ministerio.index')
        } else {/*
          return redirect()->route('ministerio.index')
          ->with('success', $mensage_success); */
            return $this->edit($ministerio_new->id_ministerio, true);
        }
        //
    }

    public function show($id) {

        $ministerio = Ministerio::find($id);

        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Ministerio";
        return View::make('ministerio.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $ministerio = Ministerio::find($id);

        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Ministerio";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('ministerio.edit', $returnData);
        } else {
            return View::make('ministerio.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'nombre_ministerio' => 'required',
            'nombre_ministro' => 'required',
        ]);

        $ministerioUpdate = $request->all();
        $ministerioUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $ministerio = Ministerio::find($id);
        $ministerio->update($ministerioUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('ministerio.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $ministerio = Ministerio::find($id);

        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Ministerio";
        return View::make('ministerio.delete', $returnData);
    }

    public function destroy($id) {
        Ministerio::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_ministerio' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_ministerio/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_ministerio' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
