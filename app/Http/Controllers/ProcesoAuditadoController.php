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
use App\Hallazgo;
use App\RelProcesoAuditor;
use App\Auditor;

class ProcesoAuditadoController extends Controller {

    public function __construct() {

        $this->controller = "proceso_auditado";
        $this->title = "Proceso Auditado";
        $this->subtitle = "Gestion de proceso auditados";

        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function setViewVariables() {

        $this->proceso = Proceso::active()->lists('nombre_proceso', 'id_proceso')->all();
        $this->equipo_auditor = EquipoAuditor::active()->lists('nombre_equipo_auditor', 'id_equipo_auditor')->all();
        $this->objetivo_auditoria = config('collection.objetivo_auditoria');
        $this->actividad_auditoria = config('collection.actividad_auditoria');
        $this->tipo_auditoria = config('collection.tipo_auditoria');
        $this->nomenclatura = config('collection.nomenclatura');
        $this->numero_informe_unidad = config('collection.numero_informe_unidad');
        $this->tipo_informe = config('collection.tipo_informe');
    }

    public function index(Request $request) {

        $itemsPageRange = config('system.items_page_range');

        $itemsPage = $request->itemsPage;
        if (is_null($itemsPage)) {
            $itemsPage = config('system.items_page');
        }

        $filter = \DataFilter::source(new ProcesoAuditado);
        $filter->add('numero_informe', 'Nº Informe', 'text')->clause('where')->operator('=');
        $filter->add('numero_informe_unidad', 'Unidad', 'text')->clause('where')->operator('=');
        $filter->add('ano', 'Año', 'text')->clause('where')->operator('=');
        $filter->submit('search');
        $filter->reset('reset');
        $filter->build();

        $grid = \DataGrid::source($filter);
        $grid->add('id_proceso_auditado', 'ID', true)->style("width:50px;");
        $grid->add('numero_informe', 'nº', true)->style("width:80px")->cell(function( $value, $row ) {
            return $row->numero_informe . " " . $row->numero_informe_unidad;
        });
        $grid->add('nombre_proceso_auditado', 'Proceso', true);
        $grid->add('fecha', 'Fecha', true);
        $grid->add('ano', 'Año', true);
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumn($value, $row);
        })->style("width:90px; text-align:center");
        $grid->orderBy('id_proceso_auditado', 'asc');
        $grid->paginate($itemsPage);

        $returnData['grid'] = $grid;
        $returnData['filter'] = $filter;
        $returnData['itemsPage'] = $itemsPage;
        $returnData['itemsPageRange'] = $itemsPageRange;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['controller'] = $this->controller;

