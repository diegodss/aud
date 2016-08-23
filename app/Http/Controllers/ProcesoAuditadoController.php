<?php

namespace App\Http\Controllers;

use View;
use Log;
use DB;
Use App\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\ProcesoAuditado;
use App\Ministerio;
use App\Organismo;
use App\CentroResponsabilidad;
use App\Region;
use App\Subsecretaria;
use App\ServicioSalud;
use App\Unidad;
use App\Establecimiento;
use App\Departamento;
use App\Comuna;
use App\AreaProcesoAuditado;
use App\Proceso;
use App\EquipoAuditor;

class ProcesoAuditadoController extends Controller {

    public function __construct() {

        $this->controller = "proceso_auditado";
        $this->title = "Proceso Auditado";
        $this->subtitle = "Gestion de procesos auditados";

//$this->middleware('auth');
//$this->middleware('admin');
    }

    public function index(Request $request) {



        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('proceso_auditado.index', $returnData);
    }

    public function filtro(Request $request) {

        $ministerio = Ministerio::active()->lists('nombre_ministerio', 'id_ministerio');
        $returnData['ministerio'] = $ministerio;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('proceso_auditado.filtro', $returnData);
    }

    public function form(Request $request) {

        $proceso = Proceso::active()->lists('nombre_proceso', 'id_proceso')->all();
        $returnData['proceso'] = $proceso;

        $equipo_auditor = EquipoAuditor::active()->lists('nombre_equipo_auditor', 'id_equipo_auditor')->all();
        $returnData['equipo_auditor'] = $equipo_auditor;

        $area_proceso_auditado = $request->area_proceso_auditado;
        $returnData['area_proceso_auditado'] = $area_proceso_auditado;

        if (isset($request->area_proceso_auditado)) {
            $request_area_proceso_auditado = json_decode($request->area_proceso_auditado);
            $unidad_auditada = $request_area_proceso_auditado->descripcion_area;
        } else {
            $unidad_auditada = "Error. Por favor empezar el proceso nuevamente.";
        }
        $returnData['unidad_auditada'] = $unidad_auditada;

        $objetivo_auditoria = array(
            "Gubernamental" => "Gubernamental"
            , "Ministerial" => "Ministerial"
            , "Interna" => "Interna");
        $returnData['objetivo_auditoria'] = $objetivo_auditoria;

        $actividad_auditoria = array(
            "Auditoría Interna" => "Auditoría Interna"
            , "Auditoría Externa-Público" => "Auditoría Externa-Público"
            , "Auditoría Externa-Privado" => "Auditoría Externa-Privado"
            , "Contraloría General de la República" => "Contraloría General de la República"
            , "Otro" => "Otro");
        $returnData['actividad_auditoria'] = $actividad_auditoria;

        $tipo_auditoria = array(
            "Planificada" => "Planificada"
            , "No Planificada" => "No Planificada");
        $returnData['tipo_auditoria'] = $tipo_auditoria;

        $nomenclatura = array(
            "PMG" => "PMG"
            , "NO PMG" => "NO PMG"
            , "Contraloría General de la República" => "Contraloría General de la República");
        $returnData['nomenclatura'] = $nomenclatura;

        $ano = $this->getAnoSelectValues();
        $returnData['ano'] = $ano;

        $numero_informe_unidad = array(
            "UAI" => "UAI"
            , "UAE" => "UAE"
            , "UAS" => "UAS"
            , "DAM" => "DAM");
        $returnData['numero_informe_unidad'] = $numero_informe_unidad;

        $tipo_informe = array(
            "Informe  Final" => "Informe Final"
            , "Informe de Seguimiento" => "Informe de Seguimiento"
            , "Informe Especial" => "Informe Especial");
        $returnData['tipo_informe'] = $tipo_informe;


        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Nuevo Proceso Auditado";

        return View::make('proceso_auditado.create', $returnData);
    }

    public function getAnoSelectValues() {
        $anoInicial = date("Y");
        $anoFinal = $anoInicial - 10;
        for ($i = $anoInicial; $i >= $anoFinal; $i--) {
            $ano[$i] = $i;
        }
        return $ano;
    }

