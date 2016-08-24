<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Compromiso;
use App\Hallazgo;
use App\Seguimiento;
use App\MedioVerificacion;

class CompromisoController extends Controller {

    public function __construct() {

        $this->controller = "compromiso";
        $this->title = "Compromisos";
        $this->subtitle = "Gestion de compromisos";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(Compromiso::with('hallazgo'));
        $filter->text('src', 'Búsqueda')->scope('freesearch');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_compromiso', 'ID', true)->style("width:80px");
        $grid->add('hallazgo.nombre_hallazgo', 'Hallazgo', true);
        $grid->add('nombre_compromiso', 'Compromiso', true);
        $grid->add('fl_status', 'Activo')->cell(function( $value, $row ) {
            return $row->fl_status ? "Sí" : "No";
        });
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_compromiso', 'asc');
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

        return View::make('compromiso.index', $returnData);
    }

    public function create() {

        $compromiso = new Compromiso;
        $returnData['compromiso'] = $compromiso;

        $hallazgo = Hallazgo::active()->lists('nombre_hallazgo', 'id_hallazgo')->all();
        $returnData['hallazgo'] = $hallazgo;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Compromiso";

        return View::make('compromiso.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_hallazgo' => 'required'
            , 'plazo_estimado' => 'required'
            , 'plazo_comprometido' => 'required'
            , 'nombre_compromiso' => 'required'
            , 'responsable' => 'required'
        ]);

        $compromiso = $request->all();
        $compromiso["fl_status"] = $request->exists('fl_status') ? true : false;
        $compromiso_new = Compromiso::create($compromiso);

        $mensage_success = trans('message.saved.success');

        if ($compromiso["modal"] == "sim") {
            Log::info($compromiso);
            return $compromiso_new; //redirect()->route('compromiso.index')
        } else {/*
          return redirect()->route('compromiso.index')
          ->with('success', $mensage_success); */
            return $this->edit($compromiso_new->id_compromiso, true);
        }
        //
    }

    public function show($id) {

        $compromiso = Compromiso::find($id);
        $returnData['compromiso'] = $compromiso;

        $hallazgo = Hallazgo::active()->lists('nombre_hallazgo', 'id_hallazgo')->all();
        $returnData['hallazgo'] = $hallazgo;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Compromiso";
        return View::make('compromiso.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $compromiso = Compromiso::find($id);
        $returnData['compromiso'] = $compromiso;

        $hallazgo = Hallazgo::active()->lists('nombre_hallazgo', 'id_hallazgo')->all();
        $returnData['hallazgo'] = $hallazgo;

        $returnData['medio_verificacion'] = $this->medio_verificacion($id);
        $returnData['seguimiento'] = $this->seguimiento($id);


        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Compromiso";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('compromiso.edit', $returnData);
        } else {
            return View::make('compromiso.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_hallazgo' => 'required'
            , 'plazo_estimado' => 'required'
            , 'plazo_comprometido' => 'required'
            , 'nombre_compromiso' => 'required'
            , 'responsable' => 'required'
        ]);


        $compromisoUpdate = $request->all();
        $compromisoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $compromiso = Compromiso::find($id);
        $compromiso->update($compromisoUpdate);

        $mensage_success = trans('message.saved.success');

        return $this->edit($id, true);
    }

    public function delete($id) {

        $compromiso = Compromiso::find($id);

        $returnData['compromiso'] = $compromiso;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Compromiso";
        return View::make('compromiso.delete', $returnData);
    }

    public function destroy($id) {
        Compromiso::find($id)->delete();
        return redirect($this->controller);
    }

    public function medio_verificacion($id_compromiso) {

        $medio_verificacion = MedioVerificacion::getByIdCompromiso($id_compromiso);

        $grid = \DataGrid::source($medio_verificacion);
        $grid->add('id_medio_verificacion', 'ID')->style("width:80px");
        $grid->add('descripcion', 'Medio de Verificacion');
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumnMedioVerificacion($value, $row);
        })->style("width:90px; text-align:center");

        //$returnData['grid_medio_verificacion'] = $grid;
        return $grid;
    }

    public function seguimiento($id_compromiso) {

        $seguimiento = Seguimiento::getByIdCompromiso($id_compromiso);

        $grid = \DataGrid::source($seguimiento);
        $grid->add('id_seguimiento', 'ID')->style("width:80px");
        $grid->add('porcentaje_avance', 'porcentaje_avance');
        $grid->add('estado', 'estado');
        $grid->add('condicion', 'condicion');

        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumnSeguimiento($value, $row);
        })->style("width:90px; text-align:center");

        //$returnData['grid_seguimiento'] = $grid;
        return $grid;
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href='" . $this->controller . "/$row->id_compromiso' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_compromiso/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_compromiso' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    public function setActionColumnSeguimiento($value, $row) {

        $controller = "seguimiento";
        $actionColumn = "";
        $url = url('/') . "/";
        if (auth()->user()->can('userAction', $controller . '-index')) {
            $btnShow = "<a href='" . $url . $controller . "/$row->id_seguimiento' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $controller . '-update')) {
            $btneditar = "<a href='" . $url . $controller . "/$row->id_seguimiento/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        return $actionColumn;
    }

    public function setActionColumnMedioVerificacion($value, $row) {

        $controller = "medio_verificacion";
        $actionColumn = "";
        $url = url('/') . "/";
        if (auth()->user()->can('userAction', $controller . '-index')) {
            $btnShow = "<a href='" . $url . $controller . "/$row->id_medio_verificacion' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $controller . '-update')) {
            $btneditar = "<a href='" . $url . $controller . "/$row->id_medio_verificacion/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        return $actionColumn;
    }

    function ajaxCompromiso(Request $request) {

        $id_hallazgo = $request->input('id_hallazgo');
        $compromiso = Compromiso::where('id_hallazgo', '=', $id_hallazgo)->get();
        return $compromiso;
    }

}
