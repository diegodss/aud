<?php

namespace App\Http\Controllers;

use Crypt;
use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Subsecretaria;
use App\Ministerio;

class SubsecretariaController extends Controller {

    public function __construct() {

        $this->controller = "subsecretaria";
        $this->title = "Subsecretarias";
        $this->subtitle = "Gestion de subsecretarias";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Subsecretaria::with('ministerio'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_subsecretaria', 'ID', true)->style("width:80px");
        $grid->add('nombre_subsecretaria', 'Subsecretaria', true);
        $grid->add('ministerio.nombre_ministerio', 'Ministerio', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_subsecretaria', 'asc');
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

        return View::make('subsecretaria.index', $returnData);
    }

    public function create() {

        $subsecretaria = new Subsecretaria;
        $returnData['subsecretaria'] = $subsecretaria;

        $ministerio = Ministerio::active()->lists('nombre_ministerio', 'id_ministerio')->all();
        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nueva Subsecretaria";

        return View::make('subsecretaria.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_ministerio' => 'required',
            'nombre_subsecretaria' => 'required',
            'rut_completo' => 'required|unique:subsecretaria'
        ]);




        $subsecretaria = $request->all();
        $subsecretaria["fl_status"] = $request->exists('fl_status') ? true : false;
        $subsecretaria_new = Subsecretaria::create($subsecretaria);

        $subsecretaria["rut_completo"] = Crypt::encrypt($subsecretaria["rut_completo"]);

        $mensage_success = trans('message.saved.success');

        if ($subsecretaria["modal"] == "sim") {
            return $subsecretaria_new; //redirect()->route('subsecretaria.index')
        } else {/*
          return redirect()->route('subsecretaria.index')
          ->with('success', $mensage_success); */
            return $this->edit($subsecretaria_new->id_subsecretaria, true);
        }
        //
    }

    public function show($id) {

        $subsecretaria = Subsecretaria::find($id);
        $returnData['subsecretaria'] = $subsecretaria;

        $ministerio = Ministerio::active()->lists('nombre_ministerio', 'id_ministerio')->all();
        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Subsecretaria";
        return View::make('subsecretaria.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $subsecretaria = Subsecretaria::find($id);

        $subsecretaria->rut_completo = Crypt::decrypt($subsecretaria->rut_completo);


        $returnData['subsecretaria'] = $subsecretaria;

        $ministerio = Ministerio::active()->lists('nombre_ministerio', 'id_ministerio')->all();
        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Subsecretaria";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('subsecretaria.edit', $returnData);
        } else {
            return View::make('subsecretaria.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_ministerio' => 'required',
            'nombre_subsecretaria' => 'required',
            'rut_completo' => 'required|unique:subsecretaria'
        ]);

        $subsecretariaUpdate = $request->all();
        $subsecretariaUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $subsecretariaUpdate["rut_completo"] = Crypt::encrypt($subsecretariaUpdate["rut_completo"]);

        $subsecretaria = Subsecretaria::find($id);
        $subsecretaria->update($subsecretariaUpdate);



        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
        /*
          return redirect()->route('subsecretaria.index')
          ->with('success', $mensage_success); */
    }

    public function delete($id) {

        $subsecretaria = Subsecretaria::find($id);

        $returnData['subsecretaria'] = $subsecretaria;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Subsecretaria";
        return View::make('subsecretaria.delete', $returnData);
    }

    public function destroy($id) {
        Subsecretaria::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_subsecretaria' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_subsecretaria/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_subsecretaria' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    function ajaxSubsecretaria(Request $request) {

        $id_ministerio = $request->input('id_ministerio');
        $subsecretarias = Subsecretaria::where('id_ministerio', '=', $id_ministerio)->get();
        return $subsecretarias;
    }

}