    public function confirmar(Request $request) {

        $returnData['tipo'] = $request->tipo;
        $id_proceso_auditaro_unidad = $request["id_" . $request->tipo];

        switch ($request->tipo) {
            case "organismo":
                $organismo = Organismo::find($id_proceso_auditaro_unidad);
                $returnData['proceso_auditaro_unidad'] = $organismo->nombre_organismo;
                break;
            case "subsecretaria":
                $subsecretaria = Subsecretaria::find($id_proceso_auditaro_unidad);
                $returnData['proceso_auditaro_unidad'] = $subsecretaria->nombre_subsecretaria;
                break;
            case "division":
            case "seremi":
            case "gabinete":
                $centro_responsabilidad = CentroResponsabilidad::find($id_proceso_auditaro_unidad);
                $returnData['proceso_auditaro_unidad'] = $centro_responsabilidad->nombre_centro_responsabilidad;
                break;
            case "servicio_salud":
                $servicio_salud = servicioSalud::find($id_proceso_auditaro_unidad);
                $returnData['proceso_auditaro_unidad'] = $servicio_salud->nombre_servicio;
                break;
            case "establecimiento":
                $establecimiento = Establecimiento::find($id_proceso_auditaro_unidad);
                $returnData['proceso_auditaro_unidad'] = $establecimiento->nombre_establecimiento;
                break;
            case "departamento":
                $departamento = Departamento::find($id_proceso_auditaro_unidad);
                $returnData['proceso_auditaro_unidad'] = $departamento->nombre_departamento;
                break;
            case "unidad":
                $unidad = Unidad::find($id_proceso_auditaro_unidad);
                $returnData['proceso_auditaro_unidad'] = $unidad->nombre_unidad;
                break;
        }

        $area_proceso_auditado = new AreaProcesoAuditado();
        $area_proceso_auditado->tabla = $request->tipo;
        $area_proceso_auditado->id_tabla = $request["id_" . $request->tipo];
        $area_proceso_auditado->descripcion_area = $returnData['proceso_auditaro_unidad'];
        $returnData['area_proceso_auditado'] = $area_proceso_auditado;



        /*
          'id_ministerio' => '1',
          'tipo' => 'organismo',
          'subsecretaria_search' => '',
          'servicio_salud_search' => '',
          'centro_responsabilidad_search' => '',
          'departamento_search' => '',
          'id_organismo' => '2',
          'id_subsecretaria' => '',
          'id_division' => '',
          'id_seremi' => '',
          'id_gabinete' => '',
          'id_servicio_salud' => '',
          'id_establecimiento' => '',
          'id_departamento' => '',
          'id_unidad' => '',
          $returnData['title'] = $this->title;
          $returnData['subtitle'] = $this->subtitle;
          $returnData['titleBox'] = "Nuevo ProcesoAuditado";
         */

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Confirmar datos para nuevo Proceso Auditado";

        return View::make('proceso_auditado.confirmar', $returnData);
    }

    public function guardar(Request $request) {
        Log::error($request);
        $this->validate($request, [
            'objetivo_auditoria' => 'required',
            'actividad_auditoria' => 'required',
            'tipo_auditoria' => 'required',
            'nomenclatura' => 'required',
            'numero_informe' => 'required',
            'numero_informe_unidad' => 'required',
            'ano' => 'required',
            'nombre_proceso_auditado' => 'required',
        ]);

        $proceso_auditado = $request->all();
        $proceso_auditado["fl_status"] = $request->exists('fl_status') ? true : false;
        $proceso_auditado_new = ProcesoAuditado::create($proceso_auditado);
        $id_proceso_auditado_new = $proceso_auditado_new->id_proceso_auditado;

        $request_area_proceso_auditado = json_decode($request->area_proceso_auditado);
        $area_proceso_auditado = New AreaProcesoAuditado(); //$request->area_proceso_auditado;
        $area_proceso_auditado->tabla = $request_area_proceso_auditado->tabla;
        $area_proceso_auditado->id_tabla = $request_area_proceso_auditado->id_tabla;
        $area_proceso_auditado->id_proceso_auditado = $id_proceso_auditado_new;
        $area_proceso_auditado->save();

        Log::error($proceso_auditado_new);
        Log::error($area_proceso_auditado);

        $proceso_auditado_identificador = $request->numero_informe . " " . $request->numero_informe_unidad;

        $mensage_success = "Proceso " . $proceso_auditado_identificador . " grabado con suceso"; //trans('message.saved.success');
        //Proceso Auditado
        // area_proceso_auditado
        $returnData["proceso_auditado_identificador"] = $proceso_auditado_identificador;
        /*
          return redirect()->route('proceso_auditado.index')
          ->with('success', $mensage_success); */
        return View::make('proceso_auditado.index', $returnData)->withSuccess($mensage_success);

        //
    }

