<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
use Excel;
use Session;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Compromiso;
use App\Hallazgo;
use App\Seguimiento;
use App\MedioVerificacion;
use App\ProcesoAuditado;
use App\Usuario;
use App\CompromisoNomenclatura;

class CompromisoController extends Controller {

    public function __construct() {

        $this->controller = "compromiso";
        $this->title = "Compromisos";
        $this->subtitle = "Gestion de compromisos";

        $this->fechaActual = date("d") . "-" . date("m") . "-" . date("Y");

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function setViewVariables() {

        $this->nomenclatura = config('collection.nomenclatura');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $compromiso = Compromiso::hallazgoProcesoAuditado();
        $filter = \DataFilter::source($compromiso);
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
        $grid->add('nombre_proceso_auditado', 'Proceso', true);
        $grid->add('nombre_hallazgo', 'Hallazgo', true);
        $grid->add('nombre_compromiso', 'Compromiso', true);
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:180px; text-align:center");
        $grid->orderBy('id_compromiso', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('compromiso.index', $returnData);
    }

    public function create($id_hallazgo) {

        $this->setViewVariables();
        $returnData['nomenclatura'] = $this->nomenclatura;
        $compromiso = new Compromiso;
        $compromiso->id_hallazgo = $id_hallazgo;
        $returnData['compromiso'] = $compromiso;

        $hallazgo = Hallazgo::find($compromiso->id_hallazgo);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['proceso_auditado'] = $proceso_auditado;
        $returnData['proceso_fecha'] = $proceso_auditado->fecha;

        $returnData['nomenclatura_historico'] = $this->getNomenclaturaHistorico(0);
        $returnData['seguimiento_actual'] = $this->getSeguimientoActual(0);

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Compromiso";

        return View::make('compromiso.create', $returnData);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'id_hallazgo' => 'required'
            , 'nomenclatura' => 'required'
            , 'plazo_estimado' => 'required'
            , 'plazo_comprometido' => 'required'
            , 'nombre_compromiso' => 'required'
            , 'responsable' => 'required'
            , 'email_responsable' => 'required|email'
        ]);

        $compromiso = $request->all();
        $compromiso["fl_status"] = $request->exists('fl_status') ? true : false;
        $compromiso_new = Compromiso::create($compromiso);

        $seguimiento_new = new Seguimiento();
        $seguimiento_new->diferencia_tiempo = dateDifference($compromiso_new->plazo_comprometido, $this->fechaActual);
        $seguimiento_new->id_compromiso = $compromiso_new->id_compromiso;
        $seguimiento_new->porcentaje_avance = 0;
        $seguimiento_new->estado = "Vigente";
        $seguimiento_new->condicion = "En Proceso";
        $seguimiento_new->fl_status = true;
        $seguimiento_new->usuario_registra = auth()->user()->id;
        $seguimiento_new->save();

        $mensage_success = trans('message.saved.success');

        if ($compromiso["modal"] == "sim") {
            return $compromiso_new;
        } else {
            return $this->edit($compromiso_new->id_compromiso, true);
        }
    }

    public function show($id) {

        $this->setViewVariables();
        $returnData['nomenclatura'] = $this->nomenclatura;

        $compromiso = Compromiso::find($id);
        $returnData['compromiso'] = $compromiso;

        $hallazgo = Hallazgo::find($compromiso->id_hallazgo);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['proceso_auditado'] = $proceso_auditado;
        $returnData['proceso_fecha'] = $proceso_auditado->fecha;

        $returnData['medio_verificacion'] = $this->medio_verificacion($id);

        $returnData['seguimiento'] = $this->seguimiento($id);

        $returnData['seguimiento_actual'] = $this->getSeguimientoActual($id);

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Compromiso";
        return View::make('compromiso.show', $returnData);
    }

    public function showModal($id) {

        $compromiso = Compromiso::find($id);
        $returnData['compromiso'] = $compromiso;

        $hallazgo = Hallazgo::find($compromiso->id_hallazgo);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['proceso_auditado'] = $proceso_auditado;
        $returnData['proceso_fecha'] = $proceso_auditado->fecha;

        $returnData['medio_verificacion'] = $this->medio_verificacion($id);

        $returnData['seguimiento'] = $this->seguimiento($id);

        $returnData['seguimiento_actual'] = $this->getSeguimientoActual($id);

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Compromiso";
        return View::make('compromiso.show_modal', $returnData);
    }