        return View::make('proceso_auditado.index', $returnData);
    }

    public function filtro() {

        $returnData['proceso_auditado'] = new ProcesoAuditado();

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

        $tipo_centro_responsabilidad = config('collection.tipo_centro_responsabilidad');
        $returnData['tipo_centro_responsabilidad'] = $tipo_centro_responsabilidad;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Filtro de selección de unidad auditada";
        return View::make('proceso_auditado.filtro', $returnData);
    }

    public function confirmar(Request $request) {

        $returnData['proceso_auditado'] = new ProcesoAuditado();
        $returnData['tipo'] = $request->tipo;
        $id_proceso_auditado_unidad = $request["id_" . $request->tipo];

        switch ($request->tipo) {
            case "organismo":
                $organismo = Organismo::find($id_proceso_auditado_unidad);
                $returnData['proceso_auditado_unidad'] = $organismo->nombre_organismo;
                break;
            case "subsecretaria":
                $subsecretaria = Subsecretaria::find($id_proceso_auditado_unidad);
                $returnData['proceso_auditado_unidad'] = $subsecretaria->nombre_subsecretaria;
                break;
            case "division":
            case "seremi":
            case "gabinete":
                $centro_responsabilidad = CentroResponsabilidad::find($id_proceso_auditado_unidad);
                $returnData['proceso_auditado_unidad'] = $centro_responsabilidad->nombre_centro_responsabilidad;
                break;
            case "servicio_salud":
                $servicio_salud = servicioSalud::find($id_proceso_auditado_unidad);
                $returnData['proceso_auditado_unidad'] = $servicio_salud->nombre_servicio;
                break;
            case "establecimiento":
                $establecimiento = Establecimiento::find($id_proceso_auditado_unidad);
                $returnData['proceso_auditado_unidad'] = $establecimiento->nombre_establecimiento;
                break;
            case "departamento":
                $departamento = Departamento::find($id_proceso_auditado_unidad);
                $returnData['proceso_auditado_unidad'] = $departamento->nombre_departamento;
                break;
            case "unidad":
                $unidad = Unidad::find($id_proceso_auditado_unidad);
                $returnData['proceso_auditado_unidad'] = $unidad->nombre_unidad;
                break;
        }

        // -- Se agrega ministerio al objeto --
        $area_proceso_auditado = new AreaProcesoAuditado();
        $area_proceso_auditado->tabla = 'ministerio';
        $area_proceso_auditado->id_tabla = $request["id_ministerio"];
        $area_proceso_auditado->descripcion = Ministerio::getNombreById($request["id_ministerio"]);
        $area_proceso_auditado_collection[] = $area_proceso_auditado;

        // -- Se agrega subsecretaria_search al objeto --
        if ($request["subsecretaria_search"] != "") {
            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->tabla = 'subsecretaria';
            $area_proceso_auditado->id_tabla = $request["subsecretaria_search"];
            $area_proceso_auditado->descripcion = Subsecretaria::getNombreById($request["subsecretaria_search"]);
            $area_proceso_auditado_collection[] = $area_proceso_auditado;
        }
        // -- Se agrega servicio_salud_search al objeto --
        if ($request["servicio_salud_search"] != "") {
            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->tabla = 'servicio_salud_search';
            $area_proceso_auditado->id_tabla = $request["servicio_salud_search"];
            $area_proceso_auditado->descripcion = ServicioSalud::getNombreById($request["servicio_salud_search"]);
            $area_proceso_auditado_collection[] = $area_proceso_auditado;
        }

        // -- Se agrega centro_responsabilidad_search al objeto --
        if ($request["centro_responsabilidad_search"] != "") {
            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->tabla = $request["tipo_centro_responsabilidad"];
            $area_proceso_auditado->id_tabla = $request["centro_responsabilidad_search"];
            $area_proceso_auditado->descripcion = CentroResponsabilidad::getNombreById($request["centro_responsabilidad_search"]);
            $area_proceso_auditado_collection[] = $area_proceso_auditado;
        }

        // -- Se agrega subsecretaria_search al objeto --
        if ($request["departamento_search"] != "") {
            $area_proceso_auditado = new AreaProcesoAuditado();
            $area_proceso_auditado->tabla = 'subsecretaria';
            $area_proceso_auditado->id_tabla = $request["departamento_search"];
            $area_proceso_auditado->descripcion = Departamento::getNombreById($request["departamento_search"]);
            $area_proceso_auditado_collection[] = $area_proceso_auditado;
        }


        $area_proceso_auditado = new AreaProcesoAuditado();
        $area_proceso_auditado->tabla = $request->tipo;
        $area_proceso_auditado->id_tabla = $request["id_" . $request->tipo];
        $area_proceso_auditado->descripcion = $returnData['proceso_auditado_unidad'];

        $area_proceso_auditado_collection[] = $area_proceso_auditado;

        $returnData['area_proceso_auditado'] = $area_proceso_auditado;
        $returnData['area_proceso_auditado_collection'] = $area_proceso_auditado_collection;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Confirmar datos para nuevo Proceso Auditado";

        return View::make('proceso_auditado.confirmar', $returnData);
    }

    public function create(Request $request) {

        //$this->setViewVariables();
        $proceso_auditado = new ProcesoAuditado;
        $proceso_auditado->usuario_registra = 1;
        $proceso_auditado->fl_status = false;
        $proceso_auditado->save();

        // ---- guardar area_auditada
        $area_proceso_auditado_collection = $_POST["area_proceso_auditado_collection"];
        $area_proceso_auditado_collection = json_decode($area_proceso_auditado_collection);
        foreach ($area_proceso_auditado_collection as $row) {
            $area_proceso_auditado = New AreaProcesoAuditado();
            $area_proceso_auditado->tabla = strtolower($row->tabla);
            $area_proceso_auditado->id_tabla = $row->id_tabla;
            $area_proceso_auditado->descripcion = $row->descripcion;
            $area_proceso_auditado->id_proceso_auditado = $proceso_auditado->id_proceso_auditado;
            $area_proceso_auditado->save();
        }
        return redirect()->route('proceso_auditado.edit', $proceso_auditado->id_proceso_auditado);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'objetivo_auditoria' => 'required',
            'actividad_auditoria' => 'required',
            'tipo_auditoria' => 'required',
            'nomenclatura' => 'required',
            'numero_informe' => 'required|unique:proceso_auditado',
            'numero_informe_unidad' => 'required',
            'ano' => 'required',
            'fecha' => 'required',
            'nombre_proceso_auditado' => 'required',
            'id_auditor_lider' => 'required',
        ]);

        $proceso_auditado = $request->all();
        $proceso_auditado_new = ProcesoAuditado::create($proceso_auditado);
        return $this->edit($proceso_auditado_new->id_proceso_auditado, true, true);
    }

    public function show($id) {
        $this->setViewVariables();
        $returnData['proceso_auditado'] = ProcesoAuditado::find($id);
        $areaProcesoAuditado = AreaProcesoAuditado::areaAuditada($id)->first();
        $returnData['unidad_auditada'] = $areaProcesoAuditado->descripcion;
        $returnData['hallazgo'] = $this->hallazgo($id);

        $returnData['grid_equipo_auditor'] = $this->getAuditores($id);
        $returnData["id_auditor_lider"] = true;

        $returnData['proceso'] = $this->proceso;
        $returnData['equipo_auditor'] = $this->equipo_auditor;
        $returnData['objetivo_auditoria'] = $this->objetivo_auditoria;
        $returnData['actividad_auditoria'] = $this->actividad_auditoria;
        $returnData['tipo_auditoria'] = $this->tipo_auditoria;
        $returnData['nomenclatura'] = $this->nomenclatura;
        $returnData['ano'] = $this->getAnoSelectValues();
        $returnData['numero_informe_unidad'] = $this->numero_informe_unidad;
        $returnData['tipo_informe'] = $this->tipo_informe;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Visualizar Proceso Auditado";

        return View::make('proceso_auditado.show', $returnData);
    }

    public function edit($id, $show_success_message = false, $nuevo = false) {
        $this->setViewVariables();


        $proceso_auditado = ProcesoAuditado::find($id);
        $proceso_auditado->fl_status = $proceso_auditado->fl_status === false ? "false" : "true";
        $returnData['proceso_auditado'] = $proceso_auditado;

        //Log::error($proceso_auditado);
        $returnData['grid_equipo_auditor'] = $this->getAuditores($id);

        $auditores = ProcesoAuditado::getAuditorById($id)->get();

        $returnData["id_auditor_lider"] = false;
        if (count($auditores) > 0) {
            $returnData["id_auditor_lider"] = true;
        }

        $cuanditad_hallazgo_db = Hallazgo::getCuantidadHallazgoDb($id);
        $returnData['cuanditad_hallazgo_db'] = $cuanditad_hallazgo_db;

        $returnData['area_proceso_auditado'] = "";
        $returnData['area_proceso_auditado_collection'] = "";

        $areaProcesoAuditado = AreaProcesoAuditado::areaAuditada($id)->first();
        $returnData['unidad_auditada'] = $areaProcesoAuditado->descripcion;
        $returnData['hallazgo'] = $this->hallazgo($id);

        $auditor = Auditor::active()->lists('nombre_auditor', 'id_auditor')->all();
        $returnData['auditor'] = $auditor;

        $returnData['proceso'] = $this->proceso;
        $returnData['equipo_auditor'] = $this->equipo_auditor;
        $returnData['objetivo_auditoria'] = $this->objetivo_auditoria;
        $returnData['actividad_auditoria'] = $this->actividad_auditoria;
        $returnData['tipo_auditoria'] = $this->tipo_auditoria;
        $returnData['nomenclatura'] = $this->nomenclatura;
        $returnData['ano'] = $this->getAnoSelectValues();
        $returnData['numero_informe_unidad'] = $this->numero_informe_unidad;
        $returnData['tipo_informe'] = $this->tipo_informe;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Editar Proceso Auditado";
        $mensage_success = trans('message.saved.success');

        if (!$show_success_message) {
            return View::make('proceso_auditado.edit', $returnData);
        } else {
            return View::make('proceso_auditado.edit', $returnData)->withSuccess($mensage_success);
        }
        ;
    }

    public function update($id, Request $request) {

        $messages = [
            'id_auditor_lider.required' => 'Por favor informe el lider del equipo',
        ];

        $this->validate($request, [
            'objetivo_auditoria' => 'required',
            'actividad_auditoria' => 'required',
            'tipo_auditoria' => 'required',
            'nomenclatura' => 'required',
            'numero_informe' => 'required|unique:proceso_auditado,numero_informe,' . $id . ',id_proceso_auditado,numero_informe_unidad,' . $request->numero_informe_unidad . ',ano,' . $request->ano,
            'numero_informe_unidad' => 'required',
            'ano' => 'required',
            'fecha' => 'required',
            'nombre_proceso_auditado' => 'required',
            'id_auditor_lider' => 'required',
                ], $messages);

        $proceso_auditadoUpdate = $request->all();
        //$proceso_auditadoUpdate["fl_status"] = $request->exists('fl_status') ? true : false;
        $proceso_auditado = ProcesoAuditado::find($id);
        $proceso_auditado->update($proceso_auditadoUpdate);

        return $this->edit($id, true);
    }

    public function delete($id) {

        $proceso_auditado = ProcesoAuditado::find($id);

        $returnData['proceso_auditado'] = $proceso_auditado;

        $returnData['title'] = $this->title;
        $returnData['subtitle'] = $this->subtitle;
        $returnData['titleBox'] = "Eliminar ProcesoAuditado";
        return View::make('proceso_auditado.delete', $returnData);
    }

    public function destroy($id) {
        ProcesoAuditado::find($id)->delete();
        return redirect($this->controller);
    }

    public function hallazgo($id_proceso_auditado) {

        $hallazgo = Hallazgo::getByIdProcesoAuditado($id_proceso_auditado);

        $grid = \DataGrid::source($hallazgo);
        $grid->add('id_hallazgo', 'ID')->style("width:80px");
        $grid->add('nombre_hallazgo', 'Hallazgo');
        $grid->add('recomendacion', 'Recomedacion');
        $grid->add('criticidad', 'Criticidad');
        $grid->add('accion', 'Acción')->cell(function( $value, $row) {
            return $this->setActionColumnHallazgo($value, $row);
        })->style("width:90px; text-align:center");

        //$returnData['grid_hallazgo'] = $grid;
        return $grid;
    }

    public function setActionColumn($value, $row) {

        $actionColumn = "";
        if (auth()->user()->can('userAction', $this->controller . '-index')) {
            //$btnShow = "<a href='" . $this->controller . "/$row->id_proceso_auditado' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            //$actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $this->controller . '-update')) {
            $btneditar = "<a href='" . $this->controller . "/$row->id_proceso_auditado/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        if (auth()->user()->can('userAction', $this->controller . '-destroy')) {
            $btnDeletar = "<a href='" . $this->controller . "/delete/$row->id_proceso_auditado' class='btn btn-danger btn-xs'> <i class='fa fa-trash-o'></i></a>";
            $actionColumn .= " " . $btnDeletar;
        }
        return $actionColumn;
    }

    public function setActionColumnHallazgo($value, $row) {

        $controller = "hallazgo";
        $actionColumn = "";
        $url = url('/') . "/";
        if (auth()->user()->can('userAction', $controller . '-index')) {
            //$btnShow = "<a href='" . $url . $controller . "/$row->id_hallazgo' class='btn btn-info btn-xs'><i class='fa fa-folder'></i></a>";
            //$actionColumn .= " " . $btnShow;
        }

        if (auth()->user()->can('userAction', $controller . '-update')) {
            $btneditar = "<a href='" . $url . $controller . "/$row->id_hallazgo/edit' class='btn btn-primary btn-xs'><i class='fa fa-pencil'></i></a>";
            $actionColumn .= " " . $btneditar;
        }

        return $actionColumn;
    }

    public function getAuditores($id_proceso_auditado) {
        $grid = \DataGrid::source(ProcesoAuditado::find($id_proceso_auditado)->auditor->all());

        $grid->add('id_auditor', 'ID')->style("width:40px");
        $grid->add('nombre_auditor', 'Auditor');
        $grid->add('jefatura_equipo', 'Lider')->cell(function( $value, $row ) {

            return $row->pivot->jefatura_equipo ? "<small class='label bg-green'>lider</small>" : "";
        });
        return $grid;
    }

    public function storeAuditor($id_proceso_auditado, $id_auditor) {

        $auditores = ProcesoAuditado::getAuditorById($id_proceso_auditado)->get();

        $relProcesoAuditor = new RelProcesoAuditor();
        $relProcesoAuditor->id_proceso_auditado = $id_proceso_auditado;
        $relProcesoAuditor->id_auditor = $id_auditor;
        if (count($auditores) == 0) {
            $relProcesoAuditor->jefatura_equipo = true;
        }
        $relProcesoAuditor->save();
    }

    function ajaxProcesoAuditado(Request $request) {

        $id_centro_responsabilidad = $request->input('id_centro_responsabilidad');
        $proceso_auditado = ProcesoAuditado::where('id_centro_responsabilidad', '=', $id_centro_responsabilidad)->get();
        return $proceso_auditado;
    }

    public function gridAjaxAuditor($id) {

        $auditores = ProcesoAuditado::getAuditorById($id);

        if (count($auditores->get()) == 0) {
            $grid = "<div class='alert alert-warning'>
                    <h4><i class='icon fa fa-warning'></i> Atención</h4>
                    El primero auditor debe ser el lider del equipo.
                    </div>";
        } else {
            $grid = \DataGrid::source($auditores);
            $grid->add('id_auditor', 'ID')->style("width:40px");
            $grid->add('nombre_auditor', 'Auditor');
            $grid->add('jefatura_equipo', 'Lider')->cell(function( $value, $row ) {
                return $row->jefatura_equipo ? "<small class='label bg-green'>lider</small>" : "";
            });
        }
        return $grid;
    }

    public function getAnoSelectValues() {
        $anoInicial = date("Y");
        $anoFinal = $anoInicial - 10;
        for ($i = $anoInicial; $i >= $anoFinal; $i--) {
            $ano[$i] = $i;
        }
        return $ano;
    }

}
