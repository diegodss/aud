<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Seguimiento;
use App\Compromiso;
use App\MedioVerificacion;
use File;
use Mail;

class SeguimientoController extends Controller {

    public function __construct() {

        $this->controller = "seguimiento";
        $this->title = "Seguimientos";
        $this->subtitle = "Gestion de seguimientos";

        $this->fechaActual = date("d") . "-" . date("m") . "-" . date("Y");
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $seguimiento = Seguimiento::compromisoHallazgoProcesoAuditado();

        $filter = \DataFilter::source($seguimiento);
        $filter->add('numero_informe', 'Nº Informe', 'text')->clause('where')->operator('=');
        $filter->add('numero_informe_unidad', 'Unidad', 'text')->clause('where')->operator('=');
        $filter->add('ano', 'Año', 'text')->clause('where')->operator('=');
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('numero_informe', 'nº', true)->style("width:80px")->cell(function( $value, $row ) {
            return $row->numero_informe . " " . $row->numero_informe_unidad;
        });
        $grid->add('nombre_proceso_auditado', 'Proceso');
        $grid->add('nombre_hallazgo', 'Hallazgo');
        $grid->add('nombre_compromiso', 'Compromiso');

        $grid->add('estado', 'Estado');
        $grid->add('condicion', 'Condicion');
        $grid->add('porcentaje_avance', '%');
        $grid->add('plazo_comprometido', 'Plazo Comprometido');
//$grid->add('plazo_estimado', 'Plazo Estimado', true);

        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_seguimiento', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('seguimiento.index', $returnData);
    }

    public function create($id_compromiso) {

        $proceso_auditado = \App\ProcesoAuditado::find(Compromiso::getIdProcesoAuditado($id_compromiso));
        $returnData['proceso_auditado'] = $proceso_auditado;

        $seguimiento = new Seguimiento;
        $seguimiento->id_compromiso = $id_compromiso;
        $returnData['seguimiento'] = $seguimiento;

        $compromiso = Compromiso::find($id_compromiso);
        $returnData['compromiso'] = $compromiso;

        $seguimiento->diferencia_tiempo = dateDifference($compromiso->plazo_comprometido, $this->fechaActual);
        $diferencia_tiempo_tooltip = "Plazo Comprometido: " . $compromiso->plazo_comprometido . ". <br> Fecha Actual: " . $this->fechaActual;
        $returnData["diferencia_tiempo_tooltip"] = $diferencia_tiempo_tooltip;

        $medio_verificacion = $this->medio_verificacion($seguimiento->id_compromiso);
        $returnData['medio_verificacion'] = $medio_verificacion;

        $returnData['estado'] = config('collection.estado');
        $returnData['condicion'] = config('collection.condicion');

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Seguimiento";

        return View::make('seguimiento.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_compromiso' => 'required'
            , 'diferencia_tiempo' => 'required'
            , 'estado' => 'required'
            , 'condicion' => 'required'
            , 'porcentaje_avance' => 'required'
        ]);

        $this->desactivaSeguimientoAnteriores($request->id_compromiso);

        $seguimiento = $request->all();
        $seguimiento["fl_status"] = $request->exists('fl_status') ? true : false;
        $seguimiento_new = Seguimiento::create($seguimiento);