    public function show($id) {
        /*
          $proceso_auditado = ProcesoAuditado::find($id);

          $returnData['proceso_auditado'] = $proceso_auditado;

          $returnData['title'] = $this->title;
          $returnData['subtitle'] = $this->subtitle;
          $returnData['titleBox'] = "Visualizar ProcesoAuditado";
          return View::make('proceso_auditado.show', $returnData);
         *
         */
    }

    public function edit($id, $show_success_message = false) {
        /*
          $proceso_auditado = ProcesoAuditado::find($id);

          $returnData['proceso_auditado'] = $proceso_auditado;

          $returnData['title'] = $this->title;
          $returnData['subtitle'] = $this->subtitle;
          $returnData['titleBox'] = "Editar ProcesoAuditado";
          $mensage_success = trans('message.saved.success');

          if (!$show_success_message) {
          return View::make('proceso_auditado.edit', $returnData);
          } else {
          return View::make('proceso_auditado.edit', $returnData)->withSuccess($mensage_success);
          }
          ; */
    }

    public function update($id, Request $request) {
        /*
          $this->validate($request, [
          'nombre_proceso' => 'required',
          'responsable_proceso' => 'required',
          ]);

          $procesoUpdate = $request->all();
          $procesoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
          $proceso_auditado = ProcesoAuditado::find($id);
          $proceso_auditado->update($procesoUpdate);

          $mensage_success = trans('message.saved.success');

          return $this->edit($id, true);
          /*
          return redirect()->route('proceso_auditado.index')
          ->with('success', $mensage_success); */
    }

    public function busqueda($id) {

        $ministerio = Ministerio::active()->lists('nombre_ministerio', 'id_ministerio')->all();
        $returnData['ministerio'] = $ministerio;

        $region = Region::active()->lists('nombre_region', 'id_region')->all();
        $returnData['region'] = $region;

        $organismo = Organismo::active()->lists('nombre_organismo', 'id_organismo')->all();
        $returnData['organismo'] = $region;

        $division = CentroResponsabilidad::division()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['division'] = $division;

        $gabinete = CentroResponsabilidad::gabinete()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['gabinete'] = $gabinete;

        $seremi = CentroResponsabilidad::seremi()->lists('nombre_centro_responsabilidad', 'id_centro_responsabilidad')->all();
        $returnData['seremi'] = $seremi;

        $servicio_salud = ServicioSalud::serviciosalud()->lists('nombre_servicio', 'id_servicio_salud')->all();
        $returnData['servicio_salud'] = $servicio_salud;

        $establecimiento = Establecimiento::active()->lists('nombre_establecimiento', 'id_establecimiento')->all();
        $returnData['establecimiento'] = $establecimiento;

        $departamento = Departamento::active()->lists('nombre_departamento', 'id_departamento')->all();
        $returnData['departamento'] = $departamento;

        $unidad = Unidad::active()->lists('nombre_unidad', 'id_unidad')->all();
        $returnData['unidad'] = $unidad;

        $tipo_centro_responsabilidad = array("gabinete" => "Gabinete", "division" => "Division", "seremi" => "Seremi");
        $returnData['tipo_centro_responsabilidad'] = $tipo_centro_responsabilidad;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Filtro de selección de unidad auditada";
        return View::make('proceso_auditado.filtro', $returnData);
    }

    public function destroy($id) {
        /*
          ProcesoAuditado::find($id)->delete();
          return redirect($this->controller);
         *
         */
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            $btnShow = "<a href = '" . $this->controller . "/$row->id_proceso' class = 'btn btn-info btn-xs'><i class = 'fa fa-folder'></i></a>";
            $actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href = '" . $this->controller . "/$row->id_proceso/edit' class = 'btn btn-primary btn-xs'><i class = 'fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href = '" . $this->controller . "/delete/$row->id_proceso' class = 'btn btn-danger btn-xs'> <i class = 'fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

}