    public function edit($id, $show_success_message = false) {

        $this->setViewVariables();
        $returnData['nomenclatura'] = $this->nomenclatura;
        $compromiso = Compromiso::find($id);
        $returnData['compromiso'] = $compromiso;

        $hallazgo = Hallazgo::find($compromiso->id_hallazgo);
        $returnData['hallazgo'] = $hallazgo;

        $proceso_auditado = ProcesoAuditado::find($hallazgo->id_proceso_auditado);
        $returnData['proceso_auditado'] = $proceso_auditado;
        $returnData['proceso_fecha'] = $proceso_auditado->fecha;

        $returnData['nomenclatura_historico'] = $this->getNomenclaturaHistorico($id);

        $returnData['medio_verificacion'] = $this->medio_verificacion($id);

        $returnData['seguimiento'] = $this->seguimiento($id);

        $returnData['seguimiento_actual'] = $this->getSeguimientoActual($id);

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
            , 'nomenclatura' => 'required'
            , 'plazo_estimado' => 'required'
            , 'plazo_comprometido' => 'required'
            , 'nombre_compromiso' => 'required'
            , 'responsable' => 'required'
            , 'email_responsable' => 'required|email'
        ]);

        $compromisoUpdate = $request->all();
        $compromisoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $compromiso = Compromiso::find($id);

        /* Verifica si nomenclatura actual es distinta de nueva nomenclatura */
        if ($compromiso->nomenclatura != $compromisoUpdate["nomenclatura"]) {
            $compromiso_nomenclatura = New \App\CompromisoNomenclatura();
            $compromiso_nomenclatura->id_compromiso = $id;
            $compromiso_nomenclatura->nomenclatura = $compromiso->nomenclatura;
            $compromiso_nomenclatura->save();
        }

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

    public function getNomenclaturaHistorico($id) {

        $grid = \DataGrid::source(CompromisoNomenclatura::where('id_compromiso', $id));

        $grid->add('id_compromiso_nomenclatura', 'ID')->style("width:40px");
        $grid->add('nomenclatura', 'Nomenclatura');
        $grid->add('created_at', 'Fecha Cambio'); // return $row->created_at->toFormattedDateString();
        return $grid;
    }

    public function getSeguimientoActual($id) {
        $seguimiento_actual = Seguimiento::getActualByIdCompromiso($id);
        if ($seguimiento_actual == "") {
            $seguimiento_actual = New \App\Seguimiento();
            $seguimiento_actual->nombre_usuario_registra = "";
        } else {
            if ($seguimiento_actual->usuario_registra == 0) {
                $seguimiento_actual->nombre_usuario_registra = "Sistema";
            } else {
                $user = Usuario::find($seguimiento_actual->usuario_registra);
                $seguimiento_actual->nombre_usuario_registra = $user->name;
                unset($user);
            }
        }
        return $seguimiento_actual;
    }

    public function medio_verificacion($id_compromiso) {

        $medio_verificacion = MedioVerificacion::getByIdCompromiso($id_compromiso);

        $grid = \DataGrid::source($medio_verificacion);
        //$grid->add('id_medio_verificacion', 'ID')->style("width:80px");
        $grid->add('descripcion', 'Medio de Verificacion');
        $grid->add('documento_adjunto', 'Link')->cell(function( $value, $row) {

            $local_path = config('system.local_path') . "public/";
            $documento_adjunto = str_replace($local_path, "", $row->documento_adjunto);
            $link = "<a href='" . $documento_adjunto . "' target='_blank'>visualizar</a>";
            return $link;
        })->style("width:90px; text-align:center");

        return $grid;
    }

    public function seguimiento($id_compromiso) {

        $seguimiento = Seguimiento::getByIdCompromiso($id_compromiso);

        $grid = \DataGrid::source($seguimiento);
        //$grid->add('id_seguimiento', 'ID')->style("width:80px");
        $grid->add('porcentaje_avance', 'Porcentaje de Avance');
        $grid->add('estado', 'Estado');
        $grid->add('condicion', 'Condicion');

        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumnSeguimiento($value, $row);
        })->style("width:90px; text-align:center");

        return $grid;
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";

        if (auth()->user()->can('userAction', 'seguimiento-create')) {
            $btnShow = "<a href='seguimiento/create/$row->id_compromiso' class='btn btn-info btn-xs'>nuevo seguimiento</a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_compromiso/edit' class='btn btn-primary btn-xs' alt='Editar Compromiso'><i class='fa fa-pencil'></i></a>";
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
            $btnShow = "<a href='" . $url . $controller . "/$row->id_seguimiento' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $controller . '-update')) {
            //$btneditar = "<a href='" . $url . $controller . "/$row->id_seguimiento/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            //$actionColumn .= " " . $btneditar;
        }

        return $actionColumn;
    }

    public function setActionColumnMedioVerificacion($value, $row) {

        $controller = "medio_verificacion";
        $actionColumn = "";
        $url = url('/') . "/";
        if (auth()->user()->can('userAction', $controller . '-index')) {
            //$btnShow = "<a href='" . $url . $controller . "/$row->id_medio_verificacion' class='btn btn-info btn-xs'><i class='fa fa-eye'></i></a>";
            //$actionColumn .= " " . $btnShow;
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

    function ajaxCompromisoResponsable(Request $request) {


        $input = $request->input('term');

        $compromiso_responsable = Compromiso::responsable($input)->get();
        Log::info($compromiso_responsable);
        /*
          [{"id":"1","label":"Centro de Eventos","value":"Centro de Eventos","address":"Apoquindo 999","town":"Santiago","state":null,"region":null,"postcode":null,"country":"CL"}
          ,{"id":"2","label":"Centro de Eventos","value":"Centro de Eventos","address":"Apoquindo 999","town":"Santiago","state":null,"region":null,"postcode":null,"country":"CL"}]
         */
        //$compromiso_responsable = '[{"id":"1", "value":"Choice1", "fono_responsable":"2721-4650","email_responsable":"diego@choise1.cl"},{"id":"2", "value":"Choice2", "fono_responsable":"9970-7707","email_responsable":"natalia@choise2.cl"}]';

        return $compromiso_responsable;
    }

    function compromisoVencido($tipo_alerta_semaforo) {

        Log::info($tipo_alerta_semaforo);
        switch ($tipo_alerta_semaforo) {
            case "verde":
                $intervalo_inicio = "0";
                $intervalo_fin = "30";
                $css_box = "green";
                break;
            case "amarillo":
                $intervalo_inicio = "31";
                $intervalo_fin = "60";
                $css_box = "yellow";
                break;
            case "rojo":
                $intervalo_inicio = "61";
                $intervalo_fin = "90";
                $css_box = "red";
                break;
        }

        /* Datos para exportar a Excel */
        $compromiso_vencido = Compromiso::compromiso_vencido($intervalo_inicio, $intervalo_fin);
        $columns = array("id", "division", "numero_informe", "fecha", "hallazgo", "compromiso", "condicion", "porcentaje_avance");
        $columns_postfix = array("", "", "", "", "", "", "", "");

        $dataGoogleChart = app('App\Http\Controllers\InformeDetalladoController')->setDataGoogleChart($compromiso_vencido->get(), $columns, $columns_postfix);
        Session::put('compromiso_vencido_excel', $dataGoogleChart["dataExcel"]);
        /* Fin de Datos para exportar a Excel */


        $grid = \DataGrid::source($compromiso_vencido);
        $grid->add('id', 'ID', false)->cell(function( $value, $row ) {
            return "<a href='" . url('compromiso/' . $row->id . '/edit' . "'>" . $row->id . "</a>");
        });
        $grid->add('numero_informe', 'Nº', false)->style("width:80px; text-align:center");
        $grid->add('fecha', 'Fecha', false)->style("width:100px; text-align:center");
        $grid->add('division', 'División', false);
        $grid->add('hallazgo', 'Hallazgo', false);
        $grid->add('compromiso', 'Compromiso', false);
        $grid->add('condicion', 'Condición', false);
        $grid->add('porcentaje_avance', '%', false)->cell(function( $value, $row ) {
            return $row->porcentaje_avance * 100 . "%";
        });
        $grid->orderBy('id', 'asc');
        $returnData['compromiso_vencido'] = $grid;
        $returnData['tipo_alerta_semaforo'] = $tipo_alerta_semaforo;


        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "<div class='small-box bg-" . $css_box . "'><div class='inner'>Compromiso Vencidos</div></div>";
        return View::make('compromiso.vencido_modal', $returnData);
    }

    public function compromisoVencidoExcel($tipo_alerta_semaforo) {

        $fechaActual = date("d") . "-" . date("m") . "-" . date("Y");
        $filename = "compromiso_vencido_" . $tipo_alerta_semaforo . "_" . $fechaActual;

        $array = array(
            'compromiso_vencido' => Session::get('compromiso_vencido_excel')
        );

        Excel::create($filename, function($excel)use($array, $fechaActual) {

            foreach ($array as $key => $value) {
                $excel->sheet($key, function($sheet) use($value) {

                    //$sheet->fromArray(array('Titlo'));
                    $sheet->fromArray($value);
                });
            }
        })->export('xls');
    }

}