        $this->storeMedioVerificacion($request, $seguimiento_new->id_compromiso);
        $id_compromiso_reprogramado = $this->checkEstadoCondicionReprogramado($request);
        if ($id_compromiso_reprogramado) {
            return redirect()->route('compromiso.edit', $id_compromiso_reprogramado);
        } else {
            return $this->edit($seguimiento_new->id_seguimiento, true);
        }
    }

    public function desactivaSeguimientoAnteriores($id_compromiso) {

        $CompromisoSeguimiento = Compromiso::find($id_compromiso)->seguimiento;
        //Log::error($CompromisoSeguimiento);
        //Log::error($id_compromiso);

        foreach ($CompromisoSeguimiento as $seguimiento) {
            $seguimiento->update(['fl_status' => 'false']);
        }
        return true;
    }

    public function checkEstadoCondicionReprogramado($request) {

        $id_compromiso_reprogramado = 0;
        if ($request->condicion == "Reprogramado" && $request->estado == "Reprogramado") {
            $compromiso = Compromiso::find($request->id_compromiso);
            $compromiso_new = new Compromiso();
            $compromiso_new->id_hallazgo = $compromiso->id_hallazgo;
            $compromiso_new->nombre_compromiso = $compromiso->nombre_compromiso;
            $compromiso_new->plazo_comprometido = "";
            $compromiso_new->plazo_estimado = $compromiso->plazo_comprometido;
            $compromiso_new->responsable = $compromiso->responsable;
            $compromiso_new->fono_responsable = $compromiso->fono_responsable;
            $compromiso_new->email_responsable = $compromiso->email_responsable;
            $compromiso_new->fl_status = false;
            $compromiso_new->usuario_registra = auth()->user()->id;
            $compromiso_new->id_compromiso_padre = $compromiso->id_compromiso;
            $compromiso_new->save();
            $id_compromiso_reprogramado = $compromiso_new->id_compromiso;
        }
        return $id_compromiso_reprogramado;
    }

    public function storeMedioVerificacion($request, $id) {
        if (isset($request->documento_adjunto)) {

            foreach ($request->documento_adjunto as $file) {

                if (is_object($file)) {

                    $fileName = $file->getClientOriginalName();
                    $path = base_path() . config('system.folder_mv') . $id . '/';
                    if (!File::exists($path)) {
                        $result = File::makeDirectory($path, 0775);
                    }
                    $documento_adjunto = $path . $fileName;
                    $file->move($path, $fileName);

                    $medio_verificacion = new MedioVerificacion();
                    $medio_verificacion->id_compromiso = $id;
                    $medio_verificacion->descripcion = $fileName;
                    $medio_verificacion->documento_adjunto = $documento_adjunto;
                    $medio_verificacion->save();
                }
            }
        }
    }

    public function show($id) {

        $seguimiento = Seguimiento::find($id);

        $compromiso = Compromiso::find($seguimiento->id_compromiso);
        $returnData['compromiso'] = $compromiso;

        $proceso_auditado = \App\ProcesoAuditado::find(Compromiso::getIdProcesoAuditado($compromiso->id_compromiso));
        $returnData['proceso_auditado'] = $proceso_auditado;

        $seguimiento->diferencia_tiempo = dateDifference($compromiso->plazo_comprometido, $this->fechaActual);
        $returnData['seguimiento'] = $seguimiento;

        $diferencia_tiempo_tooltip = "Plazo Comprometido: " . $compromiso->plazo_comprometido . ". <br> Fecha Actual: " . $this->fechaActual;
        $returnData["diferencia_tiempo_tooltip"] = $diferencia_tiempo_tooltip;

        $returnData['estado'] = config('collection.estado');
        $returnData['condicion'] = config('collection.condicion');

        $medio_verificacion = $this->medio_verificacion($seguimiento->id_compromiso);
        $returnData['medio_verificacion'] = $medio_verificacion;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Seguimiento";
        return View::make('seguimiento.show', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $seguimiento = Seguimiento::find($id);

        $compromiso = Compromiso::find($seguimiento->id_compromiso);
        $returnData['compromiso'] = $compromiso;

        $proceso_auditado = \App\ProcesoAuditado::find(Compromiso::getIdProcesoAuditado($compromiso->id_compromiso));
        $returnData['proceso_auditado'] = $proceso_auditado;

        $seguimiento->diferencia_tiempo = dateDifference($compromiso->plazo_comprometido, $this->fechaActual);
        $returnData['seguimiento'] = $seguimiento;

        $diferencia_tiempo_tooltip = "Plazo Comprometido: " . $compromiso->plazo_comprometido . ". <br> Fecha Actual: " . $this->fechaActual;
        $returnData["diferencia_tiempo_tooltip"] = $diferencia_tiempo_tooltip;

        $returnData['estado'] = config('collection.estado');
        $returnData['condicion'] = config('collection.condicion');

        $medio_verificacion = $this->medio_verificacion($seguimiento->id_compromiso);
        $returnData['medio_verificacion'] = $medio_verificacion;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Seguimiento";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('seguimiento.edit', $returnData);
        } else {
            return View::make('seguimiento.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $this->validate($request, [
            'id_compromiso' => 'required'
            , 'diferencia_tiempo' => 'required'
            , 'estado' => 'required'
            , 'condicion' => 'required'
            , 'porcentaje_avance' => 'required'
        ]);

        $this->desactivaSeguimientoAnteriores($request->id_compromiso);

        $seguimientoUpdate = $request->all();
        $seguimiento = Seguimiento::find($id);
        $seguimiento->update($seguimientoUpdate);

        $this->storeMedioVerificacion($request, $seguimiento->id_compromiso);
        $this->checkEstadoCondicionReprogramado($request);
        return $this->edit($id, true);
    }

    public function delete($id) {

        $seguimiento = Seguimiento::find($id);
        $returnData['seguimiento'] = $seguimiento;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar Seguimiento";
        return View::make('seguimiento.delete', $returnData);
    }

    public function destroy($id) {
        Seguimiento::find($id)->delete();
        return redirect($this->controller);
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
//$btnShow = "<a href='" . $this->controller . "/$row->id_seguimiento' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
//$actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_seguimiento/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_seguimiento' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    public function medio_verificacion($id_compromiso) {

        $medio_verificacion = MedioVerificacion::getByIdCompromiso($id_compromiso);

        $grid = \DataGrid::source($medio_verificacion);
        $grid->add('id_medio_verificacion', 'ID')->style("width:80px");
        $grid->add('descripcion', 'descripcion');
        $grid->add('documento_adjunto', 'Link')->cell(function( $value, $row) {
            $documento_adjunto = str_replace("C:\\xampp\\htdocs\\auditoria/public/", url('/') . "/", $row->documento_adjunto);
            $link = "<a href='" . $documento_adjunto . "' target='_blank'>visualizar</a>";
            return $link;
        })->style("width:90px; text-align:center");
        return $grid;
    }

    function ajaxSeguimiento(Request $request) {

        $id_compromiso = $request->input('id_compromiso');
        $seguimiento = Seguimiento::where('id_compromiso', '=', $id_compromiso)->get();
        return $seguimiento;
    }

}
